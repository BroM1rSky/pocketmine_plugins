<?php

namespace CleanDrop;

use pocketmine\scheduler\Task;

class CleanAllDrop extends Task{

    private $main;

    public function __construct($main){
        $this->main = $main;
    }

    public function onRun(): void{
        $this->main->ClearDrop();
    }

}


?>