<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalender', 3307);
session_start();
$stmt = $mysqli->prepare("select name, email, specialist, regdate,timeslot from bookings ");

$stmt->bind_result($name, $email,  $specialist, $date,$timeslot);
$stmt->execute();

function kysiKaupadeAndmed($sorttulp="specialist", $otsisona=""){

    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalender', 3307);

    $lubatudtulbad=array("name", "email","specialist","date","timeslot");
    if(!in_array($sorttulp, $lubatudtulbad)){
        return "lubamatu tulp";
    }
    if (isset($_REQUEST["sortsuund"])) {
        $sortsuund = $_REQUEST['sortsuund'];
    } else {
        $sortsuund = 'ASC';
    }
    $sortsuund = strtoupper($sortsuund);

    if (isset($_REQUEST["otsisona"])) {
        $otsisona = $_REQUEST["otsisona"];
    } else {
        $otsisona = "";
    }

    $otsisona=addslashes(stripslashes($otsisona));
    $stmt = $mysqli->prepare("select name, email, specialist, regdate,timeslot from bookings where
(name LIKE '%$otsisona%' OR specialist LIKE '%$otsisona%')  ORDER BY $sorttulp $sortsuund");
    //echo $yhendus->error;
    $stmt->bind_result($name, $email,  $specialist, $date,$timeslot);
    $stmt->execute();
    $hoidla=array();
    while($stmt->fetch()){
        $item=new stdClass();
        $item->name=$name;
        $item->email=htmlspecialchars($email);
        $item->specialist=htmlspecialchars($specialist);
        $item->date=htmlspecialchars($date);
        $item->tiemslot=$timeslot;
        array_push($hoidla, $item);
    }
    return $hoidla;
}

$sorttulp="nimetus";
$otsisona="";
if(isSet($_REQUEST["sort"])){
    $sorttulp=$_REQUEST["sort"];
}
if(isSet($_REQUEST["otsisona"])){
    $otsisona=$_REQUEST["otsisona"];
}
$inimesed=kysiKaupadeAndmed($sorttulp, $otsisona);
?>
<!doctype html>
<html>
<head>
    <title>Andmetabel</title>
</head>
<header>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

</header>
<body>
<h1 class="text-center" >Booking</h1>

<h2>Search</h2>
<form method="get" action="table.php">
    Otsi: <input type="text" name="otsisona" value=" " />
    <input type="hidden" name="action" value="search">
    <input type="submit" value="Otsi">
</form>

<table class="table">
    <tr>

        <th scope="col"><a href="?sort=name">Name</th>
        <th scope="col"><a href="?sort=email">Email</th>
        <th scope="col"><a href="?sort=specialist">Specialist</th>
        <th scope="col"><a href="?sort=date">Date</th>
        <th scope="col">Time</th>
    </tr>

 <?php

 // while($stmt->fetch()){
 //       echo "
 //<tr>
 //<td>$name</td>
 //<td>$email</td>
 //<td>$specialist</td>
//<td>$date</td>
//<td>$timeslot</td>
// </tr>
 //";
 //   }

 ?>
    <?php foreach($inimesed as $item): ?>
        <tr>
                <td><?=$item->name ?></td>
                <td><?=$item->email ?></td>
                <td><?=$item->specialist ?></td>
                <td><?=$item->date ?></td>
                <td><?=$item->timeslot ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</table>
</body>
</html>