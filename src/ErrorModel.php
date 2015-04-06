<?php

namespace TCB\LogSniffer;

class ErrorModel
{
    protected $datetime;

    protected $message = array();



    /**
     * Break a line of the error log file into the object.
     */
    public function __construct($fileLine = null)
    {
        if ($fileLine !== null) {
            $this->parseLine($fileLine);
        }
    }

    public function parseLine($fileLine)
    {
        ereg("\[(.*)\] PHP (.*)", trim($fileLine), $regs);

        list($date, $time, $timezone) = explode(' ', trim($regs[1]));
        $message                      = trim($regs[2]);

        $this->setDatetime($date.' '.$time, $timezone);
        $this->addToMessage($message);
    }

    public function setDatetime($datetime, $timezone)
    {
        $timezone       = new \DateTimeZone($timezone);
        $this->datetime = new \DateTimeImmutable($datetime, $timezone);

        return $this;
    }

    public function getDatetime()
    {
        return $this->datetime;
    }

    public function getLevel()
    {
        $message = $this->getMessage();

        if (substr($message, 0, 10) === 'Deprecated') {
            return 'INFO';
        }
        elseif (substr($message, 0, 6) === 'Notice') {
            return 'NOTICE';
        }
        elseif (substr($message, 0, 7) === 'Warning') {
            return 'WARNING';
        }
        elseif (substr($message, 0, 11) === 'Fatal error' || substr($message, 0, 11) === 'Parse error') {
            return 'ERROR';
        }

        return 'INFO';
    }

    public function addToMessage($message)
    {
        $this->message[] = $message;

        return $this;
    }

    public function getMessage()
    {
        return implode("\n", $this->message);
    }
}
