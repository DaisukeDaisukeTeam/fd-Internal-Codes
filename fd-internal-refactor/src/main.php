<?php

namespace Fallendeadnetwork\refactor;

include_once __DIR__."/../../vendor/autoload.php";

use Fallendeadnetwork\Generator\visitor\TestVisitor;
use Fallendeadnetwork\refactor\visitor\DocVisitor;
use Fallendeadnetwork\refactor\visitor\methodCallVisitor;
use Fallendeadnetwork\refactor\visitor\methodVisitor;
use Fallendeadnetwork\refactor\visitor\NamespaceVisitor;
use Fallendeadnetwork\refactor\visitor\NameVisitor;
use Fallendeadnetwork\refactor\visitor\UseVisitor;
use PhpParser\Comment\Doc;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use pocketmine\world\World;

class main{
	/**
	 * @param list<string> $exclude
	 */
	public function start(string $directory ,string $output, array $exclude = [], array $whitelist = []) : self{
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
			$this->exec($directory, $path, $output);
		}
		return $this;
	}

	public function exec(string $directory, string $path,string $output) : void{
		$code = '<?php class range{
    public Level $gameLevel;
}';
//		$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
//		$stmts = $parser->parse($code);
//
//		var_dump($stmts);
//
//		$dumper = new NodeDumper(['dumpComments' => true,]);
//		echo $dumper->dump($stmts, $code);
//
//		exit();

		//var_dump('/\/\*\*[^\@](@\S* ([\n]*))\*//us');

		$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
		$stmts = $parser->parse(file_get_contents($path));

		/*$dumper = new NodeDumper(['dumpComments' => true,]);
		echo $dumper->dump($stmts, file_get_contents($path));*/

		$traverser = new NodeTraverser;
		$traverser->addVisitor(new NamespaceVisitor());
		//$traverser->addVisitor(new UseVisitor());
		//$traverser->addVisitor(new DocVisitor());
		$traverser->addVisitor(new methodCallVisitor());
		$traverser->addVisitor(new methodVisitor());
		$traverser->addVisitor(new NameVisitor());

		$stmts = $traverser->traverse($stmts);
		$prettyPrinter = new Standard();
		$code = $prettyPrinter->prettyPrintFile($stmts);

		preg_match_all('/@[\S]*\s\??[^\\*]*/us',$code,$m);

		foreach($m[0] as $item){
			$target = trim($item);
			$result = str_ireplace([
				0 => " Level",
				1 => "?Level",
				2 => "|Level",
				3 => "\\pocketmine\\entity\\DataPropertyManager,",
			],[
				0 => " World",
				1 => "?World",
				2 => "|World",
				3 => "pocketmine\\network\\mcpe\\protocol\\types\\entity\\DataPropertyManager,",
			], $target);
			if($target !== $result){
				$code = str_replace($target, $result, $code);
			}
		}

		$outputdir = $output.str_replace($directory,"", $path);

		$outputdir = str_replace(["\\","/"], DIRECTORY_SEPARATOR, $outputdir);

		@mkdir(dirname($outputdir),0777, true);

		file_put_contents($outputdir, $code);
	}
}