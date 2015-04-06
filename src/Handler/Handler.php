<?php

namespace TCB\LogSniffer\Handler;

interface Handler
{
    public function start();

    public function finish();

    public function log(\TCB\LogSniffer\ErrorModel $error);
}