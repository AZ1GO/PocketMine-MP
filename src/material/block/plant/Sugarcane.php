<?php

/*

           -
         /   \
      /         \
   /   PocketMine  \
/          MP         \
|\     @shoghicp     /|
|.   \           /   .|
| ..     \   /     .. |
|    ..    |    ..    |
|       .. | ..       |
\          |          /
   \       |       /
      \    |    /
         \ | /

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.


*/

class SugarcaneBlock extends FlowableBlock{
	public function __construct($meta = 0){
		parent::__construct(SUGARCANE_BLOCK, $meta, "Sugarcane");
	}
	
	public function getDrops(Item $item, Player $player){
		return array(
			array(SUGARCANE, 0, 1),
		);
	}

	public function onUpdate($type){
		if($type === BLOCK_UPDATE_NORMAL){
			$down = $this->getSide(0);
			if($down->isTransparent === true and $down->getID() !== SUGARCANE_BLOCK){ //Replace wit common break method
				ServerAPI::request()->api->entity->drop($this, BlockAPI::getItem(SUGARCANE));
				$this->level->setBlock($this, new AirBlock(), false);
				return BLOCK_UPDATE_NORMAL;
			}
		}elseif($type === BLOCK_UPDATE_RANDOM){
			if($this->getSide(0)->getID() !== SUGARCANE_BLOCK){
				if($this->meta === 0x0F){
					for($y = 1; $y < 3; ++$y){
						$b = $this->level->getBlock(new Vector3($this->x, $this->y + $y, $this->z));
						if($b->getID() === AIR){
							$this->level->setBlock($b, new SugarcaneBlock());							
							break;
						}
					}
					$this->meta = 0;
					$this->level->setBlock($this, $this);
				}else{
					++$this->meta;
					$this->level->setBlock($this, $this);
				}
				return BLOCK_UPDATE_RANDOM;
			}
		}
		return false;
	}
	
	public function place(Item $item, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
			$down = $this->getSide(0);
			if($down->getID() === SUGARCANE_BLOCK){
				$this->level->setBlock($block, new SugarcaneBlock());
				return true;
			}elseif($down->getID() === GRASS or $down->getID() === DIRT or $down->getID() === SAND){
				$block0 = $down->getSide(2);
				$block1 = $down->getSide(3);
				$block2 = $down->getSide(4);
				$block3 = $down->getSide(5);
				if(($block0 instanceof WaterBlock)
				or ($block1 instanceof WaterBlock)
				or ($block2 instanceof WaterBlock)
				or ($block3 instanceof WaterBlock)){
					$this->level->setBlock($block, new SugarcaneBlock());
					$this->level->scheduleBlockUpdate(new Position($this, 0, 0, $this->level), Utils::getRandomUpdateTicks(), BLOCK_UPDATE_RANDOM);
					return true;
				}
			}
		return false;
	}
}