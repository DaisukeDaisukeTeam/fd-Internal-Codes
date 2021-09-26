<?php

namespace Fallendeadnetwork\refactor\visitor;

use Fallendeadnetwork\refactor\useInjector;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\NodeVisitorAbstract;

class NamespaceVisitor extends NodeVisitorAbstract{
	public Name $name;
	public function enterNode(Node $node) {
		if($node instanceof Namespace_){
			$this->name = clone $node->name;
			$this->test($node);
		}
	}

	public function leaveNode(Node $node) {
		if($node instanceof Namespace_){
			$node->name = $this->name;
		}
	}

	public function test(Namespace_ $node) : void{
		foreach($node->stmts as $stmt){
			if($stmt instanceof Class_){
				foreach($stmt->implements as $implement){
					if($implement instanceof Name&&$implement->parts[0] === "Cancellable"){
						$alias = useInjector::onexec($node, "pocketmine\\event\\CancellableTrait");
						array_unshift($stmt->stmts, new TraitUse([new Name($alias)]));
					}
				}
			}
		}
	}
}