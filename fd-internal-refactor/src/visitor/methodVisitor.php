<?php

namespace Fallendeadnetwork\refactor\visitor;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;
use pocketmine\world\World;

class methodVisitor extends NodeVisitorAbstract{
	public function enterNode(Node $node){
		if($node instanceof ClassMethod){
			if($node->returnType instanceof NullableType){
				/*if($node->returnType->type instanceof Name){
					$last = array_key_last($node->returnType->type->parts);
					$node->returnType->type->parts[$last] = $this->replace($node->returnType->type->parts[$last]);
				}*/
			}elseif($node->returnType instanceof Identifier){
				$node->returnType->name = $this->replace($node->returnType->name);
			}
		}
	}

	private function replace(string $name): string{
		return match(mb_strtolower($name)){
			"level" => "World",
			default => $name,
		};
	}
}