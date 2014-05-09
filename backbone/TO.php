<?php
require_once 'PHPUnit/Autoload.php';

trait Observable
{
    protected $subscribers = array();

    public function onEventSendTo($event, $target, $message)
    {
        $action = function() use($message, $target){$target->$message();};
        $this->subscribers[$event][] = $action;
    }

    public function trigger($event)
    {
        foreach ($this->subscribers[$event] as $function)
        {
            $function();
        }
    }
}

class TestTarget
{
    use Observable;

    public function qq()
    {
        var_dump('qqq');
    }

    public function SomeEvent()
    {
        var_dump('some event');
    }

}

class TestObserver
{
    public function getNotification()
    {
        var_dump('get notification');
    }
}

class ObservableTest extends PHPUnit_Framework_TestCase
{
    public function testTrigger()
    {
        // Prepare the testing scenario
        $target = new TestTarget();
        $observer = $this->getMock('TestObserver');

        //Configure the mock according to the expected behaviour
        $observer
            ->expects($this->once())
            ->method('getNotification');
        var_dump($observer );

        //Configure the callback
        $target->onEventSendTo('SomeEvent', $observer, 'getNotification');

        //Trigger the behavior
        $target->trigger('SomeEvent');
    }
}

$q = new ObservableTest();
$q->testTrigger();
