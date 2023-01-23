<?php

namespace CleanDrop;

use pocketmine\entity\object\ItemEntity;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{

    public $cerdo;
    public int $qtt = 0;

    public function OnEnable():void{
        $this->getLogger()->info("Plugin CleanDrop - Loaded!");
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }


    public function alert(){
        $this->getServer()->broadcastMessage("§l§8[§4S§bW§8]§r§c За 2 минуты пройдет очистка мусора!");
    }

    public function unirse(PlayerJoinEvent $event){
        if($this->qtt == 0){
            $this->getScheduler()->scheduleRepeatingTask(new Alert($this),20*480);
            $this->getScheduler()->scheduleRepeatingTask(new CleanAllDrop($this),20*600);
            $this->getLogger()->info("§bLlamando a ClearDrop()");
            Main::ClearDrop(); 
            $this->qtt=1;
        }
   
    
    }

    public function ClearDrop(){

        $this->getLogger()->info("§cProcesando");

        $spawn = $this->getServer()->getWorldManager()->getWorldByName("VoidWorld");
        $shop = $this->getServer()->getWorldManager()->getWorldByName("shop");
        $pvp = $this->getServer()->getWorldManager()->getWorldByName("giantcraft-net-2019-05-01-03-57-26-1556680568");
        $rtp = $this->getServer()->getWorldManager()->getWorldByName("world2");

        foreach ($spawn->getEntities() as $entity){
            if ($entity instanceof ItemEntity) $entity->flagForDespawn();
        }


        foreach ($shop->getEntities() as $entity){
            if ($entity instanceof ItemEntity){
                $entity->flagForDespawn();
            }
        }

        foreach ($pvp->getEntities() as $entity){
            if ($entity instanceof ItemEntity) $entity->flagForDespawn();
        }

        foreach ($rtp->getEntities() as $entity){
            if ($entity instanceof ItemEntity) $entity->flagForDespawn();
        }


        $this->getServer()->broadcastMessage("§l§8[§4S§bW§8]§r§6 Идет запуск сбора §8мусора");
        $this->cerdo = $this->getServer()->getPluginManager()->getPlugin("CozyShopWorld");
        $this->cerdo->GenerateShopItem();

    }


}

?>
