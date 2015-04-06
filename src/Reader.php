<?php

namespace TCB\LogSniffer;

class Reader
{
    protected $dateLimit = null;

    protected $errorLog;

    protected $handlers = array();



    public function __construct($errorLog)
    {
        if (!file_exists($errorLog)) {
            throw new \Exception('Error log not found: '.$errorLog);
        }

        $this->errorLog = $errorLog;
    }

    public function setDateLimit($dateLimit)
    {
        $this->dateLimit = new \DateTime($dateLimit);

        return $this;
    }

    protected function factoryError($line)
    {
        return new \TCB\LogSniffer\ErrorModel($line);
    }

    protected function isNewError($currLine)
    {
        return (substr($currLine->getMessage(), 0, 12) !== 'Stack trace:' && !is_numeric(substr($currLine->getMessage(), 0, 1)));
    }

    public function addHandler($handler)
    {
        $this->handlers[] = $handler;

        return $this;
    }

    protected function startHandlers()
    {
        foreach ($this->handlers as $index => $handler) {
            $handler->start();
        }
    }

    protected function finishHandlers()
    {
        foreach ($this->handlers as $index => $handler) {
            $handler->finish();
        }
    }

    protected function runHandlers($error)
    {
        // Skip if it's the first iteration. $error will be null.
        if ($error === null) {
            return;
        }

        foreach ($this->handlers as $index => $handler) {
            $handler->log($error);
        }

        $this->numberErrors++;
    }

    public function run()
    {
        $this->startHandlers();

        // Have to append an empty line to the log so that
        // when reversing the file and reading it works.
        $handle = fopen($this->errorLog, 'r');

        $this->numberErrors = 0;
        $error              = null;

        while (($line = fgets($handle)) !== false) {
            // Should we continue?
            // - Not at dateLimit yet?
            // - Or line is empty.
            if (trim($line) == '') {
                continue;
            }

            $currLine = $this->factoryError($line);

            // If the dateLimit has been reached break the loop.
            if ($this->dateLimit !== null && $this->dateLimit > $currLine->getDatetime()) {
                continue;
            }

            // If first characters in line isn't "Stack trace:" or " " (a space).
            // - Create the ErrorModel object.
            if ($this->isNewError($currLine)) {
                $this->runHandlers($error);

                $error = $this->factoryError($line);
            }
            // Else add the line to the current error.
            else {
                $error->parseLine($line);
            }
        }

        $this->runHandlers($error);

        // Close the error log file handler.
        fclose($handle);

        $this->finishHandlers();

        return $this->numberErrors;
    }
}
