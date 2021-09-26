<?php

namespace Fallendeadnetwork\refactor\visitor;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\NodeVisitorAbstract;

class ClassConstFetchVisitor  extends NodeVisitorAbstract{
	public function enterNode(Node $node) {
		if($node instanceof ClassConstFetch){
			var_dump($node);
			if($node->class instanceof Name&&$node->class->parts[0] === "Player"){
				if($node->name instanceof Identifier){
					switch(strtolower($node->name->name)){
						case "adventure";
							$node->class->parts[0] = "GameMode";
						break;
					}
				}
			}
		}
	}
}