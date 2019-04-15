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