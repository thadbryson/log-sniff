<?php

namespace TCB\ErrorSniffer\Handler;

class PrintOutput implements Handler
{
    public $on = true;



    public function start() {}

    public function finish() {}

    public function log(\TCB\ErrorSniffer\ErrorModel $error)
    {
        if ($this->on === true) {
            echo "\n\nError:\n\n[".$error->getDatetime()->format('Y-m-d H:i:s e').'] '.$error->getLevel()."\n".$error->getMessage()."\n";
        }
    }
}