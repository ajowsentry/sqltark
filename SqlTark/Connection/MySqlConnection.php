<?php

declare(strict_types=1);

namespace SqlTark\Connection;

class MySqlConnection extends AbstractConnection
{
    /**
     * {@inheritdoc}
     */
    protected string $driver = 'mysql';

    /**
     * {@inheritdoc}
     */
    protected function createDSN(): string
    {
        $host = $this->config->getHost();
        $port = $this->config->getPort();
        $database = $this->config->getDatabase();

        $dsn = "mysql:host={$host}";
        if(!empty($port)) $dsn .= ":{$port};port={$port}";
        return "{$dsn};dbname={$database}";
    }

    /**
     * {@inheritdoc}
     */
    protected function onConnected(): void
    {
        $charset = $this->config->getCharset();
        $collation = $this->config->getCollation();

        $this->pdo->exec("SET NAMES '{$charset}' COLLATE '{$collation}'");
        $this->pdo->exec("SET CHARACTER SET '{$charset}'");
    }

    /**
     * {@inheritdoc}
     */
    public function transaction(): bool
    {
        if($this->transactionCount++ === 0) {
            return $this->getPDO()->beginTransaction();
        }

        $this->getPDO()->exec("SAVEPOINT __trx_{$this->transactionCount}__");
        return $this->transactionCount >= 0;
    }

    /**
     * {@inheritdoc}
     */
    public function commit(): bool
    {
        if(--$this->transactionCount === 0) {
            return $this->getPDO()->commit();
        }

        return $this->transactionCount >= 0;
    }

    /**
     * {@inheritdoc}
     */
    public function rollback(): bool
    {
        if($this->transactionCount > 1) {
            $this->getPDO()->exec("ROLLBACK TO __trx_{$this->transactionCount}__");
            $this->transactionCount--;
            return true;
        }

        $this->transactionCount--;
        return $this->getPDO()->rollBack();
    }
}