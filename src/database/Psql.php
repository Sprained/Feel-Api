<?php

namespace Feel\Database;

class Psql
{
    function __destruct()
    {
        $this->disconnect();
    }

    public function connect()
    {
        return pg_connect("host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_DATABASE . " user=" . DB_USER . " password=" . DB_PASSWORD);
    }

    private function disconnect()
    {
        pg_close($this->connect());
    }
}