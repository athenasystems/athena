<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1176
Released: Wed Jun 24 19:38:48 2015 GMT
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
include "/srv/athenace/lib/shared/functions_form.php";
include "/srv/athenace/lib/staff/common.php";

$pagetitle = "Home";
$navtitle = 'Your Password';
$description = '';
$keywords = '';
$pagescript = array();
$pagestyle = array();

$sqltext = "SELECT usr,pw,seclev from pwd where staffid=?";
// print "<br/>$sqltext";
$q = $db->select($sqltext, array($staffid), 'i');
$rrt = $q[0];

$errors = array();
$pwhelp = '';
$oldpwwrong = '';
if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    if ((! isset($_POST['npw1'])) || (! isset($_POST['npw2']))) {
        $pwhelp = 'Please type your new password twice';
        $errors[] = 'npw1';
    } elseif (strlen($_POST['npw1']) < 7) {
        $pwhelp = 'New password is too short';
        $errors[] = 'npw1';
    } elseif (! chkLowercase($_POST['npw1'])) {
        $pwhelp = 'No lower case letters in password';
        $errors[] = 'npw1';
    } elseif (! chkUppercase($_POST['npw1'])) {
        $pwhelp = 'No upper case letters in password';
        $errors[] = 'npw1';
    } elseif (! chkDigit($_POST['npw1'])) {
        $pwhelp = 'No numbers in password';
        $errors[] = 'npw1';
    } elseif ($_POST['npw1'] != $_POST['npw2']) {
        $pwhelp = 'New passwords are not the same';
        $errors[] = 'npw1';
    }
        
    if (empty($errors)) {
       
        $newPwd = mkPwd($_POST['npw1']);
        
        $pwdid = getPwdID($staffid);
        // Update DB
        $pwdUpdate = new Pwd();
        $pwdUpdate->setPwdid($pwdid);
        $pwdUpdate->setPw($newPwd);
        $pwdUpdate->updateDB();
        
        $logresult = logEvent(33, $logContent);
        $done = 1;
        
        $token = base64_encode(encrypt($rrt->usr."|".$_POST['npw1']));
        
        header("Location: /pass.php?t=$token");
    }
}

$pagetitle = "staff";
$pagescript = array();
$pagestyle = array();

include "tmpl/header.php";

?>

<ol>
	<li id=subtitle><h3>Your Login Details</h3></li>

	<li><label>Your Username</label> <span style="font-size: 110%; font-weight: bold;"><?php echo $rrt->usr?> </span></li>
</ol>

<ol>
	<li id=subtitle><h3>Password</h3></li>
	<li>Password must contain at least ...</li>

	<li style="list-style: disc;">A minimum of 7 Characters</li>
	<li style="list-style: disc;">One lower case letter</li>
	<li style="list-style: disc;">One upper case letter</li>
	<li style="list-style: disc;">One number</li>


</ol>
<?php

if ($pwhelp != '') {
    echo 'There is a problem with the new password: ' . $pwhelp;
}

?>

<form role="form" class="form-signin pull-left"
	action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $staffid?>&amp;go=y" enctype="multipart/form-data"
	method="post">

	<fieldset>		
		<label for="npw1" class="sr-only">Current Password</label>
		<input id="npw1" name="npw1" class="form-control" placeholder="New Password" required type="password">
		<label for="npw2" class="sr-only">Current Password</label>
		<input id="npw2" name="npw2" class="form-control" placeholder="Confirm New Password" required type="password">
		<button class="btn btn-lg btn-success btn-block" type="submit">Save New Password</button>
	</fieldset>

</form>

<?php
include "tmpl/footer.php";
?>
