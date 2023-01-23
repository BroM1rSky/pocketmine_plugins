<?php
    namespace ClearChat;
    
    use pocketmine\plugin\PluginBase;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\event\Listener;
    use pocketmine\utils\Config;

    class Main extends PluginBase implements Listener{
        
        public  string $nickname = "";

        public function onEnable() : void{
            $this->getLogger()->info("Plugin ClearChat - Loaded!");
        }

        public function onCommand(CommandSender $sender, Command $command, string $label, array $args ): bool{

            $Pnick = strtolower($sender->getName());
            $conf = new Config($this->getDataFolder()."../LoginPasswordPrototype/$Pnick/$Pnick.json",Config::JSON);
    
            if($conf->get("Lock") == 0){

                $Rankconf = new Config($this->getDataFolder()."../RankAndNickDisplay/$Pnick/rank.json",Config::JSON);
                if($Rankconf->get("perm_level") <4){
                    $sender->sendMessage("§cУ вас нет прав на использование этой команды!");
                }else{
                    $qttArgs = count($args);

                    if($command == "cc" && $qttArgs == 0){

                        $nickname = $sender->getName(); 

                        $this->getServer()->broadcastMessage("                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              ");                    

                        $this->getServer()->broadcastMessage("§4[!]§2 $nickname - §7Oчистил чaт");

                        
                        return true;
                        
                    }else if ($command == "cc" && $qttArgs !=0){
                        return false;
                    }
                    return true;
                }
                
                
            }
            return true;

        }

    }