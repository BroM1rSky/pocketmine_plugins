<?php

    namespace CustomItemName;

    use pocketmine\event\player\PlayerInteractEvent;
    use pocketmine\plugin\PluginBase;
    use pocketmine\event\Listener;
    use pocketmine\item\Item;
    use pocketmine\item\VanillaItems;


    class Main extends PluginBase implements Listener{

        public function onEnable() : void{

            $this->getServer()->getPluginManager()->registerEvents($this,$this);
            $this->getLogger()->info("Plugin CustomItemName - Loaded!");

        }

        public function onTap(PlayerInteractEvent $event){

            $player = $event->getPlayer();
            $block = $event->getBlock();
            $item = $event->getItem();
            $nick = $player->getName();

            if($block->getId()==68){

                $x = $event->getBlock()->getPosition()->getX();
                $y = $event->getBlock()->getPosition()->getY();
                $z = $event->getBlock()->getPosition()->getZ();
              
                if($x == -45 && $y == 67 && $z == -14){

                    if($item != null){
                        $item->setCustomName("§l§o§6$nick §4item");
                        $player->getInventory()->setItemInHand($item);

                        
                    }
                }
            }
        }

    }



?>