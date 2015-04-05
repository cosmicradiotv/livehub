<?php

use t2t2\LiveHub\Services\ShowData;
use t2t2\LiveHub\Services\ShowRuleMatcher;

class TitleMatcherTest extends TestCase {

	/**
	 * Title matches
	 */
	public function testTitleMatch() {
		$matcher = $this->getMatcher();

		$rule = new stdClass();
		$rule->type = 'title';
		$rule->rule = '/Something/';

		$data = new ShowData();
		$data->title = 'The Something Stream!';

		$this->assertEquals(1, $matcher->match($rule, $data));
	}

	/**
	 * Title doesn't match
	 */
	public function testTitleNotMatch() {
		$matcher = $this->getMatcher();

		$rule = new stdClass();
		$rule->type = 'title';
		$rule->rule = '/Nothing/';

		$data = new ShowData();
		$data->title = 'The Something Stream!';

		$this->assertEquals(0, $matcher->match($rule, $data));
	}

	/**
	 * Get a instance of the matcher
	 *
	 * @return ShowRuleMatcher
	 */
	protected function getMatcher(){
		/** @var ShowRuleMatcher $matcher */
		return $this->app->make(ShowRuleMatcher::class);
	}

}