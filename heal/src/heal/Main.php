<?php

namespace heal;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\object\ItemEntity;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase{

    public function onEnable() : void{
        $this->getLogger()->info("Plugin Heal - Loaded!");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        
        if($sender instanceof Player){

            $Pnick = strtolower($sender->getName());
            $conf = new Config($this->getDataFolder()."../LoginPasswordPrototype/$Pnick/$Pnick.json",Config::JSON);
            if($conf->get("Lock") == 0){

                if($command == "heal" && count($args) == 0){
                    $sender->setHealth(20);
                    $sender->getHungerManager()->addFood(20);
                }elseif($command == "heal" && count($args)!=0){
                    $sender->sendMessage("§l§7[§4!§7]§r§8 Используйте §a/heal");
                }
                return true;
            }
        }
        return true;
    }
}

?>