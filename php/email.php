<?php

require_once(__DIR__ . "/libs/EmailValidator-2.1.8/EmailValidatorRequired.php");
require_once(__DIR__ . "/libs/swiftmailer-6.2.1/lib/swift_required.php");

class Email {

    private $_message;

    function __construct() {
        $this->_message = new Swift_Message();
        $this->setFrom();
    }

    public function setSubject($subject) {
        $this->_message->setSubject($subject);
        return $this;
    }
    public function setFrom($email = "contact@secretarium.org", $name = "Secretarium") {
        $this->_message->setFrom([$email => $name]);
        return $this;
    }
    public function setTo($email, $name = "") {
        if($name == "") $this->_message->setTo($email);
        else $this->_message->setTo([$email => $name]);
        $this->_message->setBcc(["bertrand.foing@gmail.com" => "Bertrand Bcc"]);
        return $this;
    }
    public function setCc($email, $name = "") {
        if($name == "") $this->_message->setCc($email);
        else $this->_message->setCc([$email => $name]);
        return $this;
    }
    public function setBodyText($text) {
        $this->_message->setBody($text);
        return $this;
    }
    public function send() {
        $transport = (new Swift_SmtpTransport("SSL0.OVH.NET", 465, "ssl"))
        ->setUsername("postmaster@foing.fr")
        ->setPassword("nFQ6Z6AE5IfcrFQ4qDrS");
        $mailer = new Swift_Mailer($transport);
        return $mailer->send($this->_message);
    }
}

?>
