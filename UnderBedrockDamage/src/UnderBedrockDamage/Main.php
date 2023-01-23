<?php

    namespace UnderBedrockDamage;

    use pocketmine\event\Listener;
    use pocketmine\event\entity\EntityDamageEvent;
    use pocketmine\event\player\PlayerMoveEvent;
    use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
    use pocketmine\world\Position;
    use pocketmine\Server;
    

    class Main extends PluginBase implements Listener{

        public function onEnable() : void{

            $this->getLogger()->info("Plugin UnderBedrockDamage - Loaded!");
            $this->getServer()->getPluginManager()->registerEvents($this,$this);

        }

        public function OnFallUnderBedrock(EntityDamageEvent $event) : void{
       
            $player = $event->getEntity();
            $currentWorld = $event->getEntity()->getPosition()->getWorld();
            $worldName = $currentWorld->getFolderName();

            if ($event->getCause() === EntityDamageEvent::CAUSE_VOID){

                $event->cancel();

                switch ($worldName) {
                    case 't_renacer': // this is the name of my spawn folder
                        $player->teleport(new Position((float) 227, (float) 131, (float) 231, $currentWorld ));
                        break;
                    case 'giantcraft-net-2019-05-01-03-57-26-1556680568': // pvp arena name folder 
                        $player->teleport(new Position((float) -106, (float) 14, (float) 117, $currentWorld ));
                        break;
                    case 'world2': 
                        if($player instanceof Player){
                            $spawn = Server::getInstance()->getWorldManager()->getDefaultWorld(); 
                            $player->teleport(new Position((float) 265, (float) 72, (float) 259, $spawn ));
                            $player->sendMessage("§4[!]§8Ты туда не ходи, ти сюда ходи. Бедрок в бошку ударит, саааавсем мертвой будеш ;(");
                        }
                }
            }
        }


        public function OnRise(PlayerMoveEvent $event){
            if($event->getPlayer()->getWorld()->getFolderName() === "giantcraft-net-2019-05-01-03-57-26-1556680568" ){
                if($event->getPlayer()->getPosition()->getY() > 37){
                    $vector = new Vector3(0,-4,0);
                    $event->getPlayer()->setMotion($vector);
                }
            }
        }
    }
?>