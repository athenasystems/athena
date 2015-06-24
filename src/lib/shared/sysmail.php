<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1175
Released: Wed Jun 24 19:15:43 2015 GMT
The MIT License (MIT)

Copyright (c) 2015 Web Modules Ltd. UK

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*/
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include "common.php";

$srvEmail = 'athenaServer@'.$domain;

// The message
$message = "Houston ... we have a problem ...\n";
$subject = 'Athena Problem';
// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap ( $message, 70, "\r\n" );
$headers   = array();
$headers[] = "MIME-Version: 1.0";
$headers[] = "Content-type: text/plain; charset=iso-8859-1";
$headers[] = "From: Athena Server <$srvEmail>";
$headers[] = "Reply-To: Athena Server <$srvEmail>";
$headers[] = "Subject: {$subject}";
$headers[] = "X-Mailer: PHP/".phpversion();
$headers = 'From: athenaServer@'.$domain . "\r\n" . 
'Reply-To: athenaServer@'.$domain . "\r\n" . 
'X-Mailer: PHP/' . phpversion ();
// Send
mail ( $webmasterEmail,$subject , $message, $headers );
?>
