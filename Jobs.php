<?php
    $servername = "localhost";
    $username = "root";
    $conn = new PDO("mysql:host=$servername;dbname=conference_db", $username, "");
    $sql="select distinct company from jobs";
    $temp = "-- Select Company --";
    $company = "";
    try {
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $rows=$stmt->fetchAll();
    }
    catch(Exception $e){
        echo "Connection failed: " . $e->getMessage();
        die;
    }
    if(isset($_POST['company'])){
        $company = $_POST['company'];
        $temp = $company;
    }
?>


<!DOCTYPE html>
<html>
<!-- Jobs -->
    <head>
        <title> Jobs </title>
        <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="./center.css">
        <link rel="stylesheet" href="Jobs.css">
    </head>    
    
    <form method = "post">
    
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
                <li class="nav-item active">
                    <a class="nav-link" href="#">Jobs<span class="sr-only">(current)</span></a>
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
                    <a class="nav-link" href="Events.php">Events </a>
                </li>
                </ul>
            </div>
        </nav>
        <div class="body-container" >
        <h1 style='margin-bottom: 25px; margin-top:25px; '> Jobs </h1>
            
            <select name = 'company' id = 'company' onchange = "this.form.submit();">
                
                <?php
                //echo "<option>". $temp ."</option>";
                echo $company;
                echo "<option> -- Select Company -- </option>";
                echo "<option> All </option>";
                    foreach ($rows as $output) {
                        echo " <option> " . $output['company'] . " </option> ";
                    };
                ?>    
                </select>
            <table class='table' >
                <?php echo "<p>". $company. " Jobs </p>";?>
                <thead class='thead-light' class='test'> 
                    <th> Title </th>  
                    <th> Pay </th> 
                    <th> Location </th>
                    <th> Company </th>
                </thead>
                
                <?php 
                $sql2="select * from jobs";
                if(isset($_POST['company'])){
                    if ($company != "All"){
                        $sql2 = $sql2. " where company = '$company'";
                    }                
                
                }
                
                try {
                    $stmt2=$conn->prepare($sql2);
                    $stmt2->execute();
                    $rows2=$stmt2->fetchAll();
                    foreach ($rows2 as $output){
                    
                        echo "<tr>";
                        echo "<td> ".  $output['title']. " </td> ";                        
                        echo "<td> ".  $output['pay']. " </td> ";                        
                        echo "<td> ".  $output['location'] . " </td> ";
                        echo "<td> ".  $output['company'] . " </td> ";
                        
                        echo "</tr>";
                    }
                }
                catch(Exception $e){
                    echo "Connection failed: " . $e->getMessage();
                    die;
                } 
            
                ?>
            
            <table>
        </div>
    </body>
    </form>

</html>


