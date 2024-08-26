<?php

namespace naeng\CustomItems;

use customiesdevs\customies\item\CustomiesItemFactory;
use naeng\CustomItems\item\armor\CustomArmor;
use naeng\CustomItems\item\CustomItem;
use naeng\CustomItems\item\tool\CustomAxe;
use naeng\CustomItems\item\tool\CustomHoe;
use naeng\CustomItems\item\tool\CustomPickaxe;
use naeng\CustomItems\item\tool\CustomShovel;
use naeng\CustomItems\item\tool\CustomSword;
use pocketmine\inventory\CreativeInventory;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;

class CustomItems extends PluginBase{

    use SingletonTrait;

    protected array $info = [];
    protected array $blocks = [];

    public function onLoad() : void{
        self::setInstance($this);
    }

    public function getInfo(int $typeId) : array{
        return $this->info[$typeId];
    }

    public static function getClassName(string $type) : string{
        return match($type){
            "boots", "chestplate", "helmet", "leggings" => CustomArmor::class,
            "axe" => CustomAxe::class,
            "hoe" => CustomHoe::class,
            "pickaxe" => CustomPickaxe::class,
            "shovel" => CustomShovel::class,
            "sword" => CustomSword::class,
            default => CustomItem::class,
        };
    }

    public function onEnable() : void{
        $factory = CustomiesItemFactory::getInstance();
        $inv = CreativeInventory::getInstance();
        foreach((new Config(Path::join($this->getServer()->getDataPath(), "custom_items.yml"), Config::YAML))->getAll() as $identifier => $info){
            $this->info[$info["id"]] = $info;
            $factory->registerItem($this->getClassName($info["type"] ?? ""), $identifier, $info["name"], $info["id"]);
            if(isset($info["lore"])){
                $i = $factory->get($identifier);
                $inv->remove($i);
                $i->setLore($info["lore"]);
                $inv->add($i);
            }
        }
    }

}