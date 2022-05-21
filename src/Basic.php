<?php


namespace library\Smsclub;


class Basic implements SmsClub
{

    protected static $login = '';
    protected static $password = '';
    protected static $token = '';
    public $number;
    public $message_id;
    public $data;
    protected static $saveToDataBase = false;

    public static function create($arr)
    {
        // TODO: Implement send() method.
    }

    public static function originators()
    {
        // TODO: Implement originator() method.
    }

    public static function balance()
    {
        // розпарсить ответ, если есть ошибка - вывести ее, если все хорошо - вывод массива с данными и запись в БД
        // TODO: Implement balance() method.
    }

    protected static function parseResult($result)
    {

    }

    protected static function openConnect($header)
    {
        try {
            return self::lowConnect($header);
        } catch (\Exception $exception)
        {
            echo $exception->getMessage();
        }
    }

    public static function lowConnect($header)
    {
        $ch = curl_init();
        curl_setopt_array($ch, $header);
        $result = curl_exec($ch);
        curl_close($ch);
        if (!$result)
        {
            throw new \Exception("Не правильный endpoint или проблема в локальном окружении");
        }
        return $result;
    }

    protected static function setHash($arr)
    {
        return md5(json_encode($arr));
    }

    public function getMessageId()
    {
        return $this->message_id;
    }

    public function getNumber()
    {
        return $this->number;
    }
}