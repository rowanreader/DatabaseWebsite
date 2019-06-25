<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title> Testing database connection </title>
</head>

<body>
<h3> Create a Table</h3>

<form method="POST">
    <input type="text" name="tableName" />
    <input type="submit" />
</form>

</body>

</html>


<?php 
$servername = "localhost";
$databaseName = "testDatabase";
$username = "root";

$name1 = "john";
$name2 = "clark";


// Set up a connection to the mysql database
try{
    $conn = new PDO("mysql:host=$servername;dbname=$databaseName", $username);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "connected successfully";
    if (isset($_POST["tableName"])){
        $sql= "INSERT INTO plsworklol (name) VALUES(('$name2'));";
        echo "$sql";
        $conn->exec($sql);
    };

    
}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }

$conn = null;
?>