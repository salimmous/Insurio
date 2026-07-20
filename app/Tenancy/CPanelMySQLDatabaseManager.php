<?php

namespace App\Tenancy;

use Stancl\Tenancy\Contracts\TenantDatabaseManager;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Exceptions\NoConnectionSetException;

class CPanelMySQLDatabaseManager implements TenantDatabaseManager
{
    /** @var string */
    protected $connection;

    protected function database(): Connection
    {
        if ($this->connection === null) {
            throw new NoConnectionSetException(static::class);
        }

        return DB::connection($this->connection);
    }

    public function setConnection(string $connection): void
    {
        $this->connection = $connection;
    }

    public function createDatabase(TenantWithDatabase $tenant): bool
    {
        $database = $tenant->database()->getName();

        // 1. Create MySQL database via UAPI
        $cmdCreate = "uapi Mysql create_database name=" . escapeshellarg($database);
        shell_exec($cmdCreate);

        // 2. Assign the landlord DB user to this database with ALL privileges
        $dbUser = env('DB_USERNAME', 'sc7mosa1422_dbuser');
        $cmdPrivs = "uapi Mysql set_privileges_on_database user=" . escapeshellarg($dbUser) . " database=" . escapeshellarg($database) . " privileges=ALL";
        shell_exec($cmdPrivs);

        return true;
    }

    public function deleteDatabase(TenantWithDatabase $tenant): bool
    {
        $database = $tenant->database()->getName();

        // Delete database via UAPI
        $cmdDelete = "uapi Mysql delete_database name=" . escapeshellarg($database);
        shell_exec($cmdDelete);

        return true;
    }

    public function databaseExists(string $name): bool
    {
        try {
            $result = $this->database()->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$name]);
            return !empty($result);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function makeConnectionConfig(array $baseConfig, string $databaseName): array
    {
        $baseConfig['database'] = $databaseName;

        return $baseConfig;
    }
}
