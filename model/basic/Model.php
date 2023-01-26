<?php

require_once 'Connection.php';

class Model
{

    protected ?PDO $connection;

    protected string $table;

    public function __construct()
    {
        $database = new Connection();
        $this->connection = $database->getConnection();
        $this->table = $this->parseClassName();
    }

    public function create()
    {
        $vars = get_object_vars($this);
        unset($vars['table'], $vars['id'], $vars['connection']);
        $insert = "INSERT INTO `{$this->table}` SET ";
        $arg = $this->parseParams($vars, $insert);
        $insert = rtrim(trim($arg[0]), ',');
        $this->execute($insert, $arg[1]);
    }

    public function firstOrCreate(array $terms, array $params)
    {
        $arrayOrFalse = $this->find($terms);
        if ($arrayOrFalse === false) {
            $this->create();
        } else {
            $this->update(['id' => $arrayOrFalse['id']]);
        }
    }

    public function setTableName(string $name)
    {
        $this->table = $name;
    }

    public function findById(int $id)
    {
        $select = "SELECT * FROM `{$this->table}` WHERE `id` = :id";
        $sth = $this->execute($select, ['id' => $id]);
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function find(array $params = [])
    {
        // пока что тут, в будущем добавить отдельную проверку
        if (empty($params)) {
            $select = "SELECT * FROM `{$this->table}`";
            $sth = $this->connection->prepare($select);
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);

        }
        $select = "SELECT * FROM `{$this->table}` WHERE ";
        $arg = $this->parseParams($params, '', 'AND');
        $select .= rtrim(trim($arg[0]), 'AND');
        $sth = $this->execute($select, $arg[1]);
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function update(array $terms)
    {
        $vars = get_object_vars($this);
        unset($vars['table'], $vars['id'], $vars['connection']);
        if (empty($vars)) {
            $exception= new Exception('Ошибка. Массив атрибутов пустой.');
            die($exception->getMessage());
        }
        $update = "UPDATE {$this->table} SET ";
        $arg = $this->parseParams($vars, $update);
        $update = rtrim(trim($arg[0]), ',') . " WHERE "
            . rtrim(trim($this->parseParams($terms, '')[0]), ',');
        $this->execute($update, array_merge($arg[1], $terms));
    }

    public function load(array $params)
    {
        $vars = get_class_vars(static::class);
        unset($vars['table'], $vars['id'], $vars['connection']);
        foreach ($params as $key => $param) {
            if (array_key_exists($key, $vars)) {
                $this->{$key} = $param;
            }
        }
    }

    private function parseClassName(): array|string
    {
        return str_replace('Model', '', static::class);
    }

    private function execute($str, $params): bool|PDOStatement
    {
        // пока проверяем так...
        if (empty($params)) {
            $exception= new Exception('Ошибка. Массив параметров пустой.');
            die($exception->getMessage());
        }
        try {
            $sth = $this->connection->prepare($str);
            $sth->execute($params);
            return $sth;
        } catch (Exception $exception) {
            die('Ошибка добавления записи: ' . $exception->getMessage());
        }
    }

    private function parseParams(array $vars, $query, $term = ','): array
    {
        $execute = array();
        foreach ($vars as $key => $var) {

            if (!empty($var)) {
                $query .= "`{$key}` = :{$key} " . $term . " ";
                $execute[$key] = $var;
            }
        }
        return [$query, $execute];
    }

    /* @todo Добавить чек параметров на сво-ва (rules) */
    private function checkParams($params, $method)
    {

    }

}