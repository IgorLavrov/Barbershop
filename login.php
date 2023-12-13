<?php
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalender', 3307);
session_start();

// kontroll kas login vorm on täidetud?
if(isset($_REQUEST['knimi']) && isset($_REQUEST['psw'])) {
    $login = htmlspecialchars($_REQUEST['knimi']);
    $pass = htmlspecialchars($_REQUEST['psw']);

    $sool = 'vagavagatekst';
    $krypt = crypt($pass, $sool);
    // kontrollime kas andmebaasis on selline kasutaja

    $stmt = $mysqli->prepare("
SELECT id, kasutaja, parool, isadmin FROM kasutajad WHERE kasutaja=?");
    $stmt->bind_param("s", $login);
    $stmt->bind_result($id, $kasutajanimi, $parool, $onadmin);
    $stmt->execute();

    if ($stmt->fetch() && $krypt == $parool) {
        $_SESSION['kasutaja'] = $login;
        if ($onadmin == 1) {
            $_SESSION['admin'] = true;
        }
        header("Location: table.php");
        $stmt->close();
        exit();
    }
    echo "kasutaja $login või parool $krypt on vale";
    $stmt->close();
}

?>
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<form  action="login.php" method="post">
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <div class="form-outline mb-4">
                                <h1>Login</h1>
                                <label class="form-label" for="knimi">Kasutajanimi</label>
                                <input type="text" placeholder="Sisesta kasutajanimi"
                                       name="knimi" id="knimi" class="form-control form-control-lg" required>
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="psw">Parool</label>
                                <input type="password" placeholder="Sisesta parool"
                                       name="psw" id="psw" class="form-control form-control-lg" required>

                            </div>


                            <button type="submit" class="btn btn-primary btn-floating mx-1">Logi sisse</button>
                        </div>
                    </div> </div> </div> </div>
    </section>

</form>