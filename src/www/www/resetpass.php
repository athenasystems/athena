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
$pagetitle = 'Forgotten Password Page';
$navtitle = 'Home';
$keywords = '';
$description ='';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/www/common.php";
include "/srv/athenace/lib/shared/functions_email.php";

$status = '<div class="alert alert-info" role="alert">Fill in your email address and we will send you a link to reset your password</div>';

if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    $sqltext = "SELECT fname,sname FROM athenace.address,staff 
        WHERE email=? AND address.addsid=staff.addsid LIMIT 1";
    $q = $db->select($sqltext,array($_POST['email']),'s') ;
    $r = $q[0];
    
    $found = 0;
    
   if (( isset($r->fname)) || ( isset($r->sname))) {
        // Is a staff contact - send out Email with reset link
        
        $ret = sendResetPass($r->fname . ' ' . $r->sname, $_POST['email']);
        $status = '<div class="alert alert-success" role="alert">We have sent you an email with instructions to reset your password</div>';
        $found = 1;
    } else {
        // Not a staff contact -> could be a customer or supplier
        
        $sqltext = "SELECT fname,sname,custid,suppid FROM athenace.address,contacts 
            WHERE email=? AND address.addsid=contacts.addsid";
        
        $qq = $db->select($sqltext,array($_POST['email']),'i') ;
        $rr = $qq[0];
        
        if ((! isset($rr->fname)) || (! isset($rr->sname))) {
            
            if (isset($rr->custid) && $r->custid > 0) {
                // It's a customer - send out Email with reset link
                $ret = sendResetPass($rr->fname . ' ' . $rr->sname, $_POST['email']);
                $status = '<div class="alert alert-success" role="alert">We have sent you an email with instructions to reset your password</div>';
                $found = 1;
            } elseif (isset($r->suppid) && $r->suppid > 0) {
                // It's a supplier - send out Email with reset link
                $ret = sendResetPass($rr->fname . ' ' . $rr->sname, $_POST['email']);
                $status = '<div class="alert alert-success" role="alert">We have sent you an email with instructions to reset your password</div>';
                $found = 1;
            }
        }
    }
    if (! $found) {
        $status = '<div class="alert alert-danger" role="alert">That email is not is our records. Please check you typed the right address.</div>';
    }

}

if (isset($_GET['oldreq']) && ($_GET['oldreq'] == 'y')) {
    $status = '<div class="alert alert-danger" role="alert">That password reset request is more than an hour old. Please try again</div>';
}

include "tmpl/header.php";

?>

<h2 class="form-signin-heading">Reset Password</h2>

<?php echo $status;?>

<form role="form" class="form-signin"
	action="<?php echo $_SERVER['PHP_SELF']?>?go=y" method="post"
	enctype="application/x-www-form-urlencoded">




	<label for="inputPassword" class="sr-only">Email Address</label> <input
		type="email" class="form-control" id="email" name="email"
		placeholder="example@domain.com" value="" required>

	<button class="btn btn-lg btn-success btn-block" type="submit">Reset
		Password</button>
</form>
<?php

include "tmpl/footer.php";
?>