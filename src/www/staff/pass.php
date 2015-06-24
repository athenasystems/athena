<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1171
Released: Wed Jun 24 17:40:21 2015 GMT
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

if ((isset($_GET['pg'])) && ($_GET['pg'] == 'logout')) {
    $loggedin = chkCookie();
    logEvent("30", $loggedin);
    killCookie();
    header("Location: $www_url");
    exit();
}

$token = base64_decode($_GET['t']);
$parts = preg_split('/\|/', decrypt($token));

$usr = $parts[0];
$pw = $parts[1];

// $staffid = pass ( $usr, $pw, 'staff' );

if ((! isset($_POST['pt'])) || ($_POST['pt'] == '')) {
    $staffid = pass($usr, $pw, 'staff');
} else {
    $cke = base64_decode($_POST['pt']);
    $keywords = preg_split("/\./", $cke);
    if ($keywords[4] == 'ATHENASECCHK') {
        $sid = $keywords[0];
        $staffid = $keywords[1];
        $usr = $keywords[2];
        $pw = $keywords[3];
        
        // echo "$staffid, $usr, $pw";
        // exit();
    }
}

if ($staffid > - 1) {
    
    dropCookie($staffid, $usr, $pw);
    
    // logEvent("26",$staffid,"Username:".$user);
    
    // Update DB
    $staffUpdate = new Staff();
    $staffUpdate->setStaffid($staffid);
    $staffUpdate->setLastlogin($_POST['lastlogin']);    
    $staffUpdate->updateDB();
    
    header("Location: $staff_url");
} else {
    
    killCookie();
    
    // logEvent("31",0,"Username:".$user);
    
    header("Location: $login_url/?pf=y");
}

?>