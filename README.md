# Log Sniffer

* Do you need to check your error logs?
* Do you need to get alerts through email and txt when you get serious errors?
* Do you not have the time to change all your code to do that?

Currently we just read PHP logs. Working on more though.

You can use this tool to scan your error logs and send wake up calls to your team.

Can use [Monolog](https://github.com/Seldaek/monolog) handlers for sending email, text messages, writing to chat rooms, etc.

```php
// Create the Reader and tell it what error log to read.
$reader = new \TCB\LogSniffer\Reader(__DIR__.'/files/error_log_test');
// Set a date limit. Only read from that point.
$reader->setDateLimit('2014-09-02');

// Pass in Monolog handlers. They have bunches. For sending emails, texts, or whatever.
$monolog = new Monolog\Logger('testLogger');
$monolog->pushHandler(new Monolog\Handler\NullHandler());

$testHandler = new Monolog\Handler\TestHandler();
$monolog->pushHandler($testHandler);

// Add Monolog to the Log Sniffer.
$reader->addHandler(new TCB\LogSniffer\Handler\Monolog($monolog));

// The PrintOutput handler sends output to the command line.
$printOutput = new TCB\LogSniffer\Handler\PrintOutput();

$reader->addHandler($printOutput);
$reader->addHandler(new TCB\LogSniffer\Handler\MemoryOutput());
$reader->addHandler(new TCB\LogSniffer\Handler\RSS(__DIR__.'/files/rss'));

//
$reader->run();
```

