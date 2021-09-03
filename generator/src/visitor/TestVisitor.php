<?php

namespace Fallendeadnetwork\Generator\visitor;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;

class TestVisitor extends NodeVisitorAbstract{
	public function beforeTraverse(array $nodes){
		return null;
	}

	public function enterNode(Node $node){
		if($node instanceof ClassMethod){
			$node->stmts = [];
		}
	}

	public function leaveNode(Node $node){
		return null;
	}

	public function afterTraverse(array $nodes){
		return null;
	}
}