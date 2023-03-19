<?php

declare(strict_types=1);

namespace SqlTark\Connection;

use SqlTark\Utilities\Helper;
use SqlTark\Connection\AbstractConnection;

class DbLibConnection extends AbstractConnection
{
    /**
     * {@inheritdoc}
     */
    protected string $driver = 'dblib';

    /**
     * {@inheritdoc}
     */
    protected function createDSN(): string
    {
        $host = $this->config->getHost();
        $port = $this->config->getPort();
        $database = $this->config->getDatabase();

        $dsn = "dblib:host={$host}";
        if(!is_null($port)) {
            $dsn .= Helper::isOSWindows() ? ",{$port}" : ":{$port}";
        }

        $dsn .= ";dbname={$database}";
        if(!is_null($charset = $this->config->getExtras('charset'))) {
            $dsn .= ";charset={$charset}";
        }

        if(!is_null($appName = $this->config->getExtras(['appname', 'appName']))) {
            $dsn .= ";appname={$appName}";
        }

        return $dsn;
    }

    /**
     * {@inheritdoc}
     */
    public function transaction(): bool
    {
        if($this->transactionCount++ === 0) {
            return $this->getPDO()->beginTransaction();
        }

        $this->getPDO()->exec("SAVE TRANSACTION __trx_{$this->transactionCount}__");
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
            $this->getPDO()->exec("ROLLBACK TRANSACTION __trx_{$this->transactionCount}__");
            $this->transactionCount--;
            return true;
        }

        $this->transactionCount--;
        return $this->getPDO()->rollBack();
    }
}