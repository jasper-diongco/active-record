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