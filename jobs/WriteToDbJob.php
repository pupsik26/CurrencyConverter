<?php
require_once 'model/basic/CurlXml.php';
require_once 'model/CurrencyModel.php';

class WriteToDbJob
{

    public function write()
    {
        $arrCurrency = (new CurlXml())->parseXml();
        foreach ($arrCurrency as $key => $item) {
            $model = new CurrencyModel();
            $value = $model->find(['ValuteID' => $item['ValuteID']]);
            if (!empty($value)) {
                $item = array_merge($item, ['OldValue' => (float)$value['Value']]);
                if ($value['isRefresh'] == '1') {
                    $model->load($item);
                    $model->firstOrCreate([
                        'ValuteID' => $item['ValuteID']
                    ], $item);
                }
            } else {
                $model->load($item);
                $model->firstOrCreate([
                    'ValuteID' => $item['ValuteID']
                ], $item);
            }
        }
    }

}


