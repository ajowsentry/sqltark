<?php

declare(strict_types=1);

namespace SqlTark;

use Generator;
use PDO;
use PDOException;
use SqlTark\Query\MethodType;
use SqlTark\Utilities\Helper;
use SqlTark\Query\QueryInterface;
use SqlTark\Connection\AbstractConnection;

class XQuery extends Query
{
    /**
     * @var ?AbstractConnection $connection
     */
    private ?AbstractConnection $connection = null;

    /**
     * @var bool $resetOnExecute
     */
    private bool $resetOnExecute = true;

    /**
     * @var null|(callable(string,?array<mixed>,?XPDOStatement):void) $onExecuteCallback
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
     * @param (callable(string,?array<mixed>,?XPDOStatement):void) $onExecuteCallback
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
     * @return XPDOStatement Statement
     */
    public function prepare(null|Query|string $query = null, array $params = [], array $types = []): XPDOStatement
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

            /** @var false|XPDOStatement $statement */
            $statement = $pdo->prepare($sql, [
                PDO::ATTR_STATEMENT_CLASS => [XPDOStatement::class]
            ]);

            if(false === $statement) {
                $this->throwPDOException();
            }

            foreach($params as $index => $value) {
                $type = $this->determineType($index, $value, $types);
                $statement->bindValue(1 + $index, $value, $type);
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
     * @return XPDOStatement Statement
     */
    public function execute(null|Query|string $query = null, array $params = [], array $types = []): XPDOStatement
    {
        if ($query instanceof QueryInterface) {
            $sql = $this->compiler->compileQuery($query);
        }
        elseif (is_string($query)) {
            $sql = $query;
        }
        else {
            $sql = $this->compiler->compileQuery($this);
        }

        if (empty($sql)) {
            Helper::throwInvalidArgumentException("Could not resolve '%s'", $query);
        }

        try {
            if(count($params) === 0) {
                /** @var false|XPDOStatement $statement */
                $statement = $this->getConnection()->getPDO()->query($sql);

                if(false === $statement) {
                    $this->throwPDOException();
                }
            }
            else {
                $statement = $this->prepare($sql, $params, $types);
                if(false === $statement->execute()) {
                    $this->throwPDOException();
                }
            }

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

            if(false === $result)
                return null;

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
        return iterator_to_array($this->getIterate($class), false);
    }

    /**
     * @template T
     * @param ?class-string<T> $class
     * @return Generator<int,T,null,void>
     */
    public function getIterate(?string $class = null): Generator
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
     * @param ?XPDOStatement $statement
     * @return void
     */
    private function triggerOnExecute(string $sql, ?array $errorInfo = null, ?XPDOStatement $statement = null): void
    {
        if(is_callable($this->onExecuteCallback)) {
            call_user_func_array($this->onExecuteCallback, [$sql, $errorInfo, $statement]);
        }
    }

    private function throwPDOException(): never
    {
        $pdo = $this->getConnection()->getPDO();
        [$sqlState, $errCode, $errMessage] = $pdo->errorInfo();
        $exMessage = sprintf(
            "SQLSTATE[%s]: Syntax error or access violation: %s %s",
            $sqlState, $errCode, $errMessage,
        );

        throw new PDOException($exMessage, $errCode);
    }
}