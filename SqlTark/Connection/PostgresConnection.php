<?php

declare(strict_types=1);

namespace SqlTark\Connection;

use SqlTark\Connection\AbstractConnection;

class PostgresConnection extends AbstractConnection
{
    /**
     * {@inheritdoc}
     */
    protected string $driver = 'pgsql';

    /**
     * {@inheritdoc}
     */
    protected function createDSN(): string
    {
        $host = $this->config->getHost();
        $port = $this->config->getPort();
        $database = $this->config->getDatabase();

        $dsn = "pgsql:host={$host}";
        if(!is_null($port)) {
            $dsn .= ";port={$port}";
        }

        $dsn .= ";dbname={$database}";
        if(!is_null($user = $this->config->getUsername())) {
            $dsn .= ";user={$user}";
        }

        if(!is_null($password = $this->config->getPassword())) {
            $dsn .= ";password={$password}";
        }

        if(!is_null($sslmode = $this->config->getExtras('sslmode'))) {
            $dsn .= ";sslmode={$sslmode}";
        }

        return $dsn;
    }
}