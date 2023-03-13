<?php

declare(strict_types=1);

namespace SqlTark\Connection;

use InvalidArgumentException;
use PDO;

abstract class AbstractConnection
{
    /**
     * @var ?PDO $pdo
     */
    protected ?PDO $pdo = null;

    /**
     * @var string $driver
     */
    protected string $driver = '';

    /**
     * @var Config $config
     */
    protected Config $config;

    /**
     * @var int $fetchMode
     */
    protected int $transactionCount = 0;

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @return PDO
     */
    public function getPDO(): PDO
    {
        return $this->pdo ?? $this->connect();
    }

    /**
     * @param array<string,mixed> $config
     */
    public function __construct($config = [])
    {
        $this->config = new Config($config);
    }

    /**
     * @return string
     */
    abstract protected function createDSN(): string;

    public function __destruct()
    {
        $this->pdo = null;
    }

    /**
     * @return PDO
     */
    public function connect(): PDO
    {
        if (!in_array($this->driver, PDO::getAvailableDrivers(), true)) {
            throw new InvalidArgumentException(
                "PDO driver '{$this->driver}' not available"
            );
        }

        $dsn = $this->createDSN();

        $this->pdo = new PDO($dsn, $this->config->getUsername(), $this->config->getPassword());
        foreach ($this->config->getAttributes() as $key => $value) {
            $this->getPDO()->setAttribute($key, $value);
        }

        $this->onConnected();

        return $this->pdo;
    }

    /**
     * @return bool
     */
    public function transaction(): bool
    {
        return $this->getPDO()->beginTransaction();
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->getPDO()->commit();
    }

    /**
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->getPDO()->rollBack();
    }

    /**
     * @return void
     */
    public function resetTransactionState(): void
    {
        $this->transactionCount = 0;
    }

    /**
     * @return void
     */
    protected function onConnected(): void { }
}