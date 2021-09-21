<?php

namespace Fallendeadnetwork\refactor\visitor;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\NodeVisitorAbstract;

class methodCallVisitor extends NodeVisitorAbstract{
	public function enterNode(Node $node){
		if($node instanceof MethodCall){
			if($node->name instanceof Identifier){
				//$player->sendDataPacket(); => $player->getNetworkSession()->sendDataPacket();
				if($node->name->name === "sendDataPacket"){
					$node->var = new MethodCall($node->var, new Identifier("getNetworkSession"));
				}
			}
		}
	}
}