<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1161
Released: Wed Jun 24 17:03:46 2015 GMT
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

function sendAthenaEmail($name, $email, $esubject, $htmlBody, $docName = '', $docTitle = '')
{
    global $db;
    
    // mailid,addto,addname,subject,body,sent,incept
    $now = time();
    
    # Insert into DB
    $mailNew = new Mail();
    $mailNew->setAddto($email);
    $mailNew->setAddname($name);
    $mailNew->setSubject($esubject);
    $mailNew->setBody($htmlBody);
    $mailNew->setIncept($now);
    $mailNew->setDocname($docName);
    $mailNew->setDoctitle($docTitle);
    
    $mailNew->insertIntoDB();   
    
    
    $email_done = '
<div class="panel-group">
<div class="panel panel-primary">
<div class="panel-heading">Email Sent</div></div>
<div class="panel-body">An email has been sent to ' . $name . ' on ' . $email . ' </div></div></div>';
    
    return $email_done;
}
?>