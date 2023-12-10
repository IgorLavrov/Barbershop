<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalender', 3307);
session_start();
$stmt = $mysqli->prepare("select name, email, specialist, regdate,timeslot from bookings ");


$stmt->bind_result($name, $email,  $specialist, $date,$timeslot);
$stmt->execute();


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

        <th scope="col">Name</th>
        <th scope="col"><a href="?sort=nimetus">Email</th>
        <th scope="col"><a href="?sort=nimetus">Specialist</th>
        <th scope="col"><a href="?sort=nimetus">Data</th>
        <th scope="col">Time</th>


    </tr>
    <?php
    while($stmt->fetch()){



        echo " 
 <tr> 
 <td>$name</td> 
 <td>$email</td> 
 <td>$specialist</td> 
<td>$date</td> 
<td>$timeslot</td> 
 </tr> 
 ";
    }
    ?>
</table>
</body>
</html>