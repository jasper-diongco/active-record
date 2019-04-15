<?php
	require_once 'initialize.php';

	$student = new Student();
	//set the values
	$student->first_name = "Juan";
	$student->last_name = "Dela Cruz";
	$student->course = "BSIT";
	$student->save();

	// or
	//$student->create();

	echo json_encode($student->toAssoc());
