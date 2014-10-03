<?php

namespace TCB\ErrorSniffer\Handler;

interface Handler
{
    public function start();

    public function finish();

    public function log(\TCB\ErrorSniffer\ErrorModel $error);
}