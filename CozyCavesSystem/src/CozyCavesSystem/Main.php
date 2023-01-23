<?php

namespace CozyCavesSystem;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\VanillaItems;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

    private int $contador = 0;
    private int $contadorW = 0;

    public function OnEnable() : void{
        $this->getLogger()->info("§fPlugin §6aCozyCavesSystem §f- §aLoaded Succesfully!");
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }

    public function getPlayerConf($nick){
        $nick = strtolower($nick);
        $conf = new Config($this->getDataFolder()."../economition/$nick/$nick.json",Config::JSON);
        return $conf;
    }

    public function OnBreakBlock(BlockBreakEvent $event){
        
        $block = $event->getBlock();
        $player = $event->getPlayer();
        $hight = round($block->getPosition()->getY());
        $Id = $block->getIdInfo()->getBlockId();

        if ($player->getGamemode()->getEnglishName() === "Survival"){
            
            switch ($Id) {
                case '14':
                    $item = $player->getInventory()->getItemInHand()->getId();
                    if($item === VanillaItems::IRON_PICKAXE()->getId() || $item === VanillaItems::DIAMOND_PICKAXE()->getId()){
                        $item = VanillaItems::GOLD_INGOT();
                        $event->setDrops([$item]);
                        $conf = Main::getPlayerConf($player->getName());
                        $conf->set("qtt_paper",$conf->get("qtt_paper")+50);
                        $conf->save();
                        $player->sendTip("§a+50$");
                    }
                    break;
                case '15':
                    $item = $player->getInventory()->getItemInHand()->getId();
                    if($item === VanillaItems::STONE_PICKAXE()->getId() || $item === VanillaItems::IRON_PICKAXE()->getId() || $item === VanillaItems::DIAMOND_PICKAXE()->getId()){
                        $item = VanillaItems::IRON_INGOT();
                        $event->setDrops([$item]);
                        $conf = Main::getPlayerConf($player->getName());
                        $conf->set("qtt_paper",$conf->get("qtt_paper")+30);
                        $conf->save();
                        $player->sendTip("§a+30$");
                    }
                    break;
                case '16':
                    $item = $player->getInventory()->getItemInHand()->getId();
                    if($item === VanillaItems::STONE_PICKAXE()->getId() || $item === VanillaItems::IRON_PICKAXE()->getId() || $item === VanillaItems::DIAMOND_PICKAXE()->getId() || $item === VanillaItems::WOODEN_PICKAXE()->getId() || $item === VanillaItems::GOLDEN_PICKAXE()->getId()){
                        $item = VanillaItems::COAL();
                        $event->setDrops([$item]);
                        $conf = Main::getPlayerConf($player->getName());
                        $conf->set("qtt_paper",$conf->get("qtt_paper")+15);$conf->save();
                        $player->sendTip("§a+15$");
                    }
                    break;
                case '56':
                    $item = $player->getInventory()->getItemInHand()->getId();
                    if($item === VanillaItems::IRON_PICKAXE()->getId() || $item === VanillaItems::DIAMOND_PICKAXE()->getId()){
                        $conf = Main::getPlayerConf($player->getName());
                        $conf->set("qtt_paper",$conf->get("qtt_paper")+150);$conf->save();
                        $player->sendTip("§a+150$");
                    }
                    break;
                case '17':
                    $item = $player->getInventory()->getItemInHand()->getId();
                    if($item === VanillaItems::WOODEN_AXE()->getId() || $item === VanillaItems::STONE_AXE()->getId() || $item === VanillaItems::IRON_AXE()->getId() || $item === VanillaItems::DIAMOND_AXE()->getId()){
                        if($this->contadorW == 10){
                            $conf = Main::getPlayerConf($player->getName());
                            $conf->set("qtt_paper",$conf->get("qtt_paper")+50);$conf->save();
                            $player->sendTip("§a+50$");
                            $this->contadorW = 0;
                        }else{
                            $this->contadorW++;
                        }
                    }
                    break;
                
            }

        

            if($Id == 1){
                if($block->getIdInfo()->getVariant() != 2 && $block->getIdInfo()->getVariant() != 4 && $block->getIdInfo()->getVariant() != 6){

                    if($this->contador !=10){
                        $this->contador++;
                    }else{
                        $conf = Main::getPlayerConf($player->getName());
                        $conf->set("qtt_paper",$conf->get("qtt_paper")+25);$conf->save();
                        $player->sendTip("§a+25$");
                        $this->contador = 0;
                    }

                    $item = null;
                    $chance = mt_rand(1,100);

                    if($chance <= 4 && $hight > 2 && $hight < 20){
                        if($player->getInventory()->getItemInHand()->getId() === VanillaItems::DIAMOND_PICKAXE()->getId() || $player->getInventory()->getItemInHand()->getId() === VanillaItems::IRON_PICKAXE()->getId()){
                            $item=VanillaItems::DIAMOND();
                        }   
                    }
                    elseif($chance <=8){
                        if($player->getInventory()->getItemInHand()->getId() === VanillaItems::DIAMOND_PICKAXE()->getId() || $player->getInventory()->getItemInHand()->getId() === VanillaItems::IRON_PICKAXE()->getId()){
                            $item=VanillaItems::GOLD_INGOT()->setCount(2);
                        }
                    }
                    elseif($chance <=20){
                        
                        if($player->getInventory()->getItemInHand()->getId() === VanillaItems::STONE_PICKAXE()->getId() || $player->getInventory()->getItemInHand()->getId() === VanillaItems::DIAMOND_PICKAXE()->getId() || $player->getInventory()->getItemInHand()->getId() === VanillaItems::IRON_PICKAXE()->getId()){
                            $item=VanillaItems::IRON_INGOT()->setCount(2);
                        }
                    }
                    elseif($chance <= 25){
                        $item=VanillaItems::COAL()->setCount(1);
                    }
                    else{
                        $perro = "";
                    }

                    if($item !=null){
                        $event->setDrops([$item]);
                    }               
                }
            }
        }
    }
}

?>