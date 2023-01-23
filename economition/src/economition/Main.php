<?php

namespace economition;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;



class Main extends PluginBase implements Listener{


    public function onEnable() : void{
        $this->getLogger()->info("Plugin Economition - Loaded!");
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }

    public function getPlayerConf($nick){
        $nick = strtolower($nick);
        $conf = new Config($this->getDataFolder()."$nick/$nick.json",Config::JSON);
        return $conf;
    }

    public function getStartPaper(PlayerJoinEvent $evend){

        $nick = $evend->getPlayer()->getName();
        $small = strtolower($nick);
        $p="plugin_data/economition";

        if(!is_dir("$p/$small")){

            $this->getLogger()->info("§l§4 [E$] NOT! exists folder for §b$small §4!");
            $this->getLogger()->info("§l§6 [E$] Creating new folder for §b$small §6...");

            mkdir("$p/$small",0700);
            copy("$p/shablon.json","$p/$small/$small.json");

        }else{
            $this->getLogger()->info("§l§a[E$] EXISTS! §6folder for §b$small §6!");
        }       

        $conf = Main::getPlayerConf($nick);

        if($conf->get("start_paper") == 0){
            $conf->set("qtt_paper",750);
            $conf->set("start_paper",1);
            $conf->save();
            $this->getLogger()->info("§l§a[E$] Игроку §b$nick §6было выдано старт сумму");
        }
        
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        
        $nicka = strtolower($sender->getName());

        switch ($command->getName()) {

            case 'mypaper':

                $Rankconf = new Config($this->getDataFolder()."../RankAndNickDisplay/$nicka/rank.json",Config::JSON);
                if($Rankconf->get("perm_level") <2){
                    $sender->sendMessage("§cУ вас нет прав на использование этой команды!");
                }else{

                    if (count($args) != 0){$sender->sendMessage("§l§8[§4!§8]§r §8Используйте §a/mypaper");
                    }else{
                        $conf = Main::getPlayerConf($sender->getName());
                        $qtt = $conf->get("qtt_paper");
                        $sender->sendMessage("§l§8[§4!§8]§e Ваш счет бумаги равен: §a$qtt");
                    }
                }
                break;
            
            case 'seepaper':

                $Rankconf = new Config($this->getDataFolder()."../RankAndNickDisplay/$nicka/rank.json",Config::JSON);
                if($Rankconf->get("perm_level") <2){
                    $sender->sendMessage("§cУ вас нет прав на использование этой команды!");
                }else{
                    if(count($args) != 1 ){
                        $sender->sendMessage('§l§8[§4!§8]§r §8Используйте §a/seepaper §6"ник игрока"');
                    }else{
                        if(!is_dir("plugin_data/economition/".strtolower($args[0]))){
                            $sender->sendMessage("§l§8[§4!§8]§с Игрок §6$args[0] §cникогда не играл тут");
                        }else{
                            $TConf = Main::getPlayerConf($args[0]);
                            $qtt = $TConf->get("qtt_paper");
                            $sender->sendMessage("§l§8[§4!§8]§e Счет бумаги §c$args[0]§e: §a$qtt");
                        }
                    }
                }
                break;
            
            case 'pay':

                if(count($args) != 2 ){
                    $sender->sendMessage('§l§8[§4!§8]§r §8Используйте §a/pay §6"ник игрока" §a<сумма>');
                }else{
                    if(!is_dir("plugin_data/economition/".strtolower($args[0]))){
                        $sender->sendMessage("§l§8[§4!§8]§с Игрок §6$args[0] §cникогда не играл тут");
                    }elseif(!is_numeric($args[1]) || $args[1] == 0){
                        $sender->sendMessage('§l§8[§4!§8]§r §8Используйте §a/pay §6"ник игрока" §a<сумма>');
                    }else{
                        $SConf = Main::getPlayerConf($sender->getName());
                        $qtt = $SConf->get("qtt_paper");

                        if($qtt < $args[1]){
                            $sender->sendMessage("§l§8[§4!§8]§c У вас нет такого количества бумаги!");
                            $sender->sendMessage("§l§8[§4!§8]§8 На вашем счету всего §a$qtt §8бумаги");
                        }else{

                            $SConf->set("qtt_paper",$qtt - $args[1]);$SConf->save();
                            var_dump($SConf->get("qtt_paper"));
                            $TConf = Main::getPlayerConf($args[0]);
                            $TConf->set("qtt_paper",$TConf->get("qtt_paper") + $args[1]);
                            $TConf->save();
                            $sender->sendMessage("§l§8[§4!§8]§a Вы успешно передали §6$args[0] §c$args[1] §aбумаги");
                            $this->getServer()->getPlayerByPrefix($args[0])->sendMessage("§l§8[§4!§8]§a Игрок §b{$sender->getName()} §aпередал вам §c$args[1] §aбумаги");
                        } 
                    }
                }

                break;

            case 'freepaper':

                $Rankconf = new Config($this->getDataFolder()."../RankAndNickDisplay/$nicka/rank.json",Config::JSON);
                if($Rankconf->get("perm_level") <3){
                    $sender->sendMessage("§cУ вас нет прав на использование этой команды!");
                }else{
                    if(count($args) != 1 || !is_numeric($args[0]) || $args[0] == 0){
                        $sender->sendMessage('§l§8[§4!§8]§r §8Используйте §a/freepaper §e<сумма>');
                    }else{

                        if($args[0] > 1200 && $sender->getName() != "ZVEZDA2016"){
                            $sender->sendMessage('§l§8[§4!§8]§r§f Ваш лимит составляет §c1200 §fбумаги');
                            $sender->sendMessage("§eКупите ранг по выше на §4seriksworld.tk");$sender->sendMessage("§aчтобы выдавать себе §6больше §aбумаги!");
                        }else{
                            $conf = Main::getPlayerConf($sender->getName());
                            $conf->set("qtt_paper",$conf->get("qtt_paper")+$args[0]);
                            $conf->save();
                            $sender->sendMessage("§l§8[§4!§8]§a Вы успешно выдали себе §c$args[0] §aбумаги");
                        }
                    }
                }

                break;


            
            default:
                # code...
                break;
        }


        return true;
    }


}



?>