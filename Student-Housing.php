<?php
    $servername = "localhost";
    $username = "root";
    $conn = new PDO("mysql:host=$servername;dbname=conference_db", $username, "");
    $sql="select room_number from rooms";
    $rm = "null";
    $temp = "-- Select Room Number --";
    try {
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $rows=$stmt->fetchAll();
    }
    catch(Exception $e){
        echo "Connection failed: " . $e->getMessage();
        die;
    }
    if(isset($_POST['rm_num'])){
        $rm = $_POST['rm_num'];
        $temp = $rm;
    }
?>


<!DOCTYPE html>
<html>
<!-- Student Housing -->
    <head>
        <link rel="stylesheet" href="Student-Housing.css" />
        <link rel="stylesheet" href="center.css" />
        <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
        <title> Student Housing </title>
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
                <li class="nav-item">
                    <a class="nav-link" href="Jobs.php">Jobs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Companies.php">Companies</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#">Student Housing<span class="sr-only">(current)</span></a>
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
        <div class='body-container'>
        <h1 style='margin-bottom: 25px; margin-top:25px; '>Student Housing </h1>
            <!--$rm = $_POST['room_number'];?>-->
            <select name = 'rm_num' id = 'rm_num' onchange = "this.form.submit();">
            <!--<input type="submit" name = "submit" value = "Get selected value"/>   -->
                
                <?php
                    //echo "<option>". $temp.  "</option>";
                    echo "<option> -- Select Room Number -- </option>";   
                    echo "<option> All </option>";    
                    foreach ($rows as $output) {                                    
                        echo " <option> " . $output["room_number"] . " </option> ";
                    };
                ?>    
                </select>
            <table class='table' >
                <thead class='thead-light'> 
                    <th> Room </th> 
                    <th> Students </th> 
                </thead>
                <?php 
                if(isset($_POST['rm_num'])){
                    if ($rm == "All"){
                        $sql2="select * from students";
                    }
                    else{
                        $sql2="select * from students where room_number = '$rm'";
                        $sql3 = "select spots from rooms where room_number = '$rm'";
                        
                        $stmt3=$conn->prepare($sql3);
                        $stmt3->execute();
                        $capacity=(int)($stmt3->fetchColumn(0));
                                    
                        echo " Room Number: ". $rm;
                        
                        echo ", Capacity: ". $capacity;
                    }
                    try {
                        $stmt2=$conn->prepare($sql2);
                        $stmt2->execute();
                        $rows2=$stmt2->fetchAll();
                        if (empty($rows2)){
                            echo "<tr>";
                            echo "<td> ". $rm. " </td> ";
                            echo "<td> No Students </td> ";
                            echo "</tr>";
                        }
                        else{
                            foreach ($rows2 as $output){
                                echo "<tr>";
                                echo "<td> ".  $output['room_number']. " </td> ";
                                echo "<td> ".  $output['first_name']. " " .$output['last_name'] . " </td> ";
                                echo "</tr>";
                        }
                        }

                    }
                    catch(Exception $e){
                        echo "Connection failed: " . $e->getMessage();
                        die;
                    } 
                }
                ?>
            
            <table>
        </div>
            
    </body>
    </form>

</html>


