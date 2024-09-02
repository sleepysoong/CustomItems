<?php

namespace naeng\CustomItems\item\tool;

use customiesdevs\customies\item\component\DiggerComponent;
use customiesdevs\customies\item\CreativeInventoryInfo;
use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use naeng\CustomItems\CustomItems;
use naeng\CustomItems\trait\CustomItemTrait;
use pocketmine\block\BlockToolType;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\Shovel;
use pocketmine\item\ToolTier;

class CustomShovel extends Shovel implements ItemComponents{

    use CustomItemTrait;
    use ItemComponentsTrait;

    public function __construct(ItemIdentifier $identifier, string $name = "Unknown"){
        parent::__construct($identifier, $name, ToolTier::NETHERITE());
        $info = CustomItems::getInstance()->getInfo($this->getTypeId());
        $this->initComponent($info["texture"], new CreativeInventoryInfo(CreativeInventoryInfo::CATEGORY_EQUIPMENT, CreativeInventoryInfo::GROUP_SHOVEL));
        if(isset($info["render_offsets"])) {
            $this->setupRenderOffsets(($info["render_offsets"]["width"] ?? 16), ($info["render_offsets"]["height"] ?? 16), ($info["render_offsets"]["hand_equipped"] ?? false));
        }
        $components = $this->init($info);
        if(isset($info["digger"])){
            $speed = $this->getMiningEfficiency(true);
            $diggerComponent = new DiggerComponent();
            if($info["digger"] === "type"){
                $this->getTypedDiggerComponent($diggerComponent, $speed);
            }elseif($info["digger"] === "all"){
                $this->getAllDiggerComponent($diggerComponent, $speed);
            }
            if(isset($info["digger"]["tags"]) && count($info["digger"]["tags"]) > 0){
                $diggerComponent->withTags($speed, ...$info["digger"]["tags"]);
            }elseif(is_array($info["digger"]) && count($info["digger"]) > 0){
                $blocks = $info["digger"];
                (\Closure::bind(function() use($blocks){
                    foreach($blocks as $block => $speed){
                        $this->destroySpeeds[] = [
                            "block" => ["name" => $block],
                            "speed" => $speed
                        ];
                    }
                }, $diggerComponent, $diggerComponent::class))();
            }
            $components[] = $diggerComponent;
        }
        if(count($components) > 0){
            $this->addComponent(...$components);
        }
    }

    public function getBlockToolType() : int{
        return BlockToolType::SHOVEL;
    }

}