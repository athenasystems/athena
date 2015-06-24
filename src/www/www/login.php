<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1172
Released: Wed Jun 24 17:40:52 2015 GMT
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
$pagetitle = 'Sign In Page';
$navtitle = 'Sign In';
$keywords = '';
$description = '';

include "/srv/athenace/lib/shared/common.php";

include "tmpl/header.php";

$status = '<div class="alert alert-info" role="alert">Please enter your Username and Password to proceed</div>';

if ((isset($_GET['pf'])) && ($_GET['pf'] == 'y')) {
    $status = '<div class="alert alert-danger" role="alert">Oppss ... Username and Password not accepted</div>';
}
if ((isset($_GET['pwch'])) && ($_GET['pwch'] == 'y')) {
    $status = '<div class="alert alert-success" role="alert">Your Password has been changed. Please log in.</div>';
}

if ((isset($_GET['go'])) && ($_GET['go'] == 'y')) {
    $user = $_POST['user'];
    $pw = $_POST['pw'];
    
    $site = '';
    
    $sqltext = "SELECT staffid,contactsid,usr,seclev FROM pwd WHERE usr=? LIMIT 1";
   
    $q = $db->select($sqltext,array($user),'s') ;
    $row = $q[0];
    # print $row->seclev;exit;
    if ((isset($row->staffid)) && ($row->staffid > 1) && ($row->seclev == 1)) { // Management log in
        $site = 'intranet';
    } elseif ((isset($row->staffid)) && ($row->staffid > 1) && ($row->seclev > 1)) { // Staff log in
        $site = 'staff';
    } elseif (isset($row->contactsid) && ($row->contactsid > 0)) { 
        
       $destination = getIfCustOrSupp($row->contactsid);
        
        // Customer log in
        $site = $destination;
    }  else {
        failOut('No site to go to');
    }
    
    $token = base64_encode(encrypt("$user|$pw"));
    
    header("Location: https://$site.$domain/pass.php?t=$token");
}

?>

<h2 class="form-signin-heading">Athena Sign In</h2>
<?php echo $status;?>

<form role="form" class="form-signin" action="<?php echo $_SERVER['PHP_SELF']?>?go=y"
	method="post" enctype="application/x-www-form-urlencoded">

	<label for="inputEmail" class="sr-only">Username</label> <input
		type="text" id="user" name="user" class="form-control"
		placeholder="Username" required autofocus> <label for="inputPassword"
		class="sr-only">Password</label> <input type="password" id="pw"
		name="pw" class="form-control" placeholder="Password" required>
	<button class="btn btn-lg btn-success btn-block" type="submit">Sign in</button>
	<br> <a href="resetpass.php">Forgot Password?</a>
</form>

<?php

include "tmpl/footer.php";

?>