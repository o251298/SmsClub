<?php


namespace library\Smsclub;


class SmsClubXML extends Basic
{
    protected static $ulr_send = 'https://gate.smsclub.mobi/xml/';
    protected static $ulr_status = 'https://gate.smsclub.mobi/xml/state.php';

    protected static function setHeader($url, $data)
    {
        return [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/xml"
            ]
        ];
    }

    public static function create($arr)
    {
        $hash = self::setHash($arr);
        $number = null;
        if (!is_string($arr))
        {
            $xml = self::message($arr['src_addr'], $arr['phone'], $arr['message']);
            $number = $arr['phone'];
        } else {
            $xml = $arr;
            $find = simplexml_load_string($xml);
            $number = (int) $find->to;
        }
        $header = self::setHeader(self::$ulr_send, $xml);
        $result = self::openConnect($header);
        try {
            $res = self::parseResult($result);
            return self::$stack[$hash] = new self($number, $res['message_id'], $hash, $arr);
        } catch (\Exception $exception)
        {
            echo $exception->getMessage();
        }
    }

    public static function parseResult($str)
    {
        $response = simplexml_load_string($str);
        if (!$response) throw new \Exception('Ошибка отправки смс');
        $status = (string) $response->status[0];
        if ($status != "OK")
        {
            throw new \Exception((string) $response->text);
        }
        return array('message_id' => $response->ids['mess']);
    }

    public static function message($from, $to, $text)
    {
        $to = (int) $to[0];
        $user = self::$login;
        $password = self::$password;
        $xml = "<?xml version='1.0' encoding='utf-8'?>
<request_sendsms>
<username><![CDATA[$user]]></username>
<password><![CDATA[$password]]></password>
<from><![CDATA[$from]]></from>
<to><![CDATA[$to]]></to>
<text><![CDATA[$text]]></text>
</request_sendsms>";
        return $xml;
    }

}