<?php
$SendTo = "lippert@gmail.com";
$Subject = "Bollard Builder";
$Headers = "From: Bollard Builder <bollardbuilder@wikk.com>\r\n";

$Headers .= 'MIME-Version: 1.0' . "\r\n";
$Headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

$Message = $_POST['message'];

mail($SendTo, $Subject, $Message, $Headers);
?>