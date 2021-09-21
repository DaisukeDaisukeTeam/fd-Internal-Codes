<?php

namespace Fallendeadnetwork\refactor\visitor;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeVisitorAbstract;

class NamespaceVisitor extends NodeVisitorAbstract{
	public Name $name;
	public function enterNode(Node $node) {
		if($node instanceof Namespace_){
			$this->name = clone $node->name;
		}
	}

	public function leaveNode(Node $node) {
		if($node instanceof Namespace_){
			$node->name = $this->name;
		}
	}
}