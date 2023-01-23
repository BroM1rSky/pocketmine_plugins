<?php

namespace ExplodeArrow;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\world\Explosion;
use pocketmine\entity\projectile\Arrow as Flecha;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;


class Main extends PluginBase implements Listener{

    public string $shooter = "";

    public function OnEnable() : void{
        $this->getLogger()->info("Plugin ExplodeArrow - Loaded Succesfully!");
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }

    public function onJoin (PlayerJoinEvent $event){
        $nick = $event->getPlayer()->getName();
        $small = strtolower($nick);
        $p="plugin_data/ExplodeArrow";
        if(!is_dir("$p/$small")){
            mkdir("$p/$small",0700);
            copy("$p/shablon.json","$p/$small/$small.json");
        } 
    }

    public function getPlayerConf($nick){
        $nick = strtolower($nick);
        $conf = new Config($this->getDataFolder()."$nick/$nick.json",Config::JSON);
        return $conf;
    }



    public function onProjectileImpact(ProjectileHitEvent $event){
        
        $projectile = $event->getEntity();

        if($projectile instanceof Flecha && $projectile->getNameTag() === "§l§cБАБАХ§6 стрела"){

            $location = $projectile->getLocation();
            $explosion = new Explosion($location,3);
            $explosion->explodeB();

        }
    }

    public function babahBow(EntityShootBowEvent $event){

        if($event->getProjectile() instanceof Flecha){

            if($event->getBow()->getCustomName() == "§l§cБАБАХ§6 лук"){
                $event->getProjectile()->setNameTag("§l§cБАБАХ§6 стрела");
                $nick = $event->getEntity()->getName();
                $conf = Main::getPlayerConf($nick);
                $conf->set("shoot",1);$conf->save();
                $this->shooter=$nick;
                
            }
        }
    }

    public function OnExplosion(EntityDamageEvent $event){
    
        $player = $event->getEntity();
        $cause = $player->getLastDamageCause();
    
        if($cause !== null){
    
            if($cause->getCause() === EntityDamageEvent::CAUSE_BLOCK_EXPLOSION && $player instanceof Player){
            
                $this->getLogger()->info("§6Inside");

                if($this->shooter != ""){
                    $conf = Main::getPlayerConf($this->shooter);

                    if($conf->get("shoot")==1){
                        $Vector = new Vector3(mt_rand(40,140) / 100,mt_rand(50,100) / 100,mt_rand(-100,65) / 100);
                        var_dump($Vector);
                        $event->getEntity()->setMotion($Vector);
                        //$this->getLogger()->info("§aPlayer movement changed by explosion damage");
                        $this->getLogger()->info("§6Inside §aGood!");
                        $conf->set("shoot",0);$conf->save();
                        $this->shooter = "";

                    }else{
                        $this->getLogger()->info("§6Inside §4BAD SUPER!");
                    }
                }else{
                    $this->getLogger()->info("§6Inside §4BAD!");
                }
            }
        }
    }

}


?>