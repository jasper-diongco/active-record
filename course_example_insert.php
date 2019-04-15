<?php
	require_once 'initialize.php';

	$course = new Course();
	//set the values
	$course->course_id = "C-0002";
	$course->name = "BSBA";
	$course->department = "Department 2";

	$course->create();

	echo json_encode($course->toAssoc());