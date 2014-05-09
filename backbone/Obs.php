<?php

abstract class AbstractObserver
{
    abstract function update(AbstractSubject $subject_in);
}

abstract class AbstractSubject
{
    abstract function attach(AbstractObserver $observer_in);
    abstract function detach(AbstractObserver $observer_in);
    abstract function notify();
}

function writeln($line_in) {
    echo $line_in."\n";
}

class PatternObserver extends AbstractObserver
{
    // public function __construct() {
    // }
    public function update(AbstractSubject $subject) {
      writeln('*IN PATTERN OBSERVER - NEW PATTERN GOSSIP ALERT*');
      writeln(' new favorite patterns: '.$subject->getFavorites());
      writeln('*IN PATTERN OBSERVER - PATTERN GOSSIP ALERT OVER*');
    }
}

class PatternSubject extends AbstractSubject
{
    private $favoritePatterns = NULL;
    private $observers = array();

    function __construct()
    {
    }

    function attach(AbstractObserver $observer_in)
    {
      //could also use array_push($this->observers, $observer_in);
      $this->observers[] = $observer_in;
    }

    function detach(AbstractObserver $observer_in)
    {
      //$key = array_search($observer_in, $this->observers);
      foreach($this->observers as $okey => $oval) {
        if ($oval == $observer_in) {
          unset($this->observers[$okey]);
        }
      }
    }

    function notify()
    {
      foreach($this->observers as $obs) {
        $obs->update($this);
      }
    }

    function updateFavorites($newFavorites)
    {
      $this->favorites = $newFavorites;
      $this->notify();
    }

    function getFavorites()
    {
      return $this->favorites;
    }
}

  // writeln('BEGIN TESTING OBSERVER PATTERN');
  // writeln('');

  // $patternGossiper = new PatternSubject();
  // $patternGossipFan = new PatternObserver();
  // $patternGossiper->attach($patternGossipFan);
  // $patternGossiper->updateFavorites('abstract factory, decorator, visitor');
  // $patternGossiper->updateFavorites('abstract factory, observer, decorator');
  // $patternGossiper->detach($patternGossipFan);
  // $patternGossiper->updateFavorites('abstract factory, observer, paisley');

  // writeln('END TESTING OBSERVER PATTERN');
//
// abstract class AbstractObserver
// {
//     abstract function update(AbstractSubject $subject_in);
// }
//
// abstract class AbstractSubject
// {
//     abstract function attach(AbstractObserver $observer_in);
//     abstract function detach(AbstractObserver $observer_in);
//     abstract function notify();
// }

class Terrain extends AbstractObserver
{
    public function update(AbstractSubject $subject)
    {
        var_dump('new Location: '. $subject->getLocation());
    }
}

class M extends AbstractSubject
{
    private $__location;

    public function __construct($location)
    {
        $this->__location = $location;
    }

    public function attach(AbstractObserver $observer_in)
    {
      $this->observers[] = $observer_in;
    }

    public function detach(AbstractObserver $observer_in)
    {
      //$key = array_search($observer_in, $this->observers);
      foreach($this->observers as $okey => $oval) {
        if ($oval == $observer_in) {
          unset($this->observers[$okey]);
        }
      }
    }

    public function notify()
    {
      foreach($this->observers as $obs) {
        $obs->update($this);
      }
    }

    public function getLocation()
    {
        return $this->__location;
    }

    private function __setLocation($location)
    {
        $this->__location = $location;
    }

    public function updateLocation($location)
    {
        $this->__setLocation($location);
        $this->notify();
    }
}

$Terrain = new Terrain();
$M1 = new M('a');
$M2 = new M('b');
$M1->attach($Terrain);
// $Terrain->attach($M2);

$M1->updateLocation('c');
$M1->updateLocation('d');
$M1->updateLocation('e');



