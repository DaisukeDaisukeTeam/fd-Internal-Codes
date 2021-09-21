<?php

namespace Fallendeadnetwork\refactor\visitor;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\NodeVisitorAbstract;

class NameVisitor extends NodeVisitorAbstract{
	public function enterNode(Node $node){
		if($node instanceof Name){
			$node->parts = $this->replaceUse($node->parts);
		}
	}

	private function replace(string $name): string{
		$target = mb_strtolower($name);
		if($target === "level"){
			return "World";
		}
		return match($target){
			" level" => " World",
			default => $name,
		};
	}

	public function replaceUse(array $use) : array{
		$use1 = implode("\\", $use);
		$result = match (mb_strtolower($use1)) {
			"pocketmine\\player" => "pocketmine\player\Player",
			"pocketmine\\level\level" => "pocketmine\world\World",
			"pocketmine\\entity\\datapropertymanager" => "pocketmine\\network\\mcpe\\protocol\\types\\entity\\DataPropertyManager",

			default => $use1,
		};

		$result1 = str_replace(["pocketmine\\level","pocketmine\\tile"], ["pocketmine\\world","pocketmine\\block\\tile"], $result);

		$result1 = explode("\\", $result1);

		$key = array_key_last($result1);

		$result1[$key] = $this->replace($result1[$key]);

		return $result1;
	}
}