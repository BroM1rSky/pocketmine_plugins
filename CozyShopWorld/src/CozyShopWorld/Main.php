<?php


namespace CozyShopWorld;

use pocketmine\block\BaseSign;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\entity\ItemDespawnEvent;
use pocketmine\math\Vector3;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\object\ItemEntity;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\VanillaItems;
use pocketmine\utils\Config;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\utils\SignText;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;

class Main extends PluginBase implements Listener{
    
    public $var;
    public int $qtt_global = 0;
    public array $shop_array = array();
    public array $s_pos = array();

    public function getPlayerConf($nick){
        $nick = strtolower($nick);
        $conf = new Config($this->getDataFolder()."../economition/$nick/$nick.json",Config::JSON);
        return $conf;
    }

    public function OnEnable() : void{
        $this->getLogger()->info("Plugin CozyShopWorld - Loaded Succesfully!");
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        $this->var = $this->getServer()->getPluginManager()->getPlugin("ShopHelper");

        $this->shop_array = [
            "guns" => [
                0 => ["count"=>1,"item_qtt" => 1],
                1 => ["price" => 150,"item" => VanillaItems::DIAMOND_SWORD()],
                2 => ["price" => 50,"item" => VanillaItems::IRON_SWORD()],
                3 => ["price" => 25,"item" => VanillaItems::STONE_SWORD()],
                4 => ["price" => 500,"item" => VanillaItems::DIAMOND_PICKAXE()],
                5 => ["price" => 150,"item" => VanillaItems::IRON_PICKAXE()],
                6 => ["price" => 100,"item" => VanillaItems::STONE_PICKAXE()],
                7 => ["price" => 350,"item" => VanillaItems::DIAMOND_AXE()],
                8 => ["price" => 150,"item" => VanillaItems::IRON_AXE()],
                9 => ["price" => 100,"item" => VanillaItems::STONE_AXE()],
                10 => ["price" => 100,"item" =>VanillaItems::DIAMOND_SHOVEL()],
                11 => ["price" => 75,"item" =>VanillaItems::IRON_SHOVEL()],
                12 => ["price" => 50,"item" =>VanillaItems::IRON_HOE()],
                13 => ["price" => 300,"item" =>VanillaItems::BOW()],
                14 => ["price" => 5,"item" =>VanillaItems::ARROW()],
            ],
            "food" => [
                0 => ["count"=>1,"item_qtt" => 1],
                1 => ["price" => 10,"item" => VanillaItems::COOKIE()],
                2 => ["price" => 15,"item" => VanillaItems::BREAD()],
                3 => ["price" => 20,"item" => VanillaItems::COOKED_PORKCHOP()],
                4 => ["price" => 20,"item" => VanillaItems::STEAK()],
                5 => ["price" => 5,"item" => VanillaItems::DRIED_KELP()],
                6 => ["price" => 15,"item" => VanillaItems::COOKED_SALMON()],
                7 => ["price" => 12,"item" => VanillaItems::COOKED_FISH()],
                8 => ["price" => 15,"item" => VanillaItems::COOKED_RABBIT()],
                9 => ["price" => 150,"item" =>VanillaBlocks::CAKE()->asItem()],
                10 => ["price" => 325,"item" => VanillaItems::GOLDEN_APPLE()],
                11 => ["price" => 4200,"item" =>VanillaItems::ENCHANTED_GOLDEN_APPLE()],
            ],
            "armor" => [
                0 => ["count"=>1,"item_qtt" => 1],
                1 => ["price" => 450,"item" => VanillaItems::DIAMOND_HELMET()],
                2 => ["price" => 850,"item" => VanillaItems::DIAMOND_CHESTPLATE()],
                3 => ["price" => 750,"item" => VanillaItems::DIAMOND_LEGGINGS()],
                4 => ["price" => 400,"item" => VanillaItems::DIAMOND_BOOTS()],
                5 => ["price" => 250,"item" => VanillaItems::IRON_HELMET()],
                6 => ["price" => 550,"item" => VanillaItems::IRON_CHESTPLATE()],
                7 => ["price" => 350,"item" => VanillaItems::IRON_LEGGINGS()],
                8 => ["price" => 150,"item" => VanillaItems::IRON_BOOTS()],
                9 => ["price" => 100,"item" => VanillaItems::CHAINMAIL_HELMET()],
                10 => ["price" => 250,"item" =>VanillaItems::CHAINMAIL_CHESTPLATE()],
                11 => ["price" => 200,"item" => VanillaItems::CHAINMAIL_LEGGINGS()],
                12 => ["price" => 75,"item" => VanillaItems::CHAINMAIL_BOOTS()],
                13=> ["price" => 85,"item" => VanillaItems::GOLDEN_HELMET()],
                14 => ["price" => 175,"item" =>VanillaItems::GOLDEN_CHESTPLATE()],
                15 => ["price" => 150,"item" => VanillaItems::GOLDEN_LEGGINGS()],
                16=> ["price" => 50,"item" => VanillaItems::GOLDEN_BOOTS()],
                17=> ["price" => 50,"item" => VanillaItems::LEATHER_CAP()],
                18 => ["price" => 150,"item" =>VanillaItems::LEATHER_TUNIC()],
                19=> ["price" => 100,"item" => VanillaItems::LEATHER_PANTS()],
                20 => ["price" => 35,"item" =>VanillaItems::LEATHER_BOOTS()],
            ],
            "res" => [
                0 => ["count"=>1,"item_qtt" => 1],
                1 => ["price" => 350,"item" => VanillaItems::DIAMOND()],
                2 => ["price" => 50,"item" => VanillaItems::IRON_INGOT()],
                3 => ["price" => 75,"item" => VanillaItems::GOLD_INGOT()],
                4 => ["price" => 10,"item" => VanillaItems::REDSTONE_DUST()],
                5 => ["price" => 10,"item" => VanillaItems::COAL()],
                6 => ["price" => 1200,"item" => VanillaItems::ENDER_PEARL()],
                7 => ["price" => 4500,"item" => VanillaBlocks::NETHER_WART()->asItem()],
                8 => ["price" => 1600,"item" => VanillaItems::BLAZE_ROD()],
                9 => ["price" => 1600,"item" =>VanillaItems::GLISTERING_MELON()],
                10 => ["price" => 1600,"item" =>VanillaItems::GHAST_TEAR()],
                11 => ["price" => 800,"item" => VanillaBlocks::ENDER_CHEST()->asItem()],
                12 => ["price" => 50,"item" => VanillaItems::SNOWBALL()],
                13 => ["price" => 75,"item" => VanillaItems::EGG()]
            ],
            "blocks" => [
                0 => ["count"=>1,"item_qtt" => 1],
                1 => ["price" => 10,"item" => VanillaBlocks::OAK_LOG()->asItem()],
                2 => ["price" => 10,"item" => VanillaBlocks::SPRUCE_LOG()->asItem()],
                3 => ["price" => 10,"item" => VanillaBlocks::ACACIA_LOG()->asItem()],
                4 => ["price" => 10,"item" => VanillaBlocks::DARK_OAK_LOG()->asItem()],
                5 => ["price" => 10,"item" => VanillaBlocks::JUNGLE_LOG()->asItem()],
                6 => ["price" => 25,"item" => VanillaBlocks::GRANITE()->asItem()],
                7 => ["price" => 50,"item" => VanillaBlocks::POLISHED_GRANITE()->asItem()],
                8 => ["price" => 25,"item" => VanillaBlocks::DIORITE()->asItem()],
                9 => ["price" => 50,"item" =>VanillaBlocks::POLISHED_DIORITE()->asItem()],
                10 => ["price" => 5,"item" =>VanillaBlocks::GRASS()->asItem()],
                11 => ["price" => 15,"item" => VanillaBlocks::MYCELIUM()->asItem()],
                12 => ["price" => 15,"item" => VanillaBlocks::PODZOL()->asItem()],
                13 => ["price" => 20,"item" => VanillaBlocks::GLASS()->asItem()],
                14 => ["price" => 45,"item" => VanillaBlocks::BRICKS()->asItem()],
                15 => ["price" => 55,"item" => VanillaBlocks::NETHER_BRICKS()->asItem()],
                16 => ["price" => 4,"item" => VanillaBlocks::STONE()->asItem()],
                17 => ["price" => 2,"item" => VanillaBlocks::COBBLESTONE()->asItem()],
                18 => ["price" => 175,"item" => VanillaBlocks::BEACON()->asItem()],
                19 => ["price" => 50,"item" => VanillaBlocks::GLOWSTONE()->asItem()],
                20 => ["price" => 75,"item" => VanillaBlocks::SEA_LANTERN()->asItem()],
                21 => ["price" => 75,"item" => VanillaBlocks::LIT_PUMPKIN()->asItem()],
                22 => ["price" => 55,"item" =>VanillaBlocks::SMOOTH_QUARTZ()->asItem()],
                23 => ["price" => 125,"item" =>VanillaBlocks::BOOKSHELF()->asItem()],
                24 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::WHITE())->asItem()],
                25 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::BLACK())->asItem()],
                26 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::BLUE())->asItem()],
                27 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::BROWN())->asItem()],
                28 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::CYAN())->asItem()],
                29 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::GRAY())->asItem()],
                30 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::GREEN())->asItem()],
                31 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::LIGHT_BLUE())->asItem()],
                32 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::LIGHT_GRAY())->asItem()],
                33 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::LIME())->asItem()],
                34 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::MAGENTA())->asItem()],
                35 => ["price" => 30,"item" =>VanillaBlocks::WOOL()->setColor(DyeColor::ORANGE())->asItem()],
                36 => ["price" => 30,"item" =>VanillaBlocks::WOOL()->setColor(DyeColor::PINK())->asItem()],
                37 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::PURPLE())->asItem()],
                38 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::RED())->asItem()],
                39 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::YELLOW())->asItem()],
                40 => ["price" => 30,"item" => VanillaBlocks::WOOL()->setColor(DyeColor::BLACK())->asItem()],
                41 => ["price" => 25,"item" => VanillaBlocks::RED_GLAZED_TERRACOTTA()->asItem()],
                42 => ["price" => 25,"item" => VanillaBlocks::BLUE_GLAZED_TERRACOTTA()->asItem()],
                43 => ["price" => 25,"item" => VanillaBlocks::CYAN_GLAZED_TERRACOTTA()->asItem()],
                44 => ["price" => 25,"item" => VanillaBlocks::GRAY_GLAZED_TERRACOTTA()->asItem()],
                45 => ["price" => 25,"item" => VanillaBlocks::LIME_GLAZED_TERRACOTTA()->asItem()],
                46 => ["price" => 25,"item" => VanillaBlocks::PINK_GLAZED_TERRACOTTA()->asItem()],
                47 => ["price" => 25,"item" => VanillaBlocks::BLACK_GLAZED_TERRACOTTA()->asItem()],
                48 => ["price" => 25,"item" =>VanillaBlocks::BROWN_GLAZED_TERRACOTTA()->asItem()],
                49 => ["price" => 25,"item" =>VanillaBlocks::GREEN_GLAZED_TERRACOTTA()->asItem()],
                50 => ["price" => 25,"item" => VanillaBlocks::WHITE_GLAZED_TERRACOTTA()->asItem()],
                51 => ["price" => 25,"item" => VanillaBlocks::ORANGE_GLAZED_TERRACOTTA()->asItem()],
                52 => ["price" => 75,"item" => VanillaBlocks::PURPLE_GLAZED_TERRACOTTA()->asItem()],
                53 => ["price" => 25,"item" => VanillaBlocks::YELLOW_GLAZED_TERRACOTTA()->asItem()],
                54 => ["price" => 25,"item" => VanillaBlocks::MAGENTA_GLAZED_TERRACOTTA()->asItem()],
                55 => ["price" => 25,"item" => VanillaBlocks::LIGHT_BLUE_GLAZED_TERRACOTTA()->asItem()],
                56 => ["price" => 25,"item" => VanillaBlocks::LIGHT_GRAY_GLAZED_TERRACOTTA()->asItem()],
                57 => ["price" => 15,"item" => VanillaItems::RED_DYE()],
                58 => ["price" => 15,"item" => VanillaItems::BLUE_DYE()],
                59 => ["price" => 15,"item" => VanillaItems::CYAN_DYE()],
                60 => ["price" => 15,"item" => VanillaItems::GRAY_DYE()],
                61 => ["price" => 15,"item" =>VanillaItems::LIME_DYE()],
                62 => ["price" => 15,"item" =>VanillaItems::PINK_DYE()],
                63 => ["price" => 15,"item" => VanillaItems::BLACK_DYE()],
                64 => ["price" => 15,"item" => VanillaItems::BROWN_DYE()],
                65 => ["price" => 15,"item" => VanillaItems::GREEN_DYE()],
                66 => ["price" => 15,"item" => VanillaItems::WHITE_DYE()],
                67 => ["price" => 15,"item" => VanillaItems::ORANGE_DYE()],
                68 => ["price" => 15,"item" => VanillaItems::PURPLE_DYE()],
                69 => ["price" => 15,"item" => VanillaItems::YELLOW_DYE()],
                70 => ["price" => 15,"item" => VanillaItems::MAGENTA_DYE()],
                71 => ["price" => 15,"item" => VanillaItems::LIGHT_BLUE_DYE()],
                72 => ["price" => 15,"item" => VanillaItems::LIGHT_GRAY_DYE()],
                73 => ["price" => 1200,"item" => VanillaBlocks::OBSIDIAN()->asItem()],
                74 => ["price" => 950,"item" =>VanillaBlocks::GLOWING_OBSIDIAN()->asItem()],
                75 => ["price" => 30,"item" =>VanillaBlocks::STONE_BRICKS()->asItem()],
                76 => ["price" => 50,"item" => VanillaBlocks::END_STONE()->asItem()],
                77 => ["price" => 10,"item" => VanillaBlocks::SANDSTONE()->asItem()],
                78 => ["price" => 85,"item" => VanillaBlocks::SLIME()->asItem()],
                79 => ["price" => 50,"item" => VanillaBlocks::HAY_BALE()->asItem()],
                80 => ["price" => 30,"item" =>VanillaBlocks::CONCRETE()->asItem()],
                81 => ["price" => 20,"item" =>VanillaBlocks::CONCRETE_POWDER()->asItem()],
                82 => ["price" => 10,"item" => VanillaBlocks::ELEMENT_LITHIUM()->asItem()],

            ],
            "vip" => [
                0 => ["count"=>1,"item_qtt" => 1],
                1 => ["price" => 215000,"item" => VanillaItems::FISHING_ROD()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::FEATHER_FALLING(),1))->setCustomName("§l§fП§dpa§dз§fдни§dчный §fJе§dtpa§fсk :)")],
                2 => ["price" => 350000,"item" => VanillaItems::BOW()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::FIRE_PROTECTION(),1))->setCustomName("§l§cБАБАХ§6 лук")],
                3 => ["price" => 5000,"item" => VanillaItems::SNOWBALL()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::INFINITY(),1))->setCustomName("§l§cГ§6о§cр§6я§cч§6и§cй §bcнежoк§f")],
            ]            
        ];

        $this->s_pos = [
            "guns"=>new Vector3(14937+0.5, 37, 531+0.5),
            "food"=>new Vector3(14937+0.5, 37, 536+0.5),
            "armor"=>new Vector3(14954+0.5, 37, 531+0.5),
            "res"=>new Vector3(14954+0.5, 37, 536+0.5),
            "blocks"=>new Vector3(14937+0.5, 37, 545+0.5),
            "vip"=>new Vector3(14954+0.5, 37, 545+0.5)
        ];
    }
    
    public function onJoin(PlayerJoinEvent $event){

        if($this->qtt_global == 0){
        
            Main::clearPastItem("nsq");

            Main::GenerateShopItem("guns",$this->shop_array["guns"][0]["item_qtt"]);
            Main::GenerateShopItem("food",$this->shop_array["food"][0]["item_qtt"]);
            Main::GenerateShopItem("armor",$this->shop_array["armor"][0]["item_qtt"]);
            Main::GenerateShopItem("res",$this->shop_array["res"][0]["item_qtt"]);
            Main::GenerateShopItem("blocks",$this->shop_array["blocks"][0]["item_qtt"]);
            Main::GenerateShopItem("vip",$this->shop_array["vip"][0]["item_qtt"]);

            $this->qtt_global++;
        }

        $world = $this->getServer()->getWorldManager()->getWorldByName("shop");
        $player = $event->getPlayer();

        $guns_pos = $this->var->guns_pos;
        $food_pos = $this->var->food_pos;
        $armor_pos = $this->var->armor_pos;
        $res_pos = $this->var->res_pos;
        $blocks_pos = $this->var->blocks_pos;
        $vip_pos = $this->var->vip_pos;

        $world->addParticle($guns_pos["more_pos"], $guns_pos["more_text"], [$player]);
        $world->addParticle($guns_pos["less_pos"], $guns_pos["less_text"], [$player]);
        $world->addParticle($food_pos["more_pos"], $food_pos["more_text"], [$player]);
        $world->addParticle($food_pos["less_pos"], $food_pos["less_text"], [$player]);
        $world->addParticle($armor_pos["more_pos"], $armor_pos["more_text"], [$player]);
        $world->addParticle($armor_pos["less_pos"], $armor_pos["less_text"], [$player]);
        $world->addParticle($res_pos["more_pos"], $res_pos["more_text"], [$player]);
        $world->addParticle($res_pos["less_pos"], $res_pos["less_text"], [$player]);
        $world->addParticle($blocks_pos["more_pos"], $blocks_pos["more_text"], [$player]);
        $world->addParticle($blocks_pos["less_pos"], $blocks_pos["less_text"], [$player]);
        $world->addParticle($vip_pos["more_pos"], $vip_pos["more_text"], [$player]);
        $world->addParticle($vip_pos["less_pos"], $vip_pos["less_text"], [$player]);
    }

    public function ItemWasDemolished(ItemDespawnEvent $event){
        if($event->getEntity()->getPosition()->getWorld()->getFolderName() === "shop"){
            $event->cancel();
        }
    }


    public function PlayerPickUpShopItem(EntityItemPickupEvent $event){
        if($event->getEntity()->getPosition()->getWorld()->getFolderName() === "shop"){
            $event->cancel();
        }
    }

    public function PlayerDropItem(PlayerDropItemEvent $event){
        if($event->getPlayer()->getPosition()->getWorld()->getFolderName() === "shop"){
            $event->getPlayer()->sendTip("§cНельзя бросать вещи в магазине");
            $event->cancel();
        }
    }


    function clearPastItem(string $category){

       $shop = $this->getServer()->getWorldManager()->getWorldByName("shop");

        foreach ($shop->getEntities() as $entity){
            if ($entity instanceof ItemEntity){
                $pos = $entity->getPosition();
                if($category == "guns"){
                    if($pos->getX() == 14937+0.5 && $pos->getY() == 37 && $pos->getZ() == 531+0.5){
                        $entity->flagForDespawn();
                    }
                }elseif($category == "food"){
                    if($pos->getX() == 14937+0.5 && $pos->getY() == 37 && $pos->getZ() == 536+0.5){
                        $entity->flagForDespawn();
                    }
                }elseif($category == "armor"){
                    if($pos->getX() == 14954+0.5 && $pos->getY() == 37 && $pos->getZ() == 531+0.5){
                        $entity->flagForDespawn();
                    }
                }elseif($category == "res"){
                    if($pos->getX() == 14954+0.5 && $pos->getY() == 37 && $pos->getZ() == 536+0.5){
                        $entity->flagForDespawn();
                    }       
                }elseif($category == "blocks"){
                    if($pos->getX() == 14937+0.5 && $pos->getY() == 37 && $pos->getZ() == 545+0.5){
                        $entity->flagForDespawn();
                    }       
                }elseif($category == "vip"){
                    if($pos->getX() == 14954+0.5 && $pos->getY() == 37 && $pos->getZ() == 545+0.5){
                        $entity->flagForDespawn();
                    }       
                }else{
                    $entity->flagForDespawn();
                }
            }
        }
    }


    public function GenerateShopItem(string $cat, int $index){

        $this->getServer()->getWorldManager()->loadWorld("shop");
        $world = $this->getServer()->getWorldManager()->getWorldByName("shop");

        Main::clearPastItem($cat);
        $item = $this->shop_array[$cat][$index]["item"];
        $world->dropItem($this->s_pos[$cat],$item,new Vector3(0,0,0));
        Main::UpdateBuyTable($item,$cat,$index);
    }


    function UpdateBuyTable(Item $item, string $cat,int $index){

        $world = $this->getServer()->getWorldManager()->getWorldByName("shop");
        
        if($item->hasCustomName()){
            $name = $item->getCustomName();
        }
        else{
            $name = $item->getVanillaName();
            if(strlen($name)==15){
                $length = strlen($name);
                $sub1 = substr($name, 0, $length - 10);
                $sub2 = substr($name, $length - 10);
                $name = $sub1 . "§l§4" . $sub2;
            }
            elseif(strlen($name)>16){
                $length = strlen($name);
                $sub1 = substr($name, 0, $length - 10);
                $sub2 = substr($name, $length - 10);
                $name = $sub1 . "§l§4" . $sub2;
            }
            elseif(strlen($name) > 13){
                $length = strlen($name);
                $sub1 = substr($name, 0, $length - 8);
                $sub2 = substr($name, $length - 8);
                $name = $sub1 . "§l§4" . $sub2;
            }
        }

        
       // § l § 4 D I A M O N D   L E G G I N S

        $qtt = $this->shop_array[$cat][0]["count"];
        $price = $this->shop_array[$cat][$index]["price"];
        $price = $price * $qtt;
        
        $sign_array = [
            "guns" => [14937,36,532],
            "food" => [14937,36,535],
            "armor"=> [14954,36,532],
            "res" => [14954,36,535],
            "blocks" => [14937,36,546],
            "vip" => [14954,36,546],
        ];

        $sign = $world->getBlockAt($sign_array[$cat][0],$sign_array[$cat][1],$sign_array[$cat][2]);

        if($sign instanceof BaseSign){
            if($item->hasCustomName()){
                $text = new SignText(["§l$name","§l§9x$qtt","§r§l$price $"]);
            }else{
                $text = new SignText(["§l§4$name","§l§9x$qtt","§r§l$price $"]);

            }
            
            $sign->setText($text);
            $world->setBlock(new Vector3($sign_array[$cat][0],$sign_array[$cat][1],$sign_array[$cat][2]),$sign);
        }
    

    }

    public function OnWrite(PlayerInteractEvent $event){

        if($event->getPlayer()->getPosition()->getWorld()->getFolderName() === "shop"){  

            $Id = $event->getBlock()->getIdInfo()->getItemId();
            $pos = $event->getBlock()->getPosition();
            $coordenadas = "".round($pos->getX())." ".round($pos->getY())." ".round($pos->getZ())."";

            
            if($Id == 68 || 442){

                $table_side = [
                    "guns" => ["more" => "14938 36 532", "less" => "14936 36 532", "buy" => "14937 36 532"],
                    "food" => ["more" => "14936 36 535", "less" => "14938 36 535", "buy" => "14937 36 535"],
                    "armor" => ["more" => "14955 36 532", "less" => "14953 36 532", "buy" => "14954 36 532"],
                    "res" => ["more" => "14953 36 535", "less" => "14955 36 535", "buy" => "14954 36 535"],
                    "blocks" => ["more" => "14938 36 546", "less" => "14936 36 546", "buy" => "14937 36 546"],
                    "vip" => ["more" => "14955 36 546", "less" => "14953 36 546", "buy" => "14954 36 546"],

                ];


                foreach ($table_side as $cat => $cord) {
                    foreach ($cord as $tipo => $crd) {
                        if ($crd == $coordenadas) {
                            if($tipo == "more"){
                                if($this->shop_array[$cat][0]["item_qtt"] < count($this->shop_array[$cat])-1){
                                    $this->shop_array["$cat"][0]["item_qtt"]++;
                                    Main::GenerateShopItem($cat,$this->shop_array[$cat][0]["item_qtt"]);
                                }else{
                                    $this->shop_array["$cat"][0]["item_qtt"] = 1;
                                    Main::GenerateShopItem($cat,$this->shop_array[$cat][0]["item_qtt"]);
                                }
                            }elseif($tipo == "less"){
                                if($this->shop_array[$cat][0]["item_qtt"] > 1){
                                    $this->shop_array[$cat][0]["item_qtt"]--;
                                    Main::GenerateShopItem($cat,$this->shop_array[$cat][0]["item_qtt"]);
                                }else{
                                    $this->shop_array["$cat"][0]["item_qtt"] = count($this->shop_array[$cat])-1;
                                    Main::GenerateShopItem($cat,$this->shop_array[$cat][0]["item_qtt"]);

                                }
                            }elseif($tipo == "buy"){
                                $conf = Main::getPlayerConf($event->getPlayer()->getName());
                                $indx = $this->shop_array[$cat][0]["item_qtt"];
                                $item = $this->shop_array[$cat][$indx]["item"];

                                if($item instanceof Item){
                                    $price = $this->shop_array[$cat][$indx]["price"];
                                    $price = $price * $this->shop_array[$cat][0]["count"];
            
                                    if($conf->get("qtt_paper") >= $price){
                                        
                                        if(!$event->getPlayer()->getInventory()->canAddItem($item)){
                                            $event->getPlayer()->sendTip("§cВаш инвентарь забит!");
                                        }else{
                                            $count = $this->shop_array[$cat][0]["count"];
                                            $item = $item->setCount($count);
                                            $conf->set("qtt_paper",$conf->get("qtt_paper") - $price);$conf->save();
                                            $event->getPlayer()->getInventory()->addItem($item);
                                            $event->getPlayer()->sendTip("§aУспешная покупка!");
                                            $item->setCount(1);
                                        }
                                    }else{
                                        $event->getPlayer()->sendTip("§cУ тебя Недостаточно бумаги!");
                                    }
                                }

                            }else{
                                $this->getLogger()->info("Algo ha salido mal.. (1)");
                            }
                            break;
                        }
                    }
                }
            }

            if($Id == 77){

                $buttons = [
                    "guns" => ["more" => "14936 37 531", "less" => "14938 37 531"],
                    "food" => ["more" => "14938 37 536", "less" => "14936 37 536"],
                    "armor" => ["more" => "14953 37 531", "less" => "14955 37 531"],
                    "res" => ["more" => "14955 37 536", "less" => "14953 37 536"],
                    "blocks" => ["more" => "14936 37 545", "less" => "14938 37 545"],
                    "vip" => ["more" => "14953 37 545", "less" => "14955 37 545"],

                ];

                foreach ($buttons as $cat => $cord) {
                    foreach ($cord as $tipo => $crd) {
                      if ($crd == $coordenadas) {
                        if($tipo == "more"){
                            $this->shop_array[$cat][0]["count"]++;
                            Main::GenerateShopItem($cat,$this->shop_array[$cat][0]["item_qtt"]);
                        }elseif($tipo == "less"){
                            if($this->shop_array[$cat][0]["count"] > 1){
                                $this->shop_array[$cat][0]["count"]--;
                                Main::GenerateShopItem($cat,$this->shop_array[$cat][0]["item_qtt"]);
                            }
                        }else{
                            $this->getLogger()->info("Algo ha salido mal..");
                        }
                        break;
                      }
                    }
                  }

            }
        }
    }
}

?>