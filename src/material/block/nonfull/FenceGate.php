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

class FenceGateBlock extends TransparentBlock{
	public function __construct($meta = 0){
		parent::__construct(FENCE_GATE, $meta, "Fence Gate");
		$this->isActivable = true;
		if(($this->meta & 0x04) === 0x04){
			$this->isFullBlock = true;
		}else{
			$this->isFullBlock = false;
		}
	}
	public function place(Item $item, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
		$faces = array(
			0 => 3,
			1 => 0,
			2 => 1,
			3 => 2,
		);
		$this->meta = $faces[$player->entity->getDirection()] & 0x03;
		$this->level->setBlock($block, $this);
		return true;
	}
	public function getDrops(Item $item, Player $player){
		return array(
			array($this->id, 0, 1),
		);
	}
	public function onActivate(Item $item, Player $player){
		$faces = array(
			0 => 3,
			1 => 0,
			2 => 1,
			3 => 2,
		);
		$this->meta = ($faces[$player->entity->getDirection()] & 0x03) | ((~$this->meta) & 0x04);
		$this->level->setBlock($this, $this);
		return true;
	}	
}