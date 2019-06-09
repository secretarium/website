<?php

require_once(__DIR__ . "/../php/email.php");

function requestDocs($data) {

    if(!checkString($data, "email"))
        returnError("invalid email");
    if(!checkEmail($data["email"], $error))
        returnError($error);
    if(!checkString($data, "interest", 20))
        returnError("please provide a summary of your interest (min 50 chars)");
    if(!checkArray($data, "documents") && checkString($data, "documents"))
        $data["documents"] = [$data["documents"]];
    if(!checkArray($data, "documents") || count($data["documents"]) == 0)
        returnError("please select at least one document");

    $emailTxt = "\nDocuments Request\n\n" .
        "From: " . $data["email"] . "\n" .
        "Interest: " . $data["interest"] . "\n" .
        "Documents:\n    " . implode("\n    ", $data["documents"]) . "\n\n";
    $email = (new Email())
        ->setSubject("Secretarium - Documents request")
        ->setTo("contact@secretarium.org")
        ->setBodyText($emailTxt);

    if(!$email->send()) {
        returnError("failed to register the request");
    }

    returnSuccess("successfully sent");
}

?>