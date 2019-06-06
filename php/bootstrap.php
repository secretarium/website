<?php

/* EXCEPTIONS */
function exceptionHandler($exception) {
    echo "Uncaught exception: " . $exception->getMessage();
}
if(true) set_exception_handler('exceptionHandler');

/* MESSAGES */
function toJson($o) {
    return empty($o) ? "{}": json_encode($o, \JSON_UNESCAPED_UNICODE);
}
function successObj($o) {
	return array("success" => true, "data" => $o);
}
function errorObj($message = "") {
	return array("success" => false, "message" => $message);
}
function returnJson($o) {
	header("Content-Type: application/json; charset=utf-8");
	echo toJson($o);
	exit();
}
function returnSuccess($o) {
	returnJson(successObj($o));
}
function returnError($message = "") {
	returnJson(errorObj($message));
}
function return404($message = "404 Not Found") {
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
	header("Status: " . $message);
	$_SERVER['REDIRECT_STATUS'] = 404;
	exit();
}

/* UTILS */
function checkArray($data, $name) {
    return is_array($data) && isset($data[$name]) && is_array($data[$name]);
}
function checkBool($data, $name) {
    return is_array($data) && isset($data[$name]) && ($data[$name] == true);
}
function checkCoords($data, $name) {
    return checkArray($data, $name) && checkDouble($data[$name], 0) && checkDouble($data[$name], 1);
}
function checkDouble($data, $id) {
    return is_array($data) && isset($data[$id]) && is_numeric($data[$id]);
}
function checkEmail($email, &$error) {
	if(IsStringNullOrEmpty($email)) { $error = "Invalid email address"; return false; }
	if(preg_match('/\s/', $email) == 1 || preg_match('/^.+@.+\..+$/', $email) != 1) { $error = "Invalid email address"; return false; }
    return true;
}
function checkExists($o, $name) {
	return is_array($o) && (isset($o[$name]) || !is_null($o[$name]));
}
function checkString($o, $name, $minlength = 1, $maxlength = 0) {
	return checkExists($o, $name) && strlen($o[$name]) >= $minlength && ($maxlength == 0 || strlen($o[$name]) <= $maxlength);
}
function isInSubAssocArray($arr, $name, $value) {
    foreach($arr as $sub) {
        if(isset($sub[$name]) && $sub[$name] === $value)
            return true;
    }
    return false;
}
function isStringNullOrEmpty($str) {
	return !is_string($str) || strlen($str) == 0;
}
function generateHash($length = 20) {
	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$hash = '';
	for ($i = 0; $i < $length; $i++) {
		$hash .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $hash;
}
function generateToken($len) {
    return substr(str_replace(array('+','/','=','.'), array('a','b','c','d'), base64_encode(mcrypt_create_iv($len, \MCRYPT_DEV_URANDOM))), 0, $len);
}
function arrayToJson($data, $name, $default = null) {
    if(!checkArray($data, $name))
        return $default;

    $json = json_encode($data[$name], JSON_UNESCAPED_UNICODE);
    return $json === false ? $default : $json;
}
function intStringToBool($s) {
    return $s == true;
}
function jsonToArray($s) {
    if(empty($s))
        return array();
    return json_decode($s, true);
}
function getLastElement($array) {
    if(!is_array($array)) return null;
    $l = count($array);
    if($l == 0) return null;
    return $array[$l - 1];
}

?>