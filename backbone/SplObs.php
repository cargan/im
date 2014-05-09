<?php

class Subject implements SplSubject{
    protected $observers = array();

    public function attach(SplObserver $observer) {
        $this->observers[] = $observer;
    }

    public function detach(SplObserver $observer) {
        $key = array_search($observer, $this->observers);
        if ($key) {
            unset($this->observers[$key]);
        }
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}

class MyObserver implements SplObserver {
    public function update(SplSubject $subject) {
        echo "I was updated by " . get_class($subject);
    }
}

// $subject = new Subject;
// $subject->attach(new MyObserver);
// $subject->notify(); // prints "I was updated by Subject"
//
class Machine implements SplSubject{
    protected $observers = array();

    public function attach(SplObserver $observer) {
        $this->observers[] = $observer;
    }

    public function detach(SplObserver $observer) {
        $key = array_search($observer, $this->observers);
        if ($key) {
            unset($this->observers[$key]);
        }
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function getLocation() {
        return 4;
    }

    public function move()
    {
        $this->notify();
    }
}

class Radar implements SplObserver {
    public function update(SplSubject $subject) {
        echo "I was updated by " . $subject->getLocation();
    }
}

$subject = new Machine;
$subject->attach(new Radar);
$subject->move(); // prints "I was updated by Subject"
