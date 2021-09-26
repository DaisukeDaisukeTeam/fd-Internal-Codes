<?php

namespace Fallendeadnetwork\refactor;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;

class useInjector{
	public static function onexec(Namespace_ $node, string $target){
		$target_array = explode("\\", $target);
		$hasCancellable = false;
		$alias = "CancellableTrait";
		foreach($node->stmts as $stmt1){
			if($stmt1 instanceof Use_){
				foreach($stmt1->uses as $useuse){
					$use1 = implode("\\", $useuse->name->parts);
					if($use1 === $target){//"pocketmine\\event\\CancellableTrait"
						$hasCancellable = true;
						$alias = $useuse->alias->name ?? $target_array[array_key_last($target_array)];
					}
				}
			}
		}
		//
		if(!$hasCancellable){
			array_unshift($node->stmts, new Use_([new UseUse(new Name($target_array))]));//["pocketmine","event","CancellableTrait"]
		}
		return $alias;
	}
}
