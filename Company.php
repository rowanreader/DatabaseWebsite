<?php 
$servername = "localhost";
$databaseName = "conference_db";
$username = "root";

// Set up a connection to the mysql database
try{
    $db = new PDO("mysql:host=$servername;dbname=$databaseName", $username);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}

$company = $_GET['company'];

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="Company.css" />
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <title> Company </title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">CISC 332 Conference</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="Home Page.php">Home </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Jobs.php">Jobs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Companies.php">Companies</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Student-Housing.php">Student Housing</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Committee.php">Committee</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Events.php">Events</a>
            </li>
            </ul>
        </div>
    </nav>

<div class="body-container" > 
    <h2> <?php echo $company; ?></h2>   
    <a href="Companies.php">Back to List of companies</a>
    <h4> Available Positions </h4>
    <table class='table'  style='width:80%'>
        <tr>
            <th> Job Title </th>
            <th> Location </th>
            <th> Salary </th>
        </tr>
    <?php 
        if (isset($_GET['company'])){
            $sql = "SELECT * from jobs WHERE company='$company'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            foreach($data as $row){
                echo "<tr>";
                echo "<td> ".  $row['title'] . " </td>";
                echo "<td> ".  $row['location'] . " </td>";
                echo "<td> " . $row['pay'] . " </td>";
                echo "</tr>";
            }
        }

        else{
            echo "404 NOT FOUND";
        }
    ?>
    </table>

</div>
</body>


</html>
