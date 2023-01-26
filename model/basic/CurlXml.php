<?php


class CurlXml
{
    private string $url = 'http://www.cbr.ru/scripts/XML_daily.asp';
    public false|null|SimpleXMLElement|string $xml;
    public mixed $json;

    public function __construct()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->url);

        $data = curl_exec($ch);
        if (curl_error($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        if (isset($error_msg)) {
             throw new Exception('Ошибка. Запрашиваемый сайт не отвечает. ' . $error_msg, 500);
        } else {
            $this->xml = simplexml_load_string($data);
            $json = json_encode($this->xml);
            $this->json = json_decode($json, TRUE);
        }
    }

    public function parseXml(): array
    {
        $arr = array();
        foreach ($this->json as $key => $val) {
            unset($val['Date'], $val['name']);
            foreach ($val as $keyy => $item) {
                $arr[] = [
                    'ValuteID' => $item['@attributes']['ID'],
                    'NumCode' => $item['NumCode'],
                    'CharCode' => $item['CharCode'],
                    'Nominal' => $item['Nominal'],
                    'Name' => $item['Name'],
                    'Value' => (float)str_replace(',', '.', $item['Value']),
                ];
            }
        }
        return $arr;
    }

}
