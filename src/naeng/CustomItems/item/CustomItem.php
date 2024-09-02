<?php

namespace naeng\CustomItems\item;

use customiesdevs\customies\item\CreativeInventoryInfo;
use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use naeng\CustomItems\CustomItems;
use naeng\CustomItems\trait\CustomItemTrait;
use pocketmine\block\BlockToolType;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

class CustomItem extends Item implements ItemComponents{

    use CustomItemTrait;
    use ItemComponentsTrait;

    public function __construct(ItemIdentifier $identifier, string $name = "Unknown"){
        parent::__construct($identifier, $name);
        $info = CustomItems::getInstance()->getInfo($this->getTypeId());
        $this->initComponent($info["texture"], new CreativeInventoryInfo(CreativeInventoryInfo::CATEGORY_EQUIPMENT, CreativeInventoryInfo::NONE));
        if(isset($info["render_offsets"])) {
            $this->setupRenderOffsets(($info["render_offsets"]["width"] ?? 16), ($info["render_offsets"]["height"] ?? 16), ($info["render_offsets"]["hand_equipped"] ?? false));
        }
        $components = $this->init($info);
        if(count($components) > 0){
            $this->addComponent(...$components);
        }
    }

    public function getBlockToolType() : int{
        return BlockToolType::NONE;
    }

    public function getBaseMiningEfficiency() : float{
        return $this->miningEfficiency;
    }

    public function getSpeed() : float{
        return $this->getMiningEfficiency(true);
    }

}