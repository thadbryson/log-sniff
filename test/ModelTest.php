<?php

class ModelTest extends \PHPUnit_Framework_TestCase
{


    public function testModel()
    {
        $error = new \TCB\ErrorSniffer\ErrorModel("[21-Sep-2014 15:53:53 America/New_York] PHP Warning:  mysql_free_result() expects parameter 1 to be resource, boolean given in /var/www/MyClass.php on line 134");

        $this->assertEquals('Warning:  mysql_free_result() expects parameter 1 to be resource, boolean given in /var/www/MyClass.php on line 134', $error->getMessage());

        $this->assertEquals('WARNING', $error->getLevel());
        $this->assertFalse(('ERROR' === $error->getLevel()));
        $this->assertFalse(('INFO' === $error->getLevel()));
        $this->assertFalse(('NOTICE' === $error->getLevel()));

        $datetime = $error->getDatetime();

        $this->assertEquals('DateTimeImmutable', get_class($datetime));
        $this->assertEquals('America/New_York',  $datetime->getTimezone()->getName());

        $this->assertEquals('2014', $datetime->format('Y'), 'Year 2014');
        $this->assertEquals('09',   $datetime->format('m'), 'Month Sep - 9');
        $this->assertEquals('21',   $datetime->format('d'), 'Day 21');
        $this->assertEquals('15',   $datetime->format('H'), 'Hour 15');
        $this->assertEquals('53',   $datetime->format('i'), 'Minute 53');
        $this->assertEquals('53',   $datetime->format('s'), 'Second 53');
    }
}
