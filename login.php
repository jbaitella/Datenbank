<?php

require_once("database.php");

$database = new Database();
$login = [];

if (!$database->prepare_login()) {
    $login["success"] = false;
    $login["message"] = "Login nicht bereit.";
    echo json_encode($login);
    return false;
}

if (!isset($_GET['personname']) || !isset($_GET['password'])) {
    $login["success"] = false;
    $login["message"] = "Parameter fehlen.";
    echo json_encode($login);
    return false;
}

$personname = $_GET['personname'];
$password = $_GET['password'];

$logged_in = $database->login_person($personname, $password);

if ($logged_in) {
    $login["success"] = true;
    $login["message"] = "login erfolgreich.";
    $login["person"] = $logged_in;
    echo json_encode($login);
    return false;
}

$login["success"] = false;
$login["message"] = "login nicht erfolgreich. personname oder Passwort sind falsch.";
echo json_encode($login);
return false;