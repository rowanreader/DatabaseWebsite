<?php
    $servername = "localhost";
    $username = "root";
    $conn = new PDO("mysql:host=$servername;dbname=conference_db", $username, "");
    $student="select count(id) as num from attendees where attendee_type = 'Student'";
    $pro = "select count(id) as num from attendees where attendee_type = 'Professional'";
    $sponsor="select count(id) as num from attendees where attendee_type = 'Sponsor'";
    try {
        $stmt=$conn->prepare($student);
        $stmt->execute();
        $numStudents=$stmt->fetchColumn(0);
        $stmt=$conn->prepare($pro);
        $stmt->execute();
        $numPros=$stmt->fetchColumn(0);
        $stmt=$conn->prepare($sponsor);
        $stmt->execute();
        $numSponsors=$stmt->fetchColumn(0);
    }
    catch(Exception $e){
        echo "Connection failed: " . $e->getMessage();
        die;
    }
?>

<!DOCTYPE html>
<head>
        <title> Home </title>
        <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="./center.css">
</head>


<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">CISC 332 Conference</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
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
    <div>
    <h1 style='margin-bottom: 25px; margin-top:25px;' > Welcome </h1>

<?php
    echo "<a name='all' onclick='this.form.submit()' href=Attendees.php?attendees=all>All attendees</a>";
    echo "<br>";
    
    echo "Number of <a name='students' onclick='this.form.submit()' href=Attendees.php?attendees=Student>students</a> attending: ";
    echo $numStudents."<br>";
    
    echo "Number of <a name='pros' onclick='this.form.submit()' href=Attendees.php?attendees=Professional>professionals</a> attending: ";
    echo $numPros."<br>";
    
    echo "Number of <a name='sponsors' onclick='this.form.submit()' href=Attendees.php?attendees=Sponsor>sponsors</a> attending: ";
    echo $numSponsors."<br>";

    $platinumSQL = "select count(company) as num from companies where sponsor_rank = 'Platinum'";    
    $stmt=$conn->prepare($platinumSQL);
    $stmt->execute();
    $platinum=(int)($stmt->fetchColumn(0));
    
    $goldSQL = "select count(company) as num from companies where sponsor_rank = 'Gold'";
    $stmt=$conn->prepare($goldSQL);
    $stmt->execute();
    $gold=(int)($stmt->fetchColumn(0));
    
    $silverSQL = "select count(company) as num from companies where sponsor_rank = 'Silver'";
    $stmt=$conn->prepare($silverSQL);
    $stmt->execute();
    $silver=(int)($stmt->fetchColumn(0));
    
    $bronzeSQL = "select count(company) as num from companies where sponsor_rank = 'Bronze'";
    $stmt=$conn->prepare($bronzeSQL);
    $stmt->execute();
    $bronze=(int)($stmt->fetchColumn(0));
    
    echo '</br>';
    $sponsorSum = ($platinum * 10000) + ($gold * 5000) + ($silver * 3000) + ($bronze * 1000);
    
    $studentSQL = "select count(id) as num from attendees where attendee_type = 'Student'";
    $stmt=$conn->prepare($studentSQL);
    $stmt->execute();
    $studentNum=(int)($stmt->fetchColumn(0));
    
    $proSQL = "select count(id) as num from attendees where attendee_type = 'Professional'";
    $stmt=$conn->prepare($proSQL);
    $stmt->execute();
    $proNum=(int)($stmt->fetchColumn(0));
    
    echo '</br>';
    $attendeeSum = ($studentNum * 50) + ($proNum * 100);
    
    $fundingSum = $sponsorSum + $attendeeSum;
    echo "Total funding: $". $fundingSum.'.00'.'<br>';
    echo "Total funding from sponsors: $". $sponsorSum.'.00'.'<br>';
    echo "Total funding from attendees: $". $attendeeSum.'.00'.'<br>';

?>
    </div>
    </div>

</body>
</html>
