<?php

namespace TCB\ErrorSniffer\Handler;

class Monolog implements Handler
{
    protected $monolog;



    public function __construct($monolog)
    {
        $this->monolog = $monolog;
    }

    public function start() {}
    public function finish() {}

    public function log(\TCB\ErrorSniffer\ErrorModel $error)
    {
        $message = $error->getDatetime()->format('Y-m-d H:i:s e').' '.$error->getMessage();

        $this->monolog->log($error->getLevel(), $message);
    }
}
