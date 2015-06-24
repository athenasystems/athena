<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1162
Released: Wed Jun 24 17:05:47 2015 GMT
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
$pagetitle = "Staff Login";
$navtitle = 'Staff';
$keywords = '';
$description = '';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

if(!is_numeric($_GET['id'])){
    header("Location: /staff/?id=notFound");
    exit;
}
// if (($seclevel>3) && ($_GET['id']!=$staffid)){
// header("Location: /staff/");
// exit;
// }

if (($seclevel > 1) && ($staffid < 2)) {
    header("Location: /staff/");
    exit();
}

$sqltext = "SELECT usr from pwd where staffid=?";
$q = $db->select($sqltext,array($_GET['id']),'i') ;
$rrt = $q[0];

$pw_errors = array();
$pwhelp = '';

$fields = array(
    "npw1",
    "npw2",
    'opw'
);
foreach ($fields as $field) {
    if (! isset($_POST[$field])) {
        $_POST[$field] = '';
    }
}

if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    if ((! isset($_POST['npw1'])) || (! isset($_POST['npw2']))) {
        $pwhelp = 'Please type your new password twice';
        $pw_errors[] = 'npw1';
    } elseif (strlen($_POST['npw1']) < 7) {
        $pwhelp = 'New password is too short';
        $pw_errors[] = 'npw1';
    } elseif (! chkLowercase($_POST['npw1'])) {
        $pwhelp = 'No lower case letters in password';
        $pw_errors[] = 'npw1';
    } elseif (! chkUppercase($_POST['npw1'])) {
        $pwhelp = 'No upper case letters in password';
        $pw_errors[] = 'npw1';
    } elseif (! chkDigit($_POST['npw1'])) {
        $pwhelp = 'No numbers in password';
        $pw_errors[] = 'npw1';
    } elseif ($_POST['npw1'] != $_POST['npw2']) {
        $pwhelp = 'New passwords are not the same';
        $pw_errors[] = 'npw1';
    }
    
    $stfid = $_POST['stfid'];
    
    if (empty($pw_errors)) {    	
    	
        $cryptPwd = mkPwd($_POST['npw1']);
        
    	# Update DB
    	$pwdUpdate = new Pwd();
    	
    	$pwdUpdate->setUsr($rrt->usr);
    	$pwdUpdate->setPw($cryptPwd);    	
    	$result = $pwdUpdate->updateDB();
                
        $logresult = logEvent(33, $logContent);
        $done = 1;
    }
}

$pagetitle = "staff";

include "../tmpl/header.php";
?>

<h1>Staff Log In</h1>
<?php
if ((isset($done)) && ($done)) {
    echo '<h2 style="color:red;margin-top:40px;margin-left:150px;">The password has been changed</h2>';
} else {
    
    if ($pwhelp != '') {
        echo '<li style="margin-left:40px;color:red;font-size:100%;">There is a problem with the new password: ' . $pwhelp . '</li>';
    }
    
    ?>
<h2>Password must contain at least ...</h2>
<ul style="margin-left: 40px;">
	<li>A minimum of 7 Characters</li>
	<li style="list-style: disc;">One lower case letter</li>
	<li style="list-style: disc;">One upper case letter</li>
	<li style="list-style: disc;">One number</li>
</ul>

<form role="form" class="form-signin pull-left"
	action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $_GET['id']?>&amp;go=y"
	enctype="multipart/form-data" method="post">


	<fieldset>

		Log in Username: <?php echo $rrt->usr?>
			
		
			<?php
    html_hidden('usr', $rrt->usr);
    html_hidden('stfid', $_GET['id']);
    if ($seclevel > 1) {
        if ($oldpwwrong) {
            echo '<li>' . $oldpwwrong . '</li>';
        }
        ?>
        <label for="opw" class="sr-only">Current Password</label><input
			id="opw" name="opw" class="form-control"
			placeholder="Current Password" required type="password">
        <?php
    }
    
    ?>	
	<label for="npw1" class="sr-only">Current Password</label><input
			id="npw1" name="npw1" class="form-control" placeholder="New Password"
			required type="password"> <label for="npw2" class="sr-only">Current
			Password</label><input id="npw2" name="npw2" class="form-control"
			placeholder="Confirm New Password" required type="password">
		<button class="btn btn-lg btn-success btn-block" type="submit">Save
			New Password</button>

	</fieldset>


</form>

<?php
}

include "../tmpl/footer.php";
?>
