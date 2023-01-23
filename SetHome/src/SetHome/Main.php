<?php

namespace SetHome;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\world\Position;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\Server;

class Main extends PluginBase{

    public $home_config;

    public function OnEnable() : void{
        $this->getLogger()->info("Plugin SetHome - Loaded!");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        
        if($sender instanceof Player){
        
            $Pnick = strtolower($sender->getName());
            $conf = new Config($this->getDataFolder()."../LoginPasswordPrototype/$Pnick/$Pnick.json",Config::JSON);
            if($conf->get("Lock") == 0){
            
                if($command == "sethome" && count($args) == 1 ){

                    if(!ctype_space($args[0])){

                        $playerNick = strtolower($sender->getName());
                        $this->home_config = new Config($this->getDataFolder()."$playerNick/home_list.json",Config::JSON);

                        $pos = $sender->getPosition();
                        $x = $pos->getX();
                        $y = $pos->getY()+1;
                        $z = $pos->getZ();
                        $w = $pos->getWorld()->getFolderName();

                        $array_pos = array($x,$y,$z,$w);

                        if($this->home_config->exists("home_list")){
                            $array_home = $this->home_config->get("home_list");
                            if(in_array($args[0],$array_home)){
                                $sender->sendMessage("§l§7[§4!§7]§r§8 Уже существует точка §c$args[0]");
                            }elseif(count($array_home) > 4){
                                $sender->sendMessage("§l§7[§4!§7]§r§8 Вы достигли §4максимального §8количества точек §asethome§8!");$sender->sendMessage("§aСтаньте §eСериком §aна §4seriksworld.tk");$sender->sendMessage("§aчтобы создавать куда больше точек!");
                            }else{
                                array_push($array_home,$args[0]);
                                $this->home_config->set("home_list",$array_home);
                                $this->home_config->set("$args[0]",$array_pos);
                                $this->home_config->save();
                                $sender->sendMessage("§l§7[§4!§7]§r§8 Вы успешно установили точку дома §b$args[0]");

                            }    
                        }else{
                            $array_home = array();
                            array_push($array_home,$args[0]);
                            $this->home_config->set("home_list",$array_home);
                            $this->home_config->set("$args[0]",$array_pos);
                            $this->home_config->save();
                            $sender->sendMessage("§l§7[§4!§7]§r§8 Вы успешно установили точку дома §b$args[0]");

                        }
                    }else{
                        $sender->sendMessage("§l§7[§4!§7]§r§8 Введите правильно точку §ahome§8!");
                    }
                
                }
                
                if($command == "delhome" && count($args) == 1){   
                    
                    $playerNick = strtolower($sender->getName());
                    $this->home_config = new Config($this->getDataFolder()."$playerNick/home_list.json",Config::JSON);

                    if(!($this->home_config->exists("home_list"))){
                        $sender->sendMessage("§l§7[§4!§7]§r§8 У вас пока нет ни одной точки дома");
                    }else{
                        $array_home = $this->home_config->get("home_list");
                        if(in_array($args[0],$array_home)){
                            $dK = array_search($args[0],$array_home);
                            unset($array_home[$dK]);sort($array_home);
                            $this->home_config->set("home_list",$array_home);
                            $this->home_config->remove("$args[0]");
                            $this->home_config->save();

                            $sender->sendMessage("§l§7[§4!§7]§r§8 Точка дома §a$args[0]§8 успешно §cудалена ");
                        }else{
                            $sender->sendMessage("§l§7[§4!§7]§r§8 У вас нет точки §ahome§8 с именем §c$args[0]");
                        }

                    }
                }
                
                if($command == "homelist" && count($args) == 0){
                    $playerNick = strtolower($sender->getName());
                    $this->home_config = new Config($this->getDataFolder()."$playerNick/home_list.json",Config::JSON);

                    if(!($this->home_config->exists("home_list"))){
                        $sender->sendMessage("§l§7[§4!§7]§r§8 У вас пока нет ни одной точки дома");
                    }else{
                        $array_home = $this->home_config->get("home_list");
                        if(count($array_home) < 1){
                            $sender->sendMessage("§l§7[§4!§7]§r§8 У вас пока нет ни одной точки дома");
                        }else{
                            $list = "";

                            for ($i=0; $i<count($array_home); $i++) { 
                                $list = $list."$array_home[$i], ";
                            }
                            $list = substr($list,0,-2);

                            $sender->sendMessage("§l§7[§4!§7]§r§8 Список ваших точек дома:§e $list");
                        }
                        
                    }
                }
                
                if($command == "home" && count($args) == 1){
                    $playerNick = strtolower($sender->getName());
                    $this->home_config = new Config($this->getDataFolder()."$playerNick/home_list.json",Config::JSON);
                    
                    if($this->home_config->exists("$args[0]")){
                        $array_pos = $this->home_config->get("$args[0]");
                        $world = Server::getInstance()->getWorldManager()->getWorldByName($array_pos[3]);
                        $sender->teleport(new Position((float) $array_pos[0], (float) $array_pos[1], (float) $array_pos[2], $world ));
                        $sender->sendMessage("§l§7[§4!§7]§r§8 Перемещение на точку дома §b$args[0]§8");
                    }else{
                        $sender->sendMessage("§l§7[§4!§7]§r§8 Точка дома §a$args[0] §8не существует!");

                    }
                }

                if(($command == "home" || $command == "sethome" || $command == "delhome") && count($args) > 1){
                    $sender->sendMessage("§l§7[§4!§7]§r§8 Используйте §a/homehelp");
                }elseif(($command == "home" || $command == "sethome" || $command == "delhome") && count($args) < 1){
                    $sender->sendMessage("§l§7[§4!§7]§r§8 Используйте §a/homehelp");
                }elseif($command == "homelist" && count($args)!=0){
                    $sender->sendMessage("§l§7[§4!§7]§r§8 Используйте §a/homehelp");
                }

                return true;
            }
        }
        return true;
    }


}

?>