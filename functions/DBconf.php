<?php
session_start();

define("ERROR_LOG_FILE","error.log");

$host = "Localhost";
$username = "root";
$passwd = "password";
$port = "3306";
$db = "dreamwatch";

try
{

	$bdd = new PDO('mysql:host='.$host.';dbname='.$db.';port='.$port.'', $username, $passwd);

}
catch (PDOException $e)
{
	echo "PDO ERROR:" .$e->getMessage()." storage in ".ERROR_LOG_FILE."\n";
	file_put_contents(ERROR_LOG_FILE,$e->getMessage());
	return false;
}


?>
