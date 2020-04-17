<?php
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    if(preg_match("/\.secretarium\.(com|dev|org)$/", $http_origin)) {
        header("Access-Control-Allow-Origin: $http_origin");
    }
	header('Content-type:application/json;charset=utf-8');
?>
{
  "firstname": {
    "display": "First name",
    "description": "The person's first name",
    "type": "string"
  },
  "lastname": {
    "display": "Last name",
    "description": "The person's last name",
    "type": "string"
  },
  "personalRecords": {
    "email": {
      "display": "Email",
      "description": "The person's email address",
      "type": "string"
    },
    "phone": {
      "display": "Phone",
      "description": "The person's phone number",
      "type": "string"
    }
  }
}