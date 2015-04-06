<?php

namespace TCB\LogSniffer\Handler;

class MemoryOutput implements Handler
{
    public function start() {}
    public function finish() {}

    protected function format($size)
    {
        if ($size >= 1 << 50) {
            return number_format($size / (1 << 50), 2)."PB";
        }
        elseif ($size >= 1 << 40) {
            return number_format($size / (1 << 40), 2)."TB";
        }
        elseif ($size >= 1 << 30) {
            return number_format($size / (1 << 30), 2)."GB";
        }
        elseif ($size >= 1 << 20) {
            return number_format($size / (1 << 20), 2)."MB";
        }
        elseif ($size >= 1 << 10) {
            return number_format($size / (1 << 10), 2)."KB";
        }

        return number_format($size)." bytes";
    }

    public function log(\TCB\LogSniffer\ErrorModel $error)
    {
        echo "\nMemory: ". $this->format(memory_get_usage());
    }
}