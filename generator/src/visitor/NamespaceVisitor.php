<?php

namespace Fallendeadnetwork\Generator\visitor;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;

class NamespaceVisitor extends NodeVisitorAbstract{
	/** @var array<string, array<int, array<Name, ClassMethod>>> */
	public array $class = [];
	/** @var array<string, array<string, Use_>> */
	public array $use = [];

	public function leaveNode(Node $node){
		if($node instanceof Namespace_){
			$name = implode("\\", $node->name->parts);
			foreach($node->stmts as $stmt){
				if($stmt instanceof Use_){
					foreach($stmt->uses as $use){
						$alias = null;
						$array = $use->name->parts;
						//($array[array_key_last($array)]);
						$name1 = implode("\\", $array);
						if($use->alias !== null){
							$alias = $use->alias;
							if($alias instanceof Identifier){
								$alias = $alias->name;
							}
							if(isset($this->use[$name][$alias])&&$this->use[$name][$alias] !== $name1){
								var_dump("warning: The use statement cannot be combined because the same alias is specified for different declarations of the use statement.");
							}
						}
						$this->use[$name][$alias ?? $name1] = $stmt;
					}
					//var_dump($this->use);
					//}elseif($stmt instanceof Class_||$stmt instanceof Trait_||$stmt instanceof Interface_){
				}elseif($stmt instanceof ClassLike){
					$this->class[$name][] = [$node->name, $stmt];
				}
			}
		}
	}

	/**
	 * @return array<string, array<int, array<Name, ClassMethod>>>
	 */
	public function getClass() : array{
		return $this->class;
	}

	/**
	 * @return array<string, array<string, Use_>>
	 */
	public function getUse() : array{
		return $this->use;
	}
}