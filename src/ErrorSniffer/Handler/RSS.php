<?php

namespace TCB\ErrorSniffer\Handler;

class RSS implements Handler
{
    protected $file;



    public function __construct($file)
    {
        $this->file = $file;
    }

    public function start()
    {
        $now = date('D, d M Y H:i:s O');

        $init = <<<EOT
<?xml version='1.0' encoding='UTF-8'?>
<rss version='2.0'>
<channel>
    <title>Error Log</title>
    <language>en-us</language>
    <pubDate>{$now}</pubDate>
EOT;

        file_put_contents($this->file, $init);
    }

    public function finish()
    {
        file_put_contents($this->file, "\n</channel>\n</rss>", FILE_APPEND);
    }

    public function log(\TCB\ErrorSniffer\ErrorModel $error)
    {
        $item = <<<EOT

    <item>
        <title>{$error->getLevel()}</title>
        <pubDate>{$error->getDatetime()->format('D, d M Y H:i:s O')}</pubDate>
        <description>
{$error->getMessage()}
        </description>
    </item>
EOT;

        file_put_contents($this->file, $item, FILE_APPEND);
    }
}