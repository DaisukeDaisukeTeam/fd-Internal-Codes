<?php

include_once __DIR__."/../vendor/autoload.php";

use Fallendeadnetwork\Generator\main;

$output = __DIR__.'/output/';

$main = new main();

$main->start(__DIR__.'/test/test/');
$main->writeResults($output);
$main->addEmptyClass($output,"test\\test", "test2");

/*
<?php

namespace test\test;

use test\test\test;
use test\test\test as test3;
class test
{
    public const TEST = "test";
    public function method() : void
    {
    }
}
class test1
{
    private const TEST = "test";
    private function method() : test
    {
    }
    private function method1() : test3
    {
    }
}

/** private class * /
class test2{}
*/

//$runner->start(__DIR__.'/vendor/daisukedaisuke/fallendead/src/fallendead/');
/*$runner->start(__DIR__.'/../vendor/daisukedaisuke/fallendead/src/fallendead/[private]/', ["[private].php"]);
$runner->start(__DIR__.'/../vendor/daisukedaisuke/fallendead/src/fallendead/[private]/', [], ["[private]"]);
$runner->start(__DIR__.'/../vendor/daisukedaisuke/fallendead/src/fallendead/[private]/', [], ["[private].php"]);
$runner->start(__DIR__.'/../vendor/daisukedaisuke/fallendead/src/fallendead/[private]/', [], ["[private].php"]);

$runner->writeResults($output);

$runner->addEmptyClass($output,"fallendead\\[private]", "[private]");
$runner->addEmptyClass($output,"fallendead\\[private]", "[private]");
$runner->addEmptyClass($output,"fallendead\\[private]", "[private]");*/
