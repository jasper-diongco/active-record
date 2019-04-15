<?php
	class DatabaseObject {
		/**
         * This is my simple implementation of 
         * Active Record Design Pattern in PHP
         * Author: Jasper Diongco
         */

		protected static $table_name = '';
        protected static $attributes = [];
        protected static $primary_key = 'id';
        protected static $auto_increment = true;
        protected static $conn;



        /**
         * Returns all record in the table
         * 
         */
        public static function findAll($options=[]) {
        	$result_set = [];
    	
        	$sql = 'SELECT * FROM ' . static::$table_name;

        	//options

        	//order by
        	if(isset($options['order_by'])) {
        		$sql .= ' ORDER BY ' . $options['order_by'];
        	}

        	//limit
        	if(isset($options['limit'])) {
        		$sql .= ' LIMIT ' . $options['limit'];
        	}


        	$stmt = self::$conn->prepare($sql);

        	$stmt->execute();

        	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        	foreach ($rows as $row) {
        		$result_set[] = static::instantiate($row);
        	}

        	return $result_set;
        }

        /**
         * This method return a single record
         * With the matching id in the parameter 
         */
        public static function findById($id) {
            $sql = 'SELECT * FROM ' . static::$table_name;
            $sql .= ' WHERE ' . static::$primary_key . ' = :' . static::$primary_key;

            $stmt = self::$conn->prepare($sql);

            $stmt->execute([
                ':' . static::$primary_key => $id
            ]);

            if($stmt->rowCount() <= 0) return false; 

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $obj = static::instantiate($result);

            //set the id to the object
            $primary_key = static::$primary_key;
            $obj->$primary_key = $id;

            return $obj;       
        }

        /**
         * This method will create a new record in the database
         */
        public function create() {
            $attributes = static::$attributes;
            $params = [];
        

            //include the primary key if not auto increment
            if(!static::$auto_increment) {
                array_unshift($attributes,static::$primary_key);
            }

            //params has pre colon 
            //for example => :attribute
            foreach($attributes as $attribute) {
                $params[] = ":" . $attribute;
            }

            //construct sql for inserting a new record in database
            $sql = 'INSERT INTO ' . static::$table_name . ' (';
            $sql .= implode(', ', $attributes) . ') ';
            $sql .= 'VALUES (';
            $sql .= implode(', ', $params);
            $sql .= ')';

            $stmt = self::$conn->prepare($sql);

            //bind values to params
            foreach ($attributes as $attribute) {
                if(property_exists($this,$attribute)) {
                    $stmt->bindParam(':' . $attribute, $this->$attribute);
                }
            }

            return $stmt->execute();
        }

        /**
         * This method will update a new record in the database
         */
        public function update() {

            //create attributes pairs, ex : column=:column
            $attribute_pairs = [];
            foreach(static::$attributes as $key) {
                $attribute_pairs[] = "{$key} = :{$key}";
            }

            //construct sql that will update a record
            $sql = "UPDATE " . static::$table_name . " SET ";
            $sql .= join(", ", $attribute_pairs);
            $sql .= " WHERE " . static::$primary_key . "= :" . static::$primary_key;
            $sql .= ' LIMIT 1';

            //prepare the query
            $stmt = self::$conn->prepare($sql);

            //bind values to params
            $attributes = static::$attributes;
            foreach ($attributes as $attribute) {
                if(property_exists($this,$attribute)) {
                    $stmt->bindParam(':' . $attribute, $this->$attribute);
                }
            }

            //bind the primary key
            $id = static::$primary_key;
            $stmt->bindParam(':' . static::$primary_key, $this->$id);


            return $stmt->execute();

        }


        /**
         * This method will save or update a record
         * if the id of an object has value, then it will trigger the update method
         * else, if there is no value it will trigger the create
         */
        public function save() {
            $id = static::$primary_key;

            if(empty($this->$id)) {
                $this->create();
            } else {
                $this->update();
            }
        }

        /**
         * This method will count number the records in the database
         */
        public static function count() {
            //construct sql statement
            $sql = "SELECT COUNT(*) as count FROM " . static::$table_name;


            $stmt = self::$conn->prepare($sql);

            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['count'];
        }


        /**
         * Set the PDO connection
         */
        public static function setConnection($pdoConnection) {
        	self::$conn = $pdoConnection;
        }


        /**
         * This method instaniate a new object
         * the parameter $record is a database row,
         * it is the result of fetching a record in db
         */
        protected static function instantiate($record) {
        	$object = new static;

        	// Could manually assign values to properties
            // But automatically assignment is easier and re-usable
            foreach($record as $property => $value) {
              if(property_exists($object, $property)) {
                $object->$property = $value;
              }
            }
            return $object;
        } 

        protected static function closeConnection() {
        	self::$conn = null;
        }

        public function toAssoc() {
            //get the attributes
            $result_assoc;
            $attributes = static::$attributes;
            array_unshift($attributes, static::$primary_key);
            foreach($attributes as $attribute) {
                $result_assoc[$attribute] = $this->$attribute;
            }
            return $result_assoc;
        }

        public static function toJSON($listObj=[]) {
        	$result = [];

        	foreach ($listObj as $obj) {
        		$result[] = $obj->toAssoc();
        	}

        	return json_encode($result);
        }
	}