<?php

require(__DIR__ . "/../../php/bootstrap.php");

// Check request
if($_POST == null || !is_array($_POST) || empty($_POST)) {
    $_POST = json_decode(file_get_contents('php://input'), true);
    if($_POST == null || !is_array($_POST) || empty($_POST)) {
        returnError("Invalid request");
    }
}
if(!isset($_POST["type"]))
    returnError("Invalid request type");

$type = explode(".", $_POST["type"])[0];

switch ($type) {

    #region  User services

    case "user":

        require_once(__DIR__ . "/../../services/users.php");

        switch ($_POST["type"]) {

            case "user.request-docs":
                requestDocs($_POST["data"]);
                break;
        }
        break;

    #endregion
}

?>