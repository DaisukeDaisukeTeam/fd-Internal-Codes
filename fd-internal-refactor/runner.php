<?php

namespace fd_internal_refactor;

include_once __DIR__."/../vendor/autoload.php";

use Fallendeadnetwork\refactor\main;

class runner{
	public function run() : void{
		$main = new main();



		$target = __DIR__.'/fallendead/src/fallendead/level/GameInstance.php';

		$output = __DIR__.'/fallendead/src';

		//$main->exec(__DIR__.'/fallendead/src1',$target,$output);

		$main->start(__DIR__.'/fallendead/src1',$output);
	}
}

(new runner())->run();
