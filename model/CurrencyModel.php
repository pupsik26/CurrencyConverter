<?php
require_once 'basic/Model.php';

class CurrencyModel extends Model
{
    public $id;
    public $ValuteID;
    public $NumCode;
    public $CharCode;
    public $Nominal;
    public $Name;
    public $Value;
    public $OldValue;
    public $isRefresh;


    public function updateIsRefresh($value)
    {
        $sth = $this->connection->prepare("UPDATE `{$this->table}` SET `isRefresh` = :isRefresh WHERE `id` = :id");
        $sth->execute(['isRefresh' => (int)$value, 'id' => $this->id]);
    }

}