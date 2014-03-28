<?php

class Grid {
    protected $coordinates = [];
    public function __construct($x, $y) {
        for($i=0;$i<$y;$i++) {
            $this->__coordinates[$i] = range(0, ($x-1));
        }
    }
}


$Grid = new Grid(10, 10);
print_r($Grid);
