<?php

namespace test\test;

use test\test\test;
use test\test\test as test3;

class test1{
	private const TEST = "test";

	private function method() : test{
		return new test;
	}
	private function method1() : test{
		return new test3;
	}
}
