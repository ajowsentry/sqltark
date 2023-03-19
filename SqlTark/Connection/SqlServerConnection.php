<?php

declare(strict_types=1);

namespace SqlTark\Connection;

use SqlTark\Connection\AbstractConnection;
use SqlTark\Utilities\Helper;

class SqlServerConnection extends AbstractConnection
{
    /**
     * {@inheritdoc}
     */
    protected string $driver = 'sqlsrv';

    /**
     * {@inheritdoc}
     */
    protected function createDSN(): string
    {
        $host = $this->config->getHost();
        $port = $this->config->getPort();
        $database = $this->config->getDatabase();

        $dsn = "sqlsrv:Server={$host}";
        if(!is_null($port)) {
            $dsn .= Helper::isOSWindows() ? ",{$port}" : ":{$port}";
        }

        $dsn .= ";Database={$database}";

        $connectionPooling = $this->config->getExtras(['connectionPooling', 'connection_pooling']);
        if(!is_null($connectionPooling)) {
            $connectionPooling = intval($connectionPooling);
            $dsn .= ";connectionPooling={$connectionPooling}";
        }

        $encrypt = $this->config->getExtras('encrypt');
        if(!is_null($encrypt)) {
            $encrypt = intval($encrypt);
            $dsn .= ";Encrypt={$encrypt}";
        }

        $failoverPartner = $this->config->getExtras(['failoverPartner', 'failover_partner']);
        if(!is_null($failoverPartner)) {
            $dsn .= ";Failover_Partner={$failoverPartner}";
        }

        $loginTimeout = $this->config->getExtras(['loginTimeout', 'login_timeout']);
        if(!is_null($loginTimeout)) {
            $dsn .= ";LoginTimeout={$loginTimeout}";
        }

        $multipleActiveResultSets = $this->config->getExtras(['multipleActiveResultSets', 'multiple_active_result_sets']);
        if(!is_null($multipleActiveResultSets)) {
            $dsn .= ";MultipleActiveResultSets={$multipleActiveResultSets}";
        }

        $quotedID = $this->config->getExtras(['quotedID', 'quoted_id']);
        if(!is_null($quotedID)) {
            $quotedID = intval($quotedID);
            $dsn .= ";QuotedId={$quotedID}";
        }

        $traceFile = $this->config->getExtras(['traceFile', 'trace_file']);
        if(!is_null($traceFile)) {
            $dsn .= ";TraceFile={$traceFile}";
        }

        $traceOn = $this->config->getExtras(['traceOn', 'trace_on']);
        if(!is_null($traceOn)) {
            $traceOn = intval($traceOn);
            $dsn .= ";TraceOn={$traceOn}";
        }

        $transactionIsolation = $this->config->getExtras(['transactionIsolation', 'transaction_isolation']);
        if(!is_null($transactionIsolation)) {
            $dsn .= ";TransactionIsolation={$transactionIsolation}";
        }

        $trustServerCertificate = $this->config->getExtras(['trustServerCertificate', 'trust_server_certificate']);
        if(!is_null($trustServerCertificate)) {
            $trustServerCertificate = intval($trustServerCertificate);
            $dsn .= ";TrustServerCertificate={$trustServerCertificate}";
        }

        $wsid = $this->config->getExtras('wsid');
        if(!is_null($wsid)) {
            $dsn .= ";WSID={$wsid}";
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