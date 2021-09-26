<?php

namespace Fallendeadnetwork\refactor\visitor;

use Closure;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\NodeVisitorAbstract;
use pocketmine\event\Cancellable;
use pocketmine\scheduler\AsyncTask;

class ClassVisitor extends NodeVisitorAbstract{
	public function enterNode(Node $node){
		if($node instanceof Class_){
			$this->onexec($node, "PluginBase", function(ClassMethod $stmt, string $name){
				switch($name){
					case "onLoad":
					case "onEnable":
					case "onDisable":
						$stmt->returnType = new Identifier("void");
						break;
				}
			});
			$this->onexec($node, "Task", function(ClassMethod $stmt, string $name){
				switch($name){
					case "onRun":
						$stmt->returnType = new Identifier("void");
						$stmt->params = [];
						break;
				}
			});
			$this->onexec($node, "AsyncTask", function(ClassMethod $stmt, string $name){
				switch($name){
					case "onCompletion":
						$stmt->params = [];
					case "onRun":
						$stmt->returnType = new Identifier("void");
						break;
				}
			});
			//Todo: PluginTask
		}
	}

	public function onexec(Class_ $node, string $name, Closure $callback) : void{
		if($node->extends instanceof Name){
			if($node->extends->parts[0] === $name){
				foreach($node->stmts as $stmt){
					if($stmt instanceof ClassMethod&&$stmt->name instanceof Identifier){
						($callback)($stmt, $stmt->name->name);
					}
				}
			}
		}
	}
}