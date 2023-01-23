<?php

namespace SnowFireBall;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\projectile\Snowball;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\item\VanillaItems;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\player\Player;
use pocketmine\utils\Config;


class Main extends PluginBase implements Listener{

    //public string $damager = "";

    public function OnEnable() : void{
        $this->getLogger()->info("Plugin SnowFireBall - Loaded Succesfully!");
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }


    public function onFire(ProjectileLaunchEvent $event){
        $entity = $event->getEntity();

        if ($entity instanceof Snowball){

            $player = $event->getEntity()->getOwningEntity()->getNameTag();
            
            $enemy = $player;$k1 = strpos($enemy,"[");$enemy = substr($enemy,$k1+3);$k2 = strpos($enemy,"]");$enemy= substr($enemy,1,$k2-4);
            $enemyInvent = $this->getServer()->getPlayerByPrefix($enemy)->getInventory();
            if($enemyInvent->getItemInHand()->getCustomName("§l§cГ§6о§cр§6я§cч§6и§cй §bcнежoк§f")){
    
                $entity->setNameTag("§l§cГ§6о§cр§6я§cч§6и§cй §bcнежoк§f");
            }
            
        }
    }


    public function onDamage(EntityDamageEvent $event) {
        if($event instanceof EntityDamageByChildEntityEvent){
            
            if($event->getChild() instanceof Snowball){
            
                if($event->getChild()->getNameTag() === "§l§cГ§6о§cр§6я§cч§6и§cй §bcнежoк§f"){
                    $entity = $event->getEntity();
                    $entity->setFireTicks(20*10);
                }
            }
        }
    }


    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
       
        $Pnick = strtolower($sender->getName());

        $Rankconf = new Config($this->getDataFolder()."../RankAndNickDisplay/$Pnick/rank.json",Config::JSON);

        if($Rankconf->get("perm_level") <6){

            $sender->sendMessage("§cУ вас нет прав на использование этой команды!");

        }else{
            if($command->getName() == "snowball" && count($args)==0){

                $item = VanillaItems::SNOWBALL()->setCount(16)->addEnchantment(new EnchantmentInstance(VanillaEnchantments::INFINITY(),1));
                $item->setCustomName("§l§cГ§6о§cр§6я§cч§6и§cй §bcнежoк§f");
                $sender->getInventory()->addItem($item);

            }elseif($command->getName() == "snowball" && count($args) !=0){

                $sender->sendMessage("§l§8[§4!§8]§r §8Используйте §a/snowball");
            }
        }

        return true;
    }


}


    

?>