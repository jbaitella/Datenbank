<?php

include("database.php");

$database = new Database();
$register = [];

if (!$database->prepare_registration()) {
    $register["success"] = false;
    $register["message"] = "Registrierung nicht bereit.";
    echo json_encode($register);
    return false;
}

if (!isset($_GET['personname']) || !isset($_GET['password'])) {
    $register["success"] = false;
    $register["message"] = "Parameter fehlen.";
    echo json_encode($register);
    return false;
}

$personname = $_GET['personname'];
$password = $_GET['password'];
$nachname = null;


if (isset($_GET['nachname'])) {
    $nachname = $_GET['nachname'];
}
}

$registration = $database->register_person($personname, $password);

if ($registration) {
    $register["success"] = true;
    $register["message"] = "registration erfolgreich.";
    echo json_encode($register);
    return true;
}

$register["success"] = false;
$register["message"] = "registration nicht erfolgreich.";
echo json_encode($register);
return false;