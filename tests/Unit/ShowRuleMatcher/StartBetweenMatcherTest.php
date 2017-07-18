<?php
namespace Tests\Unit\ShowRuleMatcher;

use Carbon\Carbon;
use t2t2\LiveHub\Services\ShowData;
use t2t2\LiveHub\Services\ShowRuleMatcher;
use Tests\TestCase;

class StartBetweenMatcherTest extends TestCase {

	/**
	 * Normal start time
	 *
	 * @var \stdClass
	 */
	protected $startNormal;

	/**
	 * Overnight start time
	 *
	 * @var \stdClass
	 */
	protected $startOvernight;

	public function __construct() {
		$this->startNormal = new \stdClass();
		$this->startNormal->type = 'startBetween';
		$this->startNormal->days = ['0']; // Sunday
		$this->startNormal->start = '11:00';
		$this->startNormal->end = '13:00';

		$this->startOvernight = new \stdClass();
		$this->startOvernight->type = 'startBetween';
		$this->startOvernight->days = ['0']; // Sunday
		$this->startOvernight->start = '20:00';
		$this->startOvernight->end = '04:00';
	}

	public function testTimeInRange() {
		$matcher = $this->getMatcher();

		$data = new ShowData();
		$data->start_time = Carbon::create(2015, 4, 5, 12, 0, 0); // Sunday

		$this->assertEquals(1, $matcher->match($this->startNormal, $data));
	}

	public function testTimeNotInRange() {
		$matcher = $this->getMatcher();

		$data = new ShowData();
		$data->start_time = Carbon::create(2015, 4, 5, 16, 0, 0); // Sunday

		$this->assertEquals(0, $matcher->match($this->startNormal, $data));
	}

	public function testTimeWrongDay() {
		$matcher = $this->getMatcher();

		$rule = clone $this->startNormal;
		$rule->days = ['1', '2']; // Monday

		$data = new ShowData();
		$data->start_time = Carbon::create(2015, 4, 5, 12, 0, 0); // Sunday

		$this->assertEquals(0, $matcher->match($rule, $data));
	}

	public function testTimeOvernightSameDay() {
		$matcher = $this->getMatcher();

		$data = new ShowData();
		$data->start_time = Carbon::create(2015, 4, 5, 23, 0, 0); // Sunday

		$this->assertEquals(1, $matcher->match($this->startOvernight, $data));
	}

	public function testTimeOvernightNextDay() {
		$matcher = $this->getMatcher();

		$data = new ShowData();
		$data->start_time = Carbon::create(2015, 4, 6, 2, 0, 0); // Monday

		$this->assertEquals(1, $matcher->match($this->startOvernight, $data));
	}

	public function testTimeOvernightEarly() {
		$matcher = $this->getMatcher();

		$data = new ShowData();
		$data->start_time = Carbon::create(2015, 4, 5, 2, 0, 0); // Sunday

		$this->assertEquals(0, $matcher->match($this->startOvernight, $data));
	}

	public function testTimeOvernightLate() {
		$matcher = $this->getMatcher();

		$data = new ShowData();
		$data->start_time = Carbon::create(2015, 4, 6, 22, 0, 0); // Monday

		$this->assertEquals(0, $matcher->match($this->startOvernight, $data));
	}

	/**
	 * Get a instance of the matcher
	 *
	 * @return \t2t2\LiveHub\Services\ShowRuleMatcher
	 */
	protected function getMatcher() {
		/* @var \t2t2\LiveHub\Services\ShowRuleMatcher $matcher */
		return $this->app->make(ShowRuleMatcher::class);
	}

}
