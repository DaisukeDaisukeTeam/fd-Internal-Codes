<?php

namespace Fallendeadnetwork\refactor\visitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;

class UseVisitor extends NodeVisitorAbstract{
	public function enterNode(Node $node){
		if($node instanceof Use_){
			$new = [];
			foreach($node->uses as $key => $useuse){
				$useuse->name->parts = $this->replaceUse($useuse->name->parts);
			}
		}
	}

	public function replaceUse(array $use) : array{
		$use1 = implode("\\", $use);
		$result = match (mb_strtolower($use1)) {
			"pocketmine\\player" => "pocketmine\player\Player",
			"pocketmine\\level\level" => "pocketmine\world\World",
			"pocketmine\\entity\\datapropertymanager" => "pocketmine\\network\\mcpe\\protocol\\types\\entity\\DataPropertyManager",
			default => $use1,
		};
		$result1 = str_replace([
			0 => "pocketmine\\level",
			1 => "pocketmine\\tile"
		],[
			0 => "pocketmine\\world",
			1 => "pocketmine\\block\\tile"
		], $result);
		$result1 = explode("\\", $result1);

		return $result1;
	}
}
