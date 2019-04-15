### This is my simple implementation of Active Record pattern in PHP

- This will work only if your db is mysql
- import the active_record_db.sql in your mysql server

### set up the classes
Student Class

    <?php
	class Student extends DatabaseObject {

	protected static $table_name = 'students';
	protected static $attributes = ['first_name','last_name', 'course'];
        protected static $primary_key = 'id';
        protected static $auto_increment = true;

        //properties of the class
	public $id;
	public $first_name;
	public $last_name;
	public $course;
    }

Course Class

	<?php
		class Course extends DatabaseObject {
		protected static $table_name = 'courses';
		protected static $attributes = ['name','department'];
        protected static $primary_key = 'course_id';
        protected static $auto_increment = false;

        public $course_id;
        public $name;
        public $department;
	}

### how to insert a record in students table
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

### how to update a record in students table
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

### how to select a record in students table

	<?php
		require_once 'initialize.php';

		//this will return a single record
		//found in the database
		$student = Student::findById(2);

		echo json_encode($student->toAssoc());

### how to select all records in students table
	<?php
		require_once 'initialize.php';

		//this will return a all records in the database
		$students = Student::findAll();

		//transform the list of object into JSON string
		echo Student::toJSON($students);

### how to insert a record in course table
	<?php
		require_once 'initialize.php';

		$course = new Course();
		//set the values
		$course->course_id = "C-0002";
		$course->name = "BSBA";
		$course->department = "Department 2";

		$course->create();

		echo json_encode($course->toAssoc());``
