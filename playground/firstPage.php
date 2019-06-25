<!DOCTYPE html>


<?php
    function echoName(){
        if(isset($_GET['nameInput'])){
            echo $_GET["nameInput"];
        };
    }; 
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="./homepage.css">
    <title> Test Page </title>
</head>
<body>
    <h1> Simple page</h1>
    <p class="styledPHP">
        <?php 
            $firstVar = 23;
            echo "The first variable I've ever declared: $firstVar " ;
        ?>
    </p>
    <form method="GET">  
        <p> Name </p>
        <input type="text" name="nameInput"/>
        <input type="submit"/>

    </form>
    <button id="dynamicButton"> php generator </button>
    <p id="dynamicText"></p>


    <select id="dropdown" onchange = "changeHandler();" >
        <option value="volvo">Volvo</option>
        <option value="saab">Saab</option>
        <option value="mercedes">Mercedes</option>
        <option value="audi">Audi</option>
    </select>   
</body>
</html>

<?php 
    echoName()
?>

<!-- adding php as innerHTML works -->
<script> 
    document.querySelector('#dynamicButton').addEventListener("click", () => {
        document.querySelector("#dynamicText").innerHTML = "<?php echo "hello from php"; ?>"
    });


    var phpCommand = 'echo "Hello from php"'; 
    var phpHack = "<?php ?>";
    // phpHack = phpHack.replace("?>", "");

    changeHandler = (e) =>{
        console.log(phpHack);
        var chosen =document.querySelector('#dropdown').value;
        document.querySelector('#dynamicText').innerHTML = phpHack;
    };
  
</script> 
