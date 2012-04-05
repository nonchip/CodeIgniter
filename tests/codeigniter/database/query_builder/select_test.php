<?php

class Select_test extends CI_TestCase {

	/**
	 * @var object Database/Query Builder holder
	 */
	protected $query_builder;

	public function set_up()
	{
		$db = Mock_Database_Schema_Skeleton::init(DB_DRIVER);

		Mock_Database_Schema_Skeleton::create_tables();
		Mock_Database_Schema_Skeleton::create_data();

		$this->query_builder = $db;
	}

	// ------------------------------------------------------------------------

	/**
	 * @see ./mocks/schema/skeleton.php
	 */
	public function test_select_only_one_collumn()
	{
		$jobs_name = $this->query_builder->select('name')
		                                  ->get('job')
		                                  ->result_array();
		
		// Check rows item
		$this->assertArrayHasKey('name',$jobs_name[0]);
		$this->assertFalse(array_key_exists('id', $jobs_name[0]));
		$this->assertFalse(array_key_exists('description', $jobs_name[0]));
	}

	// ------------------------------------------------------------------------

	/**
	 * @see ./mocks/schema/skeleton.php
	 */
	public function test_select_min()
	{
		$job_min = $this->query_builder->select_min('id')
		                               ->get('job')
		                               ->result_array();
		
		// Minimum id was 1
		$this->assertEquals('1', $job_min[0]['id']);
	}

	// ------------------------------------------------------------------------

	/**
	 * @see ./mocks/schema/skeleton.php
	 */
	public function test_select_max()
	{
		$job_max = $this->query_builder->select_max('id')
		                               ->get('job')
		                               ->result_array();
		
		// Maximum id was 4
		$this->assertEquals('4', $job_max[0]['id']);
	}

	// ------------------------------------------------------------------------

	/**
	 * @see ./mocks/schema/skeleton.php
	 */
	public function test_select_avg()
	{
		$job_avg = $this->query_builder->select_avg('id')
		                               ->get('job')
		                               ->result_array();
		
		// Average should be 2.5
		$this->assertEquals('2.5', $job_avg[0]['id']);
	}

	// ------------------------------------------------------------------------

	/**
	 * @see ./mocks/schema/skeleton.php
	 */
	public function test_select_sum()
	{
		$job_sum = $this->query_builder->select_sum('id')
		                               ->get('job')
		                               ->result_array();
		
		// Sum of ids should be 10
		$this->assertEquals('10', $job_sum[0]['id']);
	}
	
}