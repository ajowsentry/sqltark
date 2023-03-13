<?php

declare(strict_types=1);

namespace SqlTark;

use PDO;
use PDOStatement;
use SqlTark\Query\MethodType;
use SqlTark\Utilities\Helper;
use SqlTark\Query\QueryInterface;
use SqlTark\Connection\AbstractConnection;

class XQuery extends Query
{
    /**
     * @var ?AbstractConnection $connection
     */
    private ?AbstractConnection $connection;

    /**
     * @var bool $resetOnExecute
     */
    private bool $resetOnExecute = true;

    /**
     * @var null|(callable(string,?array<mixed>,?PDOStatement):void) $onExecuteCallback
     */
    private mixed $onExecuteCallback = null;

    /**
     * @return AbstractConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param AbstractConnection $connection
     * @return static
     */
    public function setConnection(AbstractConnection $connection): static
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * @return bool
     */
    public function isResetOnExecute(): bool
    {
        return $this->resetOnExecute;
    }

    /**
     * @param (callable(string,?array<mixed>,?PDOStatement):void) $onExecuteCallback
     * @return static Self object
     */
    public function onExecute(callable $onExecuteCallback): static
    {
        $this->onExecuteCallback = $onExecuteCallback;
        return $this;
    }

    /**
     * @param bool $value
     * @return static Self object
     */
    public function resetOnExecute(bool $value = true): static
    {
        $this->resetOnExecute = $value;
        return $this;
    }

    /**
     * @return static Self object
     */
    public function reset(): static
    {
        if(!is_null($this->components)) {
            $this->clearComponents();
        }

        $this->method = MethodType::Select;

        return $this;
    }

    /**
     * @param int $index
     * @param mixed $value
     * @param list<mixed> $types
     * @return int
     */
    private function determineType(int $index, mixed $value, array $types): int
    {
        if(array_key_exists($index, $types)) {
            return $types[$index];
        }

        return match (Helper::getType($value)) {
            'bool'    => PDO::PARAM_BOOL,
            'int'     => PDO::PARAM_INT,
            'integer' => PDO::PARAM_INT,
            'null'    => PDO::PARAM_NULL,
            default   => PDO::PARAM_STR,
        };
    }

    /**
     * @param null|Query|string $query
     * @param list<mixed> $params
     * @param list<mixed> $types
     * @return PDOStatement Statement
     */
    public function prepare(null|Query|string $query = null, array $params = [], array $types = []): PDOStatement
    {
        if(func_num_args() === 0) {
            $query = $this->compiler->compileQuery($this);
        }

        if ($query instanceof Query) {
            $sql = $this->compiler->compileQuery($query);
        }
        elseif (is_string($query)) {
            $sql = $query;
        }

        if (empty($sql)) {
            Helper::throwInvalidArgumentException("Could not resolve '%s'", $query);
        }

        try {
            $pdo = $this->connection->getPDO();

            $statement = $pdo->prepare($sql);
            foreach($params as $index => $value) {
                $type = $this->determineType($index, $value, $types);
                $statement->bindValue($index, $value, $type);
            }

            return $statement;
        }
        finally {
            if($this->resetOnExecute) {
                $this->reset();
            }
        }
    }

    /**
     * @param null|Query|string $query
     * @param list<mixed> $params
     * @param list<mixed> $types
     * @return PDOStatement Statement
     */
    public function execute(null|Query|string $query = null, array $params = [], array $types = []): PDOStatement
    {
        if(func_num_args() === 0) {
            $query = $this->compiler->compileQuery($query ?? $this);
        }

        if ($query instanceof QueryInterface) {
            $sql = $this->compiler->compileQuery($query);
        }
        elseif (is_string($query)) {
            $sql = $query;
        }

        if (empty($sql)) {
            Helper::throwInvalidArgumentException("Could not resolve '%s'", $query);
        }

        try {
            $statement = $this->prepare($sql, $params, $types);
            $statement->execute();

            return $statement;
        }
        finally {
            if(isset($statement)) {
                $this->triggerOnExecute($sql, $statement->errorInfo(), $statement);
            }
            else {
                $this->triggerOnExecute($sql);
            }
        }
    }

    /**
     * @param string $name
     * [optional] Name of the sequence object from which the ID should be returned.
     * 
     * @return string|false
     * If a sequence name was not specified for the name parameter, PDO::lastInsertId
     * returns a string representing the row ID of the last row that was inserted
     * into the database.
     */
    public function lastInsertId(?string $name = null): string|false
    {
        return $this->connection->getPDO()->lastInsertId($name);
    }

    /**
     * @return bool
     */
    public function transaction(): bool
    {
        return $this->connection->transaction();
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->connection->rollback();
    }

    /**
     * @template T
     * @param ?class-string<T> $class
     * @return ?T
     */
    public function getOne(?string $class = null): mixed
    {
        $this->method = MethodType::Select;

        $statement = $this->limit(1)->execute($this);

        try {
            $result = !is_null($class)
                ? $statement->fetch(PDO::FETCH_ASSOC)
                : $statement->fetch();

            return !is_null($class)
                ? new $class($result)
                : $result;
        }
        finally {
            $statement->closeCursor();
        }
    }

    /**
     * @template T
     * @param ?class-string<T> $class
     * @return list<T>
     */
    public function getAll(?string $class = null): array
    {
        return iterator_to_array($this->getIterate($class));
    }

    /**
     * @template T
     * @param ?class-string<T> $class
     * @return iterable<T>
     */
    public function getIterate(?string $class = null): iterable
    {
        $this->method = MethodType::Select;
        $statement = $this->execute($this);

        try {
            if(!is_null($class)) {
                while(false !== ($row = $statement->fetch(PDO::FETCH_ASSOC))) {
                    yield new $class($row);
                }
            }
            else {
                while(false !== ($row = $statement->fetch())) {
                    yield $row;
                }
            }
        }
        finally {
            $statement->closeCursor();
        }
    }

    /**
     * @param int $columnIndex
     * @return mixed
     */
    public function getScalar(int $columnIndex = 0): mixed
    {
        $this->method = MethodType::Select;

        $statement = $this->execute($this);
        $result = $statement->fetchColumn($columnIndex);
        $statement->closeCursor();

        return $result;
    }

    /**
     * @param string $sql
     * @param ?list<mixed> $errorInfo
     * @param ?PDOStatement $statement
     * @return mixed
     */
    private function triggerOnExecute(string $sql, ?array $errorInfo = null, ?PDOStatement $statement = null): mixed
    {
        return is_callable($this->onExecuteCallback)
            ? call_user_func_array($this->onExecuteCallback, [$sql, $errorInfo, $statement])
            : null;
    }
}