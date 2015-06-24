<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1168
Released: Wed Jun 24 17:28:24 2015 GMT
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

$pagetitle = "Add Staff";
$navtitle = 'Staff';
$keywords = '';
$description ='';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

$done = 0;

$fields = array(
    "sname",
    "fname",
    'jobtitle'
);
foreach ($fields as $field) {
    if (! isset($_POST[$field])) {
        $_POST[$field] = '';
    }
}
if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    $logContent = "";
    $pw = generatePassword();
    
    // Add to Address table
    $addsid = db_addAddress($_POST);
    
    # Insert into DB
    $staffNew = new Staff();
    $staffNew->setFname($_POST['fname']);
    $staffNew->setSname($_POST['sname']);
    $staffNew->setAddsid($addsid);
    $staffNew->setJobtitle($_POST['jobtitle']);
    
    $stfid = $staffNew->insertIntoDB();
    
    $usr = generateStafflogon($_POST['fname'], $_POST['sname']);
    $staffPwd = mkPwd($pw);
    
    # Insert Pwd into DB
    $pwdNew = new Pwd();
    $pwdNew->setUsr($usr);
    $pwdNew->setStaffid($stfid);
    $pwdNew->setPw($staffPwd);
    
    $pwdNew->insertIntoDB();
    

    file_put_contents('/etc/athenace/pwd', "Staff\t$stfid\t$usr\t$pw\n", FILE_APPEND);
    
    
    $logresult = logEvent(15, $logContent);
        
    $done = 1;
}

include "../tmpl/header.php";


if ($done) {
    
    ?>
<h1>New staff member has been added</h1>
<h2>Write down the password now. It cannot be found anywhere else.</h2>
Username:
<?php echo $usr?>
<br>
Password:
<?php echo $pw?>

<?php
} else {
    
    ?>

<h1>Add a new member of Staff</h1>

<form role="form" action="<?php echo $_SERVER['PHP_SELF']?>?go=y"
	enctype="multipart/form-data" method="post">



	<fieldset>

			<?php
    
    echo '<h3>Personal Details</h3>';
    
    html_text("First Name *", "fname", $_POST['fname']);
    
    html_text("Second Name *", "sname", $_POST['sname']);
    
    html_text("Job Title", "jobtitle", $_POST['jobtitle']);
    
    include "/srv/athenace/lib/shared/adds.add.form.php";
    
    ?>

	</fieldset>

	<fieldset class="buttons">

		<?php
    html_button("Save changes");
    ?>

		or <a href="/staff/" class="cancel" title="Cancel">Cancel</a>

	</fieldset>

</form>

<?php
}
include "../tmpl/footer.php";
?>
