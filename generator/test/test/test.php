<?php

namespace test\test;

class test{
	public const TEST = "test";

	public function method() : void{
		echo 0;
		echo self::TEST;
	}
}