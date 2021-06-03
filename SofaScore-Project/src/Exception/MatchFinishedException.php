<?php

namespace App\Exception;

class MatchFinishedException extends \Exception {
    public function __construct(){
        parent::__construct("The match has finished. You can't switch to next period.");
    }
}

?>