<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1166
Released: Wed Jun 24 17:10:40 2015 GMT
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
include "/srv/athenace/lib/shared/common.php";

$ret = sendDBEmail();
// Returns:
// 1 on No mail,
// 2 on Sent a Mail Successfully
// 3 on failed sending mail, i.e. there was an error

$logContent = '';

if ($ret == 2) {
    $logContent .= 'Sent a Mail Successfully';
    $logresult = logEvent(32, $logContent);
} elseif ($ret == 3) {
    $logContent .= 'Failed Sending Mail';
    $logresult = logEvent(32, $logContent);
} else {}

function sendDBEmail()
{
    
    // This function picks up an unsent mail from the Database and sends it.
    // This is desgined to be run from CRON
    // Returns:
    // 1 on No mail,
    // 2 on Sent a Mail Successfully
    // 3 on failed sending mail, i.e. there was an error
    global $db;
    
    $owner = siteDets();
    
    require_once '/srv/athenace/lib/pub/PHPMailer-5.2.10/PHPMailerAutoload.php';
    
    $sqltext = "SELECT * FROM mail WHERE sent=? AND body<>'' LIMIT 1;";
    // rint $sqltext. "\n";
    $q = $db->select($sqltext,array(0),'i') ;
    
   if (! empty($q)) {
        $r = $q[0];
        
        $mailid = $r->mailid;
        $name = stripslashes($r->addname);
        $email = stripslashes($r->addto);
        $esubject = stripslashes($r->subject);
        $htmlBody = stripcslashes($r->body);
        $docName = $r->docname;
        $docTitle = $r->doctitle;
        
        $owner = siteDets();
        
        $mail = new PHPMailer();
        $mail->IsSMTP(); // send via SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // turn on SMTP authentication
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; // TCP port to connect to
     
        $mail->Host = $owner->emailsmtpsrv;
        $mail->Username = $owner->athenaemail; // SMTP username
        $mail->Password = $owner->athenaemailpw; // SMTP password
        $mail->From = $owner->email;
        $mail->FromName = $owner->co_name;        
        $mail->AddAddress($email, $name);        
        $mail->AddReplyTo($owner->email, $owner->co_name);    // Reply to this email ID     
        $mail->WordWrap = 50; // set word wrap
        $mail->IsHTML(true); // send as HTML
        $mail->Subject = $esubject;
        $mail->Body = $htmlBody; // HTML Body
        $htmlTextBody = 'Please see the HTML version of this mail to read the contents.';
        $mail->AltBody = $htmlTextBody; // Text Body
                                        
        // PDF Attachment
        if (file_exists($docName)) {
            $mail->AddAttachment($docName, $docTitle); // attachment
        }
        
        $email_done = '';
        if (! $mail->Send()) {
            $email_done = "Mailer Error: " . $mail->ErrorInfo;
            // Send a message to Developer to let them know something is wrong
            // passthru('../shared/sysmail.php');
            
            return 3;
        } else {            
            # Update DB
            $mailUpdate = new Mail();            
            $mailUpdate->setMailid($mailid);
            $mailUpdate->setSent(time());            
            $mailUpdate->updateDB();
            
        }
        
        return 2;
    } else {
        // No Mail
        return 1;
    }
}

