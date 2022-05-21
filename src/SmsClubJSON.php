<?php


namespace library\Smsclub;
use library\Smsclub\SmsClubLogger;



class SmsClubJSON extends Basic
{
    protected static $ulr_send = 'https://im.smsclub.mobi/sms/send';
    protected static $ulr_originator = 'https://im.smsclub.mobi/sms/originator';
    protected static $ulr_balance = 'https://im.smsclub.mobi/sms/balance';
    protected static $ulr_status = 'https://im.smsclub.mobi/sms/status';
    public static $stack = [];

    private function __construct($number, $message_id, $hash, $data)
    {
        $this->message_id = $message_id;
        $this->number = $number;
        $this->data = $data;
        self::$stack[] = $hash;
        if (self::$saveToDataBase)
        {
            // save dataBase
        }
    }

    public static function create($arr)
    {
        $hash = self::setHash($arr);
        if (isset(self::$stack[$hash])) return self::$stack[$hash];
        $header = self::setHeader(self::$ulr_send, json_encode($arr));
        $result = self::openConnect($header);
        $log = SmsClubLogger::create($result);
        $log->log($result);
        try {
            $result = self::parseResult($result);
            $id = array_keys($result);
            $number = array_values($result);
            return self::$stack[$hash] = new self($number[0], $id[0], $hash, $arr);
        } catch (\Exception $exception)
        {
            echo $exception->getMessage();
        }
    }

    public static function balance()
    {
        $header = self::setHeader(self::$ulr_balance, null);
        $result = self::openConnect($header);
        $result = self::parseResult($result);
        $log = SmsClubLogger::create($result);
        $log->log($result);
        return $result;
    }

    public static function originators()
    {
        $header = self::setHeader(self::$ulr_originator, null);
        $result = self::openConnect($header);
        $result = self::parseResult($result);
        $log = SmsClubLogger::create(implode(" ", $result));
        $log->log(implode(" ", $result));
        return $result;
    }

    protected static function setHeader($url, $data)
    {
        return [
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . self::$token,
                'Content-Type: application/json'
            ]
        ];
    }

    public static function parseResult($result)
    {
        $result = (array) json_decode($result);
        try {
            $result = (array) self::checkingResult($result)['info'];
            return $result;
        } catch (\Exception $exception)
        {
            echo $exception->getMessage();
        }
    }

    public function checkingResult($result)
    {

        if ((key_exists('name', $result)))
        {
            throw new \Exception('Auth faild');
        }
        if (!key_exists('success_request', $result)){
            throw new \Exception("Error sending... \n checking log file!!!");
        }
        $result = (array) $result['success_request'];
        if ((!key_exists('info', $result)))
        {
            throw new \Exception(implode("", (array)$result["add_info"]));
        }
        return $result;
    }

}