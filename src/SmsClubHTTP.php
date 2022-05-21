<?php


namespace library\Smsclub;


class SmsClubHTTP extends Basic
{

    protected static $ulr_send = 'https://gate.smsclub.mobi/token/';
    protected static $ulr_balance = "https://gate.smsclub.mobi/token/getbalance.php";
    protected static $ulr_status = 'https://im.smsclub.mobi/sms/status';
    public static $stack = [];

    public static function balance()
    {
        $header = self::setHeader(self::$ulr_balance . self::auth());
        $res = self::openConnect($header);
        echo $res;
    }

    public static function create($arr)
    {
        if (!is_string($arr))
        {
            $str = self::messageToString($arr["src_addr"], $arr['phone'], $arr["message"]);
        } else {
            $str = $arr;
        }
        $header = self::setHeader($str);
        $res = self::openConnect($header);
    }

    protected static function setHeader($url)
    {
        return [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
        ];
    }

    protected static function auth()
    {
        $login = self::$login;
        $token = self::$token;
        return "?username=$login&token=$token";
    }

    protected static function messageToString($from, $to, $text)
    {
        $message = self::$ulr_send . self::auth() . "&from=$from&to=$to&text=$text";
        return $message;
    }


}