<?php
	require_once 'classes/DatabaseObject.php';
	require_once 'classes/Student.php';
	require_once 'classes/Course.php';

	//create a new instance of PDO
	try {
		$options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
		$pdo = new PDO('mysql: host=localhost;dbname=active_record_db', 'root', '', $options);
	} catch(PDOException $err) {
		exit($err);
	}

	//set the connection of class
	DatabaseObject::setConnection($pdo);