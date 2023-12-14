<?php

function build_calendar($month, $year)
{
    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalender', 3307);

    /*$stmt = $mysqli->prepare("select * from bookings where MONTH(regdate) = ? AND YEAR(regdate) = ?");
    $stmt->bind_param('ss', $month, $year);
    $bookings = array();
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                $bookings[] = $row['regdate'];
            }

            $stmt->close();
        }
    }*/

    $daysOfWeek = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date("t", $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $daysOfWeekIndex = $dateComponents['wday'];
    if ($daysOfWeekIndex == 0) {
        $daysOfWeekIndex == 0;
    } else {
        $daysOfWeekIndex = $daysOfWeekIndex - 1;
    }
    $dateToday = date('Y-m-d');
    $calendar = "<table class='table table-bordered'>";
    $calendar .= "<center><h2>$monthName $year</h2>";

    $calendar .= "<a class='btn btn-xs btn-primary' href='?month=" . date('m', mktime(0, 0, 0, $month - 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month - 1, 1, $year)) . "'>Previous Month</a>";

    $calendar .= "<a class='btn btn-xs btn-primary' href='?month=" . date('m') . "&year=" . date('Y') . "'>Current Month</a>";

    $calendar .= "<a class='btn btn-xs btn-primary' href='?month=" . date('m', mktime(0, 0, 0, $month + 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month + 1, 1, $year)) . "'>Next Month</a></center><br>";

    $calendar .= "<tr>";


    foreach ($daysOfWeek as $day) {
        $calendar .= "<th class='header'>$day</th>";
    }

    $calendar .= "</tr><tr>";

    if ($daysOfWeekIndex > 0) {
        for ($k = 0; $k < $daysOfWeekIndex; $k++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    $currentDay = 1;

    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    while ($currentDay <= $numberDays) {
        if ($daysOfWeekIndex == 7) {
            $daysOfWeekIndex = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";

        $dayname = strtolower(date('l', strtotime($date)));
        $eventNum = 0;
        $today = $date == date('Y-m-d') ? "today" : "";
        /* if($dayname=='saturday'||$dayname=='sunday'){
            $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>Holiday</button>";
        }else*/
        if ($date < date('Y-m-d')) {
            $calendar .= "<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>N/A</button>";
        } else {
            $totalbookings = checkSlots($mysqli, $date);
            if ($totalbookings == 18) {
                $calendar .= "<td class='$today'><h4>$currentDay</h4> <a href='#' class='btn btn-danger btn-xs'>All Booked</a>";
            } else {
                $availableslots=18 - $totalbookings;
                $calendar .= "<td class='$today'><h4>$currentDay</h4> <a href='book.php?date=" . $date . "' class='btn btn-success btn-xs'>Book</a><small><i>$availableslots slots left</i></small>";
            }
        }


        //$today=$date==date('Y-m-d')?"today":"";
        //if($dateToday<date('Y-m-d')){
        // $calendar.="<td><h4>$currentDay</h4><button class='btn btn-danger btn-xs'>N/A</button>";
        //}else{
        //  $calendar.="<td class='$today'><h4>$currentDay</h4><a href='book.php?date=$date' class='btn btn-success btn-xs'>Book</a>";
        //}

        $calendar .= "</td>";
        $currentDay++;
        $daysOfWeekIndex++;
    }

    if ($daysOfWeekIndex != 7) {
        $remainingDays = 7 - $daysOfWeekIndex;
        for ($i = 0; $i < $remainingDays; $i++) {
            $calendar .= "<td></td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";
    echo $calendar;
}
    function checkSlots($mysqli,$date){
        $stmt = $mysqli->prepare("select * from bookings where regdate = ?");
        $stmt->bind_param('s', $date);
        $totalbookings = 0;
        if($stmt->execute()){
         $result = $stmt->get_result();
         if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                $totalbookings ++;
            }

            $stmt->close();
        }
    }

     return $totalbookings;
    }


?>
<html>
<head>
    <meta name="viewport" content="width=device=width,initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <title>BarberShop</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            $dateComponents=getdate();

            if(isset($_GET['month']) && isset($_GET['year'])){
                $month=$_GET['month'];
                $year=$_GET['year'];
            } else {
                $month=$dateComponents['mon'];
                $year=$dateComponents['year'];
            }
            echo build_calendar($month,$year);
            ?>
        </div>
    </div>
</div>

</body>
</html>
