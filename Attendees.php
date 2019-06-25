<?php
    $servername = "localhost";
    $username = "root";
    $conn = new PDO("mysql:host=$servername;dbname=conference_db", $username, "");   
    $attendees = $_GET['attendees'];
    
    function getRms($someArray){
        $newVar = "[";
        foreach($someArray as $rows){
            $newVar = $newVar . $rows['room_number'].", "; 
        }
        $newVar = $newVar . "]";
        return $newVar;
    }
    function getComp($compArray){
        $newVar = "[";
        foreach($compArray as $rows){
            $newVar = $newVar ."'". $rows['company']."'".", ";
        }
        $newVar = $newVar . "]";
        return $newVar;
    }
    function getId(){
        global $conn;
        $maxId = "SELECT max(id) from attendees";                
        $stmt = $conn->prepare($maxId);
        $stmt->execute();
        $newId = (int)($stmt->fetchColumn(0));
        return $newId + 1;
                
    }
?>

<!DOCTYPE html>
<head>
        <link rel="stylesheet" href="Companies.css"/>
        <link rel="stylesheet" href="center.css"/>
        <link rel="stylesheet" href="./tingle/src/tingle.css"/>
        <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
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
    <div class='body-container'>
        <?php 
            $servername = "localhost";
            $databaseName = "conference_db";
            $username = "root";

            // Set up a connection to the mysql database
            try{
                
                $db = new PDO("mysql:host=$servername;dbname=$databaseName", $username);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                if ($attendees == "all"){
                    echo '<title>'. 'All Attendees'. '</title>';
                    echo"<div style='display:flex; flex-direction: row; justify-content:center; align-items:center; margin-left: 10px;'> ";
                    echo '<h1 style="margin-right: 15px; margin-bottom:20px;">'. 'All Attendees' .'</h1>';
                    echo '<img src="./assets/add_icon.png" class = "add-icon" onclick = "formModal.open()">';
                    echo"</div> ";
                    $students = "SELECT * FROM attendees where attendee_type = 'Student'";
                    $stmt = $db->prepare($students);
                    $stmt->execute();
                    $studentNames  = $stmt->fetchAll();
                    
                    $pros = "SELECT * FROM attendees where attendee_type = 'Professional'";
                    $stmt = $db->prepare($pros);
                    $stmt->execute();
                    $proNames  = $stmt->fetchAll();
                    
                    $sponsors = "SELECT * FROM attendees where attendee_type = 'Sponsor'";
                    $stmt = $db->prepare($sponsors);
                    $stmt->execute();
                    $sponsorNames  = $stmt->fetchAll();
                    echo "<div style='display:flex;flex-direction:row; justify-content: space-evenly;  width: 100%'>";
                    echo '<table>';
                    echo '<th>'.'Sponsors'.'</th>';
                    foreach($sponsorNames as $row){
                        echo "<tr>";
                        echo "<td> ".$row['first_name']." ".$row['last_name']."</td>";
                        echo "</tr>";
                    }
                    echo '</table>';
                    echo '<br>';
                    
                    echo '<table>';
                    echo '<th>'.'Professionals'.'</th>';
                    foreach($proNames as $row){
                        echo "<tr>";
                        echo "<td> ".$row['first_name']." ".$row['last_name']."</td>";
                        echo "</tr>";
                    }
                    echo '</table>';
                    echo '<br>';
                    
                    echo '<table>';
                    echo '<th>'.'Students'.'</th>';
                    foreach($studentNames as $row){
                        echo "<tr>";
                        echo "<td> ".$row['first_name']." ".$row['last_name']."</td>";
                        echo "</tr>";
                    }
                    echo '<br>';
                    echo '</table>';
                    echo "</div>";
                    $rooms = "SELECT room_number FROM rooms where spots_taken < spots";
                    $company = "SELECT company FROM companies";
                    $stmt1 = $db->prepare($rooms);
                    $stmt2 = $db->prepare($company);
                    $stmt1->execute();
                    $stmt2->execute();
                    $rmNum  = $stmt1->fetchAll();
                    $allCompanies = $stmt2->fetchAll();
                    $holdRms = getRms($rmNum);
                    $holdComp = getComp($allCompanies);
                    echo "<script> var rooms = " . $holdRms . "</script>";
                    echo "<script> var company = " . $holdComp. "</script>";
                    if (isset($_POST['submit-info'])){
                        
                        $first = $_POST['first'];
                        $last = $_POST['last'];
                        $type = $_POST['attendee_type'];
                        $id = getId();
                        $update = "INSERT INTO attendees VALUES('$first', '$last', '$type', '$id')";                        
                                            
                        $stmt = $db->prepare($update);
                        
                        $stmt->execute();
                        if ($type == "Sponsor"){
                            $company = $_POST['Company'];
                            $spons = "INSERT INTO sponsor_members VALUES('$first','$last','$id','$company')";
                            $stmt = $db->prepare($spons);
                            $stmt->execute();                                    
                        }
                        else if ($type == "Student"){                        
                            $studentRm = $_POST['Room_Number'];
                            
                            if ($studentRm != "None"){                             
                                $addStudent = "INSERT INTO students VALUES('$first', '$last', '$id', '$studentRm')";
                                $stmt = $db->prepare($addStudent);
                                echo "123";
                                $stmt->execute();    
                                echo "456";
                                $taken = "SELECT spots_taken from rooms where room_number = '$studentRm'";
                                $stmt = $db->prepare($taken);
                                $stmt->execute();
                                $taken = (int)($stmt->fetchColumn(0));   
                                $taken = 1 + $taken;
                                echo $taken;
                                $addRm = "UPDATE rooms SET spots_taken = $taken WHERE room_number = '$studentRm'";
                                $stmt = $db->prepare($addRm);
                                $stmt->execute();
                            }
                            else{             
                                $addStudent = "INSERT INTO students VALUES('$first', '$last', '$id', NULL)";
                                $stmt = $db->prepare($addStudent);
                                $stmt->execute();                    
                                
                            }
                        }
                        echo "<script> var sqlSent = true </script>";
                        

                    }
                }
                else{
                    echo '<h1>'. $attendees .'</h1>';
                    echo '<title>'. $attendees. '</title>';
                    echo '<table>';
                    echo '<th>'.'Name'.'</th>';
                    if ($attendees == "Sponsor"){                    
                        echo '<th style="padding-left: 20px;">'.'Company'.'</th>';
                        $sql = "SELECT * FROM sponsor_members ORDER BY first_name";
                        $stmt = $db->prepare($sql);
                        $stmt->execute();
                        $data  = $stmt->fetchAll();
                        foreach($data as $row){
                            echo "<tr>";
                            echo "<td> ".$row['first_name']." ".$row['last_name']."</td>";
                            echo "<td style='padding-left: 20px;'> ".$row['company']."</td>";
                            echo "</tr>";
                        }
                    }
                    else{ 
                        $sql = "SELECT * FROM attendees where attendee_type = '$attendees' ORDER BY first_name";
                        $stmt = $db->prepare($sql);
                        $stmt->execute();
                        $data  = $stmt->fetchAll();                    
                        foreach($data as $row){
                            echo "<tr>";
                            echo "<td> ".$row['first_name']." ".$row['last_name']."</td>";
                            echo "</tr>";
                        }    
                    }
                    
                    echo '</table>';
                }
            
            }
            catch(PDOException $e){
                $errorMessage = $e->getMessage();
                echo "<script>var connectionFailed = true;</script>";
            }

            $db = null;
        ?>
    </div>
</body>
</html>
<script src="./tingle/src/tingle.js"> 
</script>

<script>

var formModalContent =`
    <form method='POST' class='modal-form'>
        <div class='modal-content'> 
            <h3 style='align-self:center;'> Add Attendee</h3>
            <label> First Name </label>
            <input type="text" name="first"  />
            <label> Last Name </label>
            <input type="text" name="last"  />
            <label> Attendee </label>
            <select  name="attendee_type" onchange='changeHandler()' >                
                <option> Professional </option>
                <option> Sponsor </option>
                <option> Student </option>
             </select>
             <div class='add-something'> </div>
            <input type='submit' class='submit-info' name='submit-info' style='display:none;'>
        </div>
    </form>
`

// Form modal 
var formModal = new tingle.modal({
    footer: true,
    stickyFooter: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: "Close",
    cssClass: ['custom-class-1', 'custom-class-2']
});
formModal.setContent(formModalContent);
formModal.addFooterBtn('Submit', 'tingle-btn tingle-btn--primary', function() {
    document.querySelector('.submit-info').click();
});


var errorModal = new tingle.modal({
    footer: true,
    stickyFooter: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: "Close",
    cssClass: ['custom-class-1', 'custom-class-2'],
});
errorModal.setContent('<h1>We encountered an error connecting to the database </h1>');
errorModal.addFooterBtn('Refresh', 'tingle-btn tingle-btn--danger', function() {
    location.reload();
});

if (typeof connectionFailed !== 'undefined'){
    if(connectionFailed){
    errorModal.open()
    }
}



if(typeof sqlSent !== 'undefined'){
    if (sqlSent){
        alert('SQL sent successfully');
        window.location.href="Attendees.php?attendees=all";
     }

}
function changeHandler(){
        
    if (event.target.value == "Student"){ 
         var newHTML = `<label> Room_Number </label>
        <select name='Room_Number'><option> None </option>`
        for(var i=0; i < rooms.length ; i++){
            newHTML = newHTML + "<option>" + rooms[i] + "</option>"
        }
        newHTML = newHTML + "</select>"
        document.querySelector('.add-something').innerHTML = newHTML;
    }
    else if (event.target.value == "Sponsor"){
        var newHTML = `<label> Company </label>
        <select name='Company'>`
        for(var i=0; i < company.length ; i++){
            newHTML = newHTML + "<option>" + company[i] + "</option>"
        }
        newHTML = newHTML + "</select>"
        document.querySelector('.add-something').innerHTML = newHTML;    
    }
    else {
        document.querySelector('.add-something').innerHTML = ""
    }
}


</script>

