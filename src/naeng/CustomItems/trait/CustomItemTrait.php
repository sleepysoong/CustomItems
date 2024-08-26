<?php

namespace naeng\CustomItems\trait;

use customiesdevs\customies\item\component\AllowOffHandComponent;
use customiesdevs\customies\item\component\ArmorComponent;
use customiesdevs\customies\item\component\BlockPlacerComponent;
use customiesdevs\customies\item\component\CanDestroyInCreativeComponent;
use customiesdevs\customies\item\component\ChargeableComponent;
use customiesdevs\customies\item\component\CooldownComponent;
use customiesdevs\customies\item\component\DiggerComponent;
use customiesdevs\customies\item\component\DurabilityComponent;
use customiesdevs\customies\item\component\FoilComponent;
use customiesdevs\customies\item\component\FoodComponent;
use customiesdevs\customies\item\component\FuelComponent;
use customiesdevs\customies\item\component\InteractButtonComponent;
use customiesdevs\customies\item\component\ItemComponent;
use customiesdevs\customies\item\component\KnockbackResistanceComponent;
use customiesdevs\customies\item\ItemComponentsTrait;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Durable;

trait CustomItemTrait{

    use ItemComponentsTrait;

    private int $maxStackSize = 64;
    private int $defensePoint = 5;
    private int $maxDurability = 100;

    protected function getAllDiggerComponent(int $speed): DiggerComponent{
        $component = new DiggerComponent();
        foreach(VanillaBlocks::getAll() as $block){
            $component->withBlocks($speed, $block);
        }
        return $component;
    }

    protected function getTypedDiggerComponent(int $speed): DiggerComponent{
        $component = new DiggerComponent();
        foreach(VanillaBlocks::getAll() as $block){
            if($block->getBreakInfo()->getToolType() == $this->getBlockToolType()){
                $component->withBlocks($speed, $block);
            }
        }
        return $component;
    }


    /** @return ItemComponent[] */
    protected function init(array $info) : array{
        $components = [];
        if(isset($info["off_hand"])){
            $components[] = new AllowOffHandComponent($info["off_hand"]);
        }
        if(isset($info["armor_component"])){
            $components[] = new ArmorComponent($info["armor_component"]["protection"], $info["armor_component"]["texture_type"]);
        }
        if(isset($info["block_placer"])){
            $components[] = new BlockPlacerComponent($info["block_placer"]["block_identifier"], $info["block_placer"]["use_block_description"] ?? false);
        }
        if(isset($info["canDestroyInCreative"])){
            $components[] = new CanDestroyInCreativeComponent($info["canDestroyInCreative"]);
        }
        if(isset($info["chargeable"])){
            $components[] = new ChargeableComponent(floatval($info["chargeable"]));
        }
        if(isset($info["cooldown"])){
            $components[] = new CooldownComponent($info["cooldown"]["category"], $info["cooldown"]["duration"]);
        }
        if(isset($info["digger"])){
            if($info["digger"] === "type"){
                $components[] = $this->getTypedDiggerComponent(5);
            }elseif($info["digger"] === "all"){
                $components[] = $this->getAllDiggerComponent(5);
            }else{
                $blocks = $info["digger"];
                $diggerComponent = new DiggerComponent();
                (\Closure::bind(function() use($blocks){
                    foreach($blocks as $block => $speed){
                        $this->destroySpeeds[] = [
                            "block" => ["name" => $block],
                            "speed" => $speed
                        ];
                    }
                }, $diggerComponent, $diggerComponent::class))();
                $components[] = $diggerComponent;
            }
        }
        if(isset($info["max_durability"])){
            $maxDurability = $info["max_durability"];
            if($maxDurability === "infinity"){
                $this->setUnbreakable();
            }else{
                $this->maxDurability = $maxDurability;
                $components[] = new DurabilityComponent($maxDurability);
            }
        }
        if(isset($info["foil"])){
            $components[] = new FoilComponent($info["foil"]);
        }
        if(isset($info["can_always_eat"])){
            $components[] = new FoodComponent($info["can_always_eat"]);
        }
        if(isset($info["fuel"])){
            $components[] = new FuelComponent(floatval($info["fuel"]));
        }
        if(isset($info["interact_button"])){
            $components[] = new InteractButtonComponent($info["interact_button"]);
        }
        if(isset($info["knockback_resistance"])){
            $components[] = new KnockbackResistanceComponent($info["knockback_resistance"]);
        }
        if(isset($info["max_stack_size"])){
            $this->maxStackSize = $info["max_stack_size"];
        }
        if(isset($info["defense_point"])){
            $this->defensePoint = $info["defense_point"];
        }
        return $components;
    }

    public function getMaxStackSize() : int{
        return $this->maxStackSize;
    }

    public function getDefensePoints() : int{
        return $this->defensePoint;
    }

    public function getMaxDurability() : int{
        return $this->maxDurability;
    }

}