<?php
// Database Type : "SQLite"
// Database Adapter : "sqlite"
$exports = <<<'JSON'
{
    "name": "db",
    "module": "dbconnector",
    "action": "connect",
    "options": {
        "server": "sqlite",
        "databaseType": "SQLite",
        "connectionString": "sqlite:../db/db_dev.sqlite3"
    }
}
JSON;
?>