<?php

namespace Fallendeadnetwork\Generator;

include_once __DIR__."/../../vendor/autoload.php";

use Fallendeadnetwork\Generator\visitor\NamespaceVisitor;
use Fallendeadnetwork\Generator\visitor\TestVisitor;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

class main{
	/** @var array<string, Namespace_> $result */
	public array $result = [];
	public NamespaceVisitor $namespaceVisitor;

	public function __construct(){
		$this->namespaceVisitor = new NamespaceVisitor();
	}

	/**
	 * @param list<string> $exclude
	 */
	public function start(string $directory, array $exclude = [], array $whitelist = []) : self{
		if(!is_dir($directory)){
			throw new \RuntimeException('directory "'.$directory.'" not found.');
		}
		foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $path => $file){
			if($file->isFile() === false){
				continue;
			}
			if(substr($path, strrpos($path, '.') + 1) !== "php"){
				continue;
			}
			foreach($exclude as $string){
				if(strpos($path, $string) !== false){
					continue 2;
				}
			}
			if(count($whitelist) !== 0){
				$skip = true;
				foreach($whitelist as $string){
					if(strpos($path, $string) !== false){
						$skip = false;
						break;
					}
				}
				if($skip === true){
					continue;
				}
			}
			$this->exec($path);
		}
		return $this;
	}

	public function exec(string $path) : void{
		$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
		$stmts = $parser->parse(file_get_contents($path));

		/*$dumper = new NodeDumper(['dumpComments' => true,]);
		echo $dumper->dump($stmts, file_get_contents($path));*/

		$traverser = new NodeTraverser;
		$traverser->addVisitor(new TestVisitor());
		$traverser->addVisitor($this->namespaceVisitor);
		$stmts = $traverser->traverse($stmts);
	}

	public function writeResults(string $outputdir) : self{
		$lastchar = $outputdir[strlen($outputdir) - 1];
		if($lastchar !== "/"&&$lastchar !== "\\"){
			$outputdir .= DIRECTORY_SEPARATOR;
		}

		$result = [];
		foreach($this->namespaceVisitor->getClass() as $strname => $array){
			foreach($array as $array1){
				if(!isset($result[$strname])){
					$use = $this->namespaceVisitor->getUse();
					$result[$strname] = new Namespace_($array1[0], $use[$strname]);
				}
				$result[$strname]->stmts[] = $array1[1];
			}
		}

		foreach($result as $name => $new_stmts){
			$prettyPrinter = new Standard();
			$code = $prettyPrinter->prettyPrintFile([$new_stmts]);

			$output = $outputdir.str_replace("\\", DIRECTORY_SEPARATOR, $name).".php";

			if(!@mkdir(dirname($output), 0755, true)&&!is_dir($outputdir)){
				throw new \RuntimeException(sprintf('Directory "%s" was not created', $outputdir));
			}

			file_put_contents($output, $code);
		}
		return $this;
	}

	public function addEmptyClass(string $output, string $file, string $name) : self{
		file_put_contents($output.str_replace("\\", DIRECTORY_SEPARATOR, $file).".php", "\n\n/** private class */\nclass ".$name."{}\n", FILE_APPEND);
		return $this;
	}
}
