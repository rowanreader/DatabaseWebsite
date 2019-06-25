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
    echo "<script> var badSql = true</script>";
}

// Helper function
function splitName($fullName){
    $nameArray = preg_split("~\s~",$fullName);
    return $nameArray;
}

function timeToMinutes($time){
    $minutes = (int)date('i',strtotime($time));
    $hours = (int) date('H',strtotime($time));
    return (($hours * 60) + $minutes);
}

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="Events.css" />
    <link rel="stylesheet" href="center.css" />
    <link rel="stylesheet" href="./tingle/src/tingle.css"/>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <title> Events </title>
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
            <li class="nav-item active">
                <a class="nav-link" href="#">Events <span class="sr-only">(current)</span></a>
            </li>
            </ul>
        </div>
    </nav>
<div class='body-container' >
<h1 style='margin-bottom: 25px; margin-top:25px; '> Events </h1>
    <div class="tab-container"> 
        <form method="POST"> 
            <!-- <button class='btn btn-primary' onclick="day1Submit()"> Day 1 </button> -->
            <input type="submit"  class="day1" value="Day 1"  name="day1"/>
        </form>
        <form method="POST"> 
            <!-- <button class='btn btn-primary' onclick="console.log('hello');"> Day 2 </button> -->
            <input type="submit"  class="day2" value="Day 2" name="day2" />
        </form>
    </div>
    <!-- This is where the update sql gets sent to the database -->
    <?php 
        if (isset($_POST['event-changes'])){
            $session = $_POST['session-input'];
            $session_day = $_POST['day-input'];
            $start_t =date("H:i:s" , strtotime($_POST['start-time-input']));
            $end_t = date("H:i:s" , strtotime($_POST['end-time-input']));
            $speaker_first = splitName($_POST['name-input'])[0];
            $room = $_POST['room-input'];
            
            $startMinutes = timeToMinutes($start_t);
            $conflict = false;

            $stmt = $db->prepare("SELECT * from sessions where session_day='$session_day' and session !='$session'"); 
            $stmt ->execute();
            $allTimes = $stmt->fetchAll();
            $conflict = false; 
            foreach($allTimes as $rows){
                if ( (timeToMinutes($rows['start_t']) <= $startMinutes)  and ( $startMinutes < timeToMinutes($rows['end_t'])) and ($room == $rows['room'])){
                    $conflict = true;
                    break;
                }
            }


            // if size of array is 1, there is only 1 name 
            // sizeof(splitName($_POST['name-input'])== 1  or 
            if( ($conflict == true) or sizeof(splitName($_POST['name-input'])) == 1 ) {
                echo "<script> var badSql = true </script> ";
            }

            
            else{
                $speaker_last = splitName($_POST['name-input'])[1];
                $editSql = "UPDATE sessions SET  session='$session', session_day='$session_day', start_t='$start_t', 
                end_t='$end_t', speaker_first='$speaker_first',speaker_last='$speaker_last', room='$room' WHERE session='$session'";
                $stmt2 = $db->prepare($editSql);
                try{
                    $stmt2->execute();
                }
                catch(PDOException $e){
                    echo "<script> var badSql = true</script>";
                }
            }
        }
    ?>
    <table class='table' >
    <thead class='thead-light'> 
        <th> Speaker </th>
        <th> Session Name</th>
        <th> Start Time </th>
        <th> End Time </th>
        <th> Room </th>
    </thead>
    <?php 
        $databaseName = "conference_db";
        $username = "root";
        $servername = "localhost";
        $sql = "SELECT * FROM SESSIONS";
        // if either day1 or day2 was selected
        if (isset($_POST["day2"]) ){ 
            $sql = $sql. " WHERE session_day='Day 2'";
        }
        else{
            $sql = $sql. " WHERE session_day='Day 1'";
        }
        $stmt = $db->prepare($sql);
        $stmt->execute(); 
        $data = $stmt->fetchAll();
        $eventID = 0;
        foreach($data as $row){
            echo "<tr>";
            echo "<td><span id='speaker$eventID'>". $row['speaker_first']. " " . $row['speaker_last'] . "</span></td>";
            echo "<td> <span id='event$eventID'>" . $row['session']."</span> <a onclick='editHandler($eventID)'><img src='./assets/edit_icon.png' style='height:15%;margin-left:10px;'/> </a></td>";
            echo "<td><span id='startTime$eventID'> " . $row['start_t'] ."</span></td>";
            echo "<td><span id='endTime$eventID'> " . $row['end_t'] ."</span></td>";
            echo "<td><span id='room$eventID'> " . $row['room'] ."</span></td>";
            echo "</tr>";
            $eventID++;
        };
    ?>
    </table>
</div>
</body>

<script src="./tingle/src/tingle.js"></script>


<script>
var errorModal = new tingle.modal({
    footer: true,
    stickyFooter: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: "Close",
    cssClass: ['custom-class-1', 'custom-class-2'],
});
errorModal.setContent('<h2>The information entered was incorrect. The SQL was not sent. Please try again </h2>');
errorModal.addFooterBtn('Got it', 'tingle-btn tingle-btn--danger', function() {
    errorModal.close();
});


if (typeof badSql !== 'undefined'){
    if(badSql){
        errorModal.open();
    }
}

let modalContent = `
                <form class="modal-form" name='modal-form' method="POST" action='Events.php'  > 
                <div class='modal-content'>
                        <h3 style='align-self:center;'> Edit Event</h3>
                        <label > Speaker Name (First and Last) </label>
                        <input type="text" name="name-input" id='name-editor' />
                        <label> Session </label>
                        <input type="text" id="session-editor" name="session-input" />
                        <label> Day </label>
                        <select name='day-input' value="Day 1"> 
                            <option>Day 1</option>
                            <option>Day 2</option>
                        </select>
                        <label> Start Time </label>
                        <input type="text" name="start-time-input"  id='start-time-editor' />
                        <label> End Time </label>
                        <input type="text"  name="end-time-input" id='end-time-editor'/>
                        <label> Room </label>
                        <input type="text"  name="room-input" id='room-editor'/>
                        <input type=submit name="event-changes" class='submit-form' style='display:none;'/>
                </div>
                </form>
                `;
var formModal = new tingle.modal({
    footer: true,
    stickyFooter: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: "Close",
    cssClass: ['custom-class-1', 'custom-class-2'],
    onOpen: function() {
        console.log('modal open');
    },
    onClose: function() {
        console.log('modal closed');
    },
    beforeClose: function() {
        return true; // close the modal
    }
});

formModal.addFooterBtn('Submit', 'tingle-btn tingle-btn--primary', function() {
    document.querySelector('.submit-form').click();
});

formModal.setContent(modalContent);

// Edit handler opens the modal and prepopulates it with the current values 
const editHandler = (eventID) =>{
    var nameEditor = document.querySelector('#name-editor');
    var sessionEditor = document.querySelector('#session-editor');
    var startTimeEditor = document.querySelector('#start-time-editor');
    var endTimeEditor = document.querySelector('#end-time-editor');
    var roomEditor = document.querySelector('#room-editor')
    formModal.open();
    sessionEditor.value = document.querySelector("#event" + eventID).innerHTML;
    nameEditor.value=document.querySelector("#speaker" + eventID).innerHTML;
    startTimeEditor.value=document.querySelector("#startTime" + eventID).innerHTML;
    endTimeEditor.value=document.querySelector("#endTime" + eventID).innerHTML;
    roomEditor.value=document.querySelector("#room" + eventID).innerHTML;
    roomEditor.value= roomEditor.value.trim();
    startTimeEditor = startTimeEditor.value.trim();
    endTimeEditor = endTimeEditor.value.trim();

}

</script> 
</html>
