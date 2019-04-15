<?php
	require_once 'initialize.php';

	//this will return a all records in the database
	$students = Student::findAll();

	//transform the list of object into JSON string
	echo Student::toJSON($students);