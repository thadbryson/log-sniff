<?php

class ReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testReader()
    {
        $reader = new \TCB\ErrorSniffer\Reader(__DIR__.'/files/error_log_test');
        $reader->setDateLimit('2014-09-02');

        $monolog = new Monolog\Logger('testLogger');
        $monolog->pushHandler(new Monolog\Handler\NullHandler());

        $testHandler = new Monolog\Handler\TestHandler();
        $monolog->pushHandler($testHandler);

        $reader->addHandler(new TCB\ErrorSniffer\Handler\Monolog($monolog));

        $printOutput = new TCB\ErrorSniffer\Handler\PrintOutput();
        $printOutput->on = false;

        $reader->addHandler($printOutput);

        $reader->addHandler(new TCB\ErrorSniffer\Handler\MemoryOutput());
        $reader->addHandler(new TCB\ErrorSniffer\Handler\RSS(__DIR__.'/files/rss'));

        $this->assertEquals(1, $reader->run());
        $this->assertEquals(1, count($testHandler->getRecords()));

        foreach ($testHandler->getRecords() as $record) {
            $this->assertTrue($testHandler->hasWarning($record));
        }
    }
}
