<?php

namespace Fallendeadnetwork\refactor\visitor;

use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

/**
 * @deprecated
 */
class DocVisitor extends NodeVisitorAbstract{
	public function afterTraverse(array $nodes){
		foreach($nodes->comments ?? [] as $index => $node){
			if($node instanceof Comment){
				var_dump("!!");
				var_dump($node);
				self::bindTo(function() : void{
					/** @var $this Doc */
					$result = DocVisitor::replace($this->getText());
					$this->text = $result;
					$this->endTokenPos = $this->getStartFilePos() + strlen($result) - 1;
					var_dump($this);
				}, $node);
			}
		}
	}

	public static function replace(string $name) : string{
		return str_ireplace([
			0 => "level",
		], [
			0 => "World",
		], $name);
	}

	public static function bindTo(\Closure $closure, $class){
		return $closure->bindTo($class, get_class($class))();
	}
}