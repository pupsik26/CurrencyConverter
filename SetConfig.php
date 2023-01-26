<?php
require_once 'model/CurrencyModel.php';

class SetConfig
{
    public $data;

    public function __construct()
    {
        $this->data = json_decode(file_get_contents("php://input"), true);
    }

    public function setRefresh()
    {
        $model = new CurrencyModel();
        $this->setRefreshAllTrue($model);
        foreach ($this->data as $item) {
            $value = $model->find(['ValuteID' => $item['ValuteID']]);
            if (!empty($value)) {
                $model->load($value);
                $model->id = $value['id'];
                $model->updateIsRefresh(false);
            }
        }
    }

    public function setRefreshAllTrue(CurrencyModel $model)
    {
        $allItem = $model->find();
        foreach ($allItem as $item) {
            $value = $model->find(['ValuteID' => $item['ValuteID']]);
            $model->load($value);
            $model->id = $value['id'];
            $model->updateIsRefresh(true);
        }
    }
}

$model = new SetConfig();
$model->setRefresh();