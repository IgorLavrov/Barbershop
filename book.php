<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalender', 3307);
// Check for connection errors
if ($mysqli->connect_error) {
    die('Connection Error: ' . $mysqli->connect_error);
}
if(isset($_GET['date'])){
    $date = $_GET['date'];
    $stmt = $mysqli->prepare("select * from bookings where regdate = ?");
    $stmt->bind_param('s', $date);
    $bookings = array();
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                $bookings[] = $row['timeslot'];
            }
            $stmt->close();
        }
    }
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $timeslot= $_POST['timeslot'];
    $specialist=$_POST['specialist'];
    $date = $_GET['date'];  // Retrieve the date from the URL

    $stmt = $mysqli->prepare("select * from bookings where regdate = ? and timeslot = ? ");
    $stmt->bind_param('ss', $date,$timeslot);
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            $msg = "<div class='alert alert-danger'>Already booked: " . $stmt->error . "</div>";
        }else{

            $stmt = $mysqli->prepare("INSERT INTO bookings (name, timeslot,email, regdate,specialist) VALUES (?, ?, ?, ?,?)");

            // Check for statement preparation errors
            if (!$stmt) {
                die('Prepare Error: ' . $mysqli->error);
            }

            // Bind parameters
            $stmt->bind_param('sssss', $name, $timeslot,$email, $date,$specialist);

            // Execute the statement
            if ($stmt->execute()) {
                $msg = "<div class='alert alert-success'>Booking Successful</div>";
            } else {
                $msg = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
            }
            $bookings[]=$timeslot;

            $stmt->close();
            $mysqli->close();
        }
    }



}
$duration=20;
$cleanup=10;
$start="10:00";
$end="19:00";


function timeslots($duration,$cleanup,$start,$end){
    $start = new DateTime($start);
    $end= new DateTime($end);
    $interval= new DateInterval("PT".$duration."M");
    $cleanupInterval=new  DateInterval("PT".$cleanup."M");
    $slot=array();
    for($initStart=$start;$initStart<$end;$initStart->add($interval)->add($cleanupInterval)){
        $endPeriod=clone $initStart;
        $endPeriod->add($interval);
        if($endPeriod>$end){
            break;
        }
        $slots[]=$initStart->format("H:iA")."-".$endPeriod->format("H:iA");
    }
    return $slots;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <title></title>
</head>
<body>
<div class='container'>
    <h1 class="text-center">Book for Date:<?php  echo date('d/m/Y',strtotime($date));?></h1><hr>
    <div class='row'>
        <div class="col-md-12">
            <?php echo isset($msg)?$msg:"";?>
        </div>
       <?php $timeslots=timeslots($duration,$cleanup,$start,$end);
       foreach ($timeslots as $ts){
       ?>

        <div class="col-md-2">
            <div class="form-group">
                <?php if(in_array($ts,$bookings)){ ?>
                    <button class="btn btn-danger "><?php echo $ts;?></button>
                <?php }else{ ?>
                <button class="btn btn-success book" data-timeslot="<?php echo $ts;?>"><?php echo $ts;?></button>

                <?php } ?>

            </div>
</div>
    <?php }?>
    </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Booking: <span id="slot"></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="">
                                    Timeslot
                                </label>
                                <input required type="text" readonly name="timeslot" id="timeslot" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">
                                    Name
                                </label>
                                <input required type="text" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">
                                    Email
                                </label>
                                <input required type="email" name="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">
                                    Specialist
                                </label>
                                <SELECT multiple name="specialist" class="form-control input-sm">
                                    <option value='Anastasia Mironova'>Anastasia Mironova</option>
                                    <option value='Vladimir Jakovenko'>Vladimir Jakovenko</option>
                                    <option value='Liza Luis'>Liza Luis</option>
                                    <option value='Any'>Any</option>
                                </select>
                            </div>
                            <div class="form-group pull-right">
                                <button class="btn btn-primary" type="submit" name="submit">Submit</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<div class="text-center">
    <a href="table.php" class="btn btn-primary"> Check your appointments</a>
</div>
<script>

    $(".book").click(function(){
        var timeslot=$(this).attr('data-timeslot');
        $("#slot").html(timeslot);
        $("#timeslot").val(timeslot);
        $("#myModal").modal("show");
    })
</script>
</body>

</html>
