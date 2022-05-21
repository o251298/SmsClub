<?php


namespace library\Smsclub;


class SmsClubLogger
{
    protected static $stack = [];
    protected $f;
    protected $lines = [];

    public function __construct($filename)
    {
        $this->mkDir();
        $this->f = fopen('../SmsClubLogger/' . date('Y_m_d') . '.log', 'a+');
    }

    public static function create($filename)
    {
        if (!self::$stack[$filename]) return self::$stack[] = new self($filename);
        return self::$stack[$filename];
    }

    public function log($str)
    {
        $prefix = "[" . date('Y-m-d_H:i:s') . "]";
        $str = preg_replace('/^/m', $prefix, rtrim($str));
        $this->lines[] = $str . "\n";
    }

    public function close()
    {
        fputs($this->f, implode("", $this->lines));
        fclose($this->f);
    }
    public function __destruct()
    {
        $this->close();
    }
    public function mkDir()
    {
        if (!is_dir('../SmsClubLogger')) mkdir('../SmsClubLogger/', 0777);
    }
}