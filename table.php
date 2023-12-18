<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalender', 3307);
session_start();
if (!isset($_SESSION["error"])) {
    $_SESSION["error"] = "";
}
if (!isset($_SESSION["admin"])) {
    $_SESSION["admin"] = false;
}
$stmt = $mysqli->prepare("select name, email, specialist, regdate,timeslot from bookings ");
$stmt->bind_result($name, $email,  $specialist, $date,$timeslot);
$stmt->execute();

function kysiKaupadeAndmed($sorttulp, $otsisona)
{
    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalender', 3307);

    $lubatudtulbad = array("name", "email", "specialist", "regdate", "timeslot");
    if (!in_array($sorttulp, $lubatudtulbad)) {
        return "lubamatu tulp";
    }

    $sortsuund = isset($_REQUEST["sortsuund"]) ? strtoupper($_REQUEST["sortsuund"]) : 'ASC';

    $otsisona = '%' . $otsisona . '%';

    $stmt = $mysqli->prepare("SELECT id,name, email, specialist, regdate, timeslot FROM bookings WHERE name LIKE ? OR specialist LIKE ? ORDER BY $sorttulp $sortsuund");
    $stmt->bind_param("ss", $otsisona, $otsisona);
    $stmt->execute();

    $stmt->bind_result($id,$name, $email, $specialist, $date, $timeslot);
    $stmt->execute();

    if ($stmt->error) {
        return "SQL Error: " . $stmt->error;
    }

    $hoidla = array();
    while ($stmt->fetch()) {
        $item = new stdClass();
        $item->id = $id;
        $item->name = $name;
        $item->email = htmlspecialchars($email);
        $item->specialist = htmlspecialchars($specialist);
        $item->date = $date;
        $item->timeslot = $timeslot;
        array_push($hoidla, $item);
    }

    return $hoidla;
}

function kustutaKaup($item_id){
    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalender', 3307);
    $stmt=$mysqli->prepare("DELETE FROM bookings WHERE id=?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
}

if (isset($_POST["delete"]) && isset($_POST["kustutusid"])) {
    kustutaKaup($_POST["kustutusid"]);
    // Redirect or refresh the page as needed

    header("Location: NewApp.php");
    exit();

}

$sorttulp="name";
$otsisona="";

if(isSet($_REQUEST["sort"])){
    $sorttulp=$_REQUEST["sort"];
}

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
<nav id="navbar" class="navbar navbar-inverse" data-spy="affix" data-offset-top="197">

    <div class="container">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-left">
                <li class="active">
                    <a href="#">Home
                        <span class="sr-only">(current)</span>
                    </a>
                </li>

                <li>
                    <a href="table.php">Appointment</a>
                </li>
                <li>
                    <a href="info.html">About Us</a>
                </li>
            </ul>
            <!-- <form class="navbar-form navbar-right col-sm-4">
                <input id="search-input" type="text" class="form-control" placeholder="Search">
                <button id="search-button" type="submit" class="btn btn-danger">Submit</button>
            </form> -->
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div  class="text-center">

<?php
    if(isset($_SESSION['kasutaja'])){
        ?>
        <h3>Hi, <?="$_SESSION[kasutaja]"?></h3>
        <a href="logout.php" class="black-link">Log out</a>
        <?php
    } else {
        ?>
        <h3>HI you need to login to see this page</h3>
        <button><a href="login.php" class="black-link">Log in</a></button>
        <?php
    }
    ?>
            </div>
        </div>
    </div>
</div>
<body>
<h1 class="text-center" >Booking</h1>
<?php
if (isset($_SESSION["kasutaja"])) {
    ?>
    <div class="container">
<h2>Search</h2>
    <form method="get" action="table.php" onsubmit="trimSearchInput()">
        Otsi: <input type="text" name="otsisona" id="otsisona" value="<?= isset($_GET['otsisona']) ? htmlspecialchars($_GET['otsisona']) : '' ?>" />
        <input type="hidden" name="action" value="search">
        <input type="submit" value="Otsi">
    </form>
    <?php

    if(isSet($_REQUEST["otsisona"])){
        $otsisona=$_REQUEST["otsisona"];
    }
    $inimesed=kysiKaupadeAndmed($sorttulp, $otsisona);
    ?>

<br>
<table   class="table table-bordered">
    <tr>
        <th scope="col">Changes</th>
        <th scope="col"><a href="?sort=name" class="black-link">Name</th>
        <th scope="col"><a href="?sort=email" class="black-link">Email</th>
        <th scope="col"><a href="?sort=specialist" class="black-link">Specialist</th>
        <th scope="col"><a href="?sort=regdate" class="black-link">Date</th>
        <th scope="col">Time</th>
    </tr>
    </div>

    <?php foreach($inimesed as $item): ?>
        <form method="post" action="table.php">
            <tr>
                <td>
                    <input type="submit" name="delete" class="btn btn-danger" value="Delete" />
                    <input type="hidden" name="kustutusid" value="<?=$item->id ?>" />
                </td>
                <td><?=$item->name ?></td>
                <td><?=$item->email ?></td>
                <td><?=$item->specialist ?></td>
                <td><?=$item->date ?></td>
                <td><?=$item->timeslot ?></td>
            </tr>
        </form>
    <?php endforeach; ?>
</table>
<?php
    }
    ?>
<script>
   // JavaScript snippet to clear any leading/trailing spaces from the search input when the form is submitted. This should ensure consistent behavior:
    function trimSearchInput() {
        var searchInput = document.getElementById('otsisona');
        searchInput.value = searchInput.value.trim();
    }
</script>
</body>
</html>