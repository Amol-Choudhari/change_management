<?php
 
$host='10.153.72.53';
$db = 'aqcmstest';
$username = 'dsm';
$password = '';
 
/*$dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";
 
try{
 // create a PostgreSQL database connection
 $conn = new PDO($dsn);
 
 // display a message if connected to the PostgreSQL successfully
 if($conn){
    echo "Connected to the <strong>$db</strong> database successfully!";
 }
} catch (PDOException $e){
 // report error message
 echo $e->getMessage();
}*/

$conn = pg_connect("host='10.153.72.53' port=5432 dbname='aqcmstest' user='dsm' password=''") ;


$result = pg_query('select * from users');

while($row = pg_fetch_assoc($result)){
	print_r($row['name'].'<br>');
}

?>
