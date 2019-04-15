<?php
	require_once 'initialize.php';

	//find the student you want to update
	//the parameter would be the id of student you want to update
	$student = Student::findById(2);

	//set the values
	$student->first_name = "John Updated";
	$student->last_name = "Doe Updated";
	$student->save();

	// or
	//$student->update();

	echo json_encode($student->toAssoc());