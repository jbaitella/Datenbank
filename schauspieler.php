<?php

$conn->close();

include("database.php");

$database = new Database();

if (!$database->prepare_schauspieler()) {
    return false;
}

return true;

