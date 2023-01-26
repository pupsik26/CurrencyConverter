<?php

class Connection
{

    private string $dbName = 'my_db';
    private string $host = 'mysql';
    private string $userName = 'root';
    private string $password = 'root';
    public ?PDO $connection = null;

    public function getConnection(): ?PDO
    {
        try {
            $this->connection = new PDO("mysql:dbname=$this->dbName;host=$this->host",
                $this->userName,
                $this->password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
            );
        } catch (PDOException $exception) {
            die("Ошибка соединения: " . $exception->getMessage());
        }

        return $this->connection;
    }

}