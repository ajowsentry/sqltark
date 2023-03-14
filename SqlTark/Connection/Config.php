<?php

declare(strict_types=1);

namespace SqlTark\Connection;

use PDO;

class Config
{
    /**
     * @var string $host
     */
    private string $host = 'localhost';

    /**
     * @var ?int $port
     */
    private ?int $port = null;

    /**
     * @var string $username
     */
    private string $username = '';
    
    /**
     * @var string $password
     */
    private string $password = '';

    /**
     * @var string $database
     */
    private string $database = '';

    /**
     * @var string $charset
     */
    private string $charset = 'utf8';

    /**
     * @var string $collation
     */
    private string $collation = 'utf8_general_ci';

    /**
     * @var array<int,mixed> $attributes
     */
    private array $attributes = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    /**
     * @param array<string,mixed> $config
     */
    public function __construct(array $config = [])
    {
        if (!empty($config['host'])) {
            $this->host = $config['host'];
        }

        if (!empty($config['port'])) {
            $this->port = (int) $config['port'];
        }

        if (!empty($config['username'])) {
            $this->username = $config['username'];
        }

        if (!empty($config['password'])) {
            $this->password = $config['password'];
        }

        if (!empty($config['database'])) {
            $this->database = $config['database'];
        }

        if (!empty($config['charset'])) {
            $this->charset = $config['charset'];
        }

        if (!empty($config['collation'])) {
            $this->collation = $config['collation'];
        }

        if (!empty($config['attributes'])) {
            $this->attributes = array_merge($this->attributes, $config['attributes']);
        }
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return ?int
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * @return string
     */
    public function getCollation(): string
    {
        return $this->collation;
    }

    /**
     * @return array<int,mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param int $pdoAttribute
     * @return mixed
     */
    public function getAttribute(int $pdoAttribute): mixed
    {
        return $this->attributes[$pdoAttribute] ?? null;
    }

    /**
     * @return PDO::FETCH_*
     */
    public function getFetchMode(): int
    {
        return $this->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE);
    }

    /**
     * @param string $value
     * @return void
     */
    public function setHost(string $value): void
    {
        $this->host = $value;
    }

    /**
     * @param ?int $value
     * @return void
     */
    public function setPort(?int $value): void
    {
        $this->port = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setUsername(string $value): void
    {
        $this->username = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setPassword(string $value): void
    {
        $this->password = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setDatabase(string $value): void
    {
        $this->database = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setCharset(string $value): void
    {
        $this->charset = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setCollation(string $value): void
    {
        $this->collation = $value;
    }

    /**
     * @param array<int,mixed> $value
     * @return void
     */
    public function setAttributes(array $value): void
    {
        $this->attributes = $value;
    }

    /**
     * @param int $pdoAttribute
     * @param mixed $value
     * @return void
     */
    public function setAttribute(int $pdoAttribute, mixed $value): void
    {
        $this->attributes[$pdoAttribute] = $value;
    }

    /**
     * @param int $pdoAttribute
     * @return void
     */
    public function unsetAttribute(int $pdoAttribute): void
    {
        unset($this->attributes[$pdoAttribute]);
    }

    /**
     * @param int $value
     * @return void
     */
    public function setFetchMode(int $value): void
    {
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $value);
    }
}