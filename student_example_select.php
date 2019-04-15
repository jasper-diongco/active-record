<?php
	require_once 'initialize.php';

	//this will return a single record
	//found in the database
	$student = Student::findById(2);

	echo json_encode($student->toAssoc());