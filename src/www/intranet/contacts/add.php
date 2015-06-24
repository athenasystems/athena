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
$pagetitle = "Add a New Contact";
$navtitle = 'Contacts';
$keywords = '';
$description = '';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

$fields = array("fname","sname","co_name","role","notes","custid","suppid");

foreach ($fields as $field) {
    if (! isset($_POST[$field])) {
        $_POST[$field] = '';
    }
}

if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    $logContent = "\n";
    $pw = generatePassword();
    
    // Add to Address table
    $addsid = db_addAddress($_POST);
    
    $logon = generateContactlogon($_POST['fname'], $_POST['sname']);
    
    // Insert into DB
    $contactsNew = new Contacts();    
    $contactsNew->setFname($_POST['fname']);
    $contactsNew->setSname($_POST['sname']);
    $contactsNew->setCo_name($_POST['co_name']);
    $contactsNew->setRole($_POST['role']);
    $contactsNew->setCustid($_POST['custid']);
    $contactsNew->setSuppid($_POST['suppid']);
    $contactsNew->setAddsid($addsid);
    $contactsNew->setNotes($_POST['notes']);
    $contactId = $contactsNew->insertIntoDB();
        
    // Insert into DB
    $pwdNew = new Pwd();
    $pwdNew->setUsr($logon);
    $pwdNew->setContactsid($contactId);
    $pwdNew->setSeclev(100);
    $pwdNew->setPw(mkPwd($pw));
    
    file_put_contents('/etc/athenace/pwd', "{$_POST['custid']}\t{$_POST['suppid']}\t$logon\t$pw\n", FILE_APPEND);
    
    // Dont add to the Password table unless they have a custid or a suppid
    if (((isset($_POST['custid'])) && ($_POST['custid'] > 0)) || ((isset($_POST['suppid'])) && ($_POST['suppid'] > 0))) {        
        $pwdNew->insertIntoDB();
    } else {
        // Not adding to passwd table
        // i.e contacts not associated with a customer or supplier cant log in
    }
    
    $logresult = logEvent(6, $logContent);
    
    header("Location: /contacts/?Added=" . $result['id']);
    exit();
}

include "../tmpl/header.php";

if (isset($_GET['FromAddCustomer'])) {
    ?>

<div id=help>Your new Customer has been saved. You can now add a contact for this Customer</div>

<?php
}
?>
<h1>Add a New Contact</h1>

<form role="form" action="<?php echo $_SERVER['PHP_SELF']?>?go=y" enctype="multipart/form-data" method="post">



	<fieldset>

		<h3>Personal Details</h3>

			<?php

html_text("First Name *", "fname", $_POST['fname']);

html_text("Surname", "sname", $_POST['sname']);

?>

<h3>Company Details</h3>
<?php

html_text("Company Name", "co_name", $_POST['co_name']);

if (! isset($_POST['custid'])) {
    $_POST['custid'] = $_GET['custid'];
}
customer_select("Or", "custid", $_POST['custid']);

if (! isset($_POST['suppid'])) {
    $_POST['suppid'] = $_GET['suppid'];
}
supplier_select("Or", "suppid", $_POST['suppid']);

html_text("Role", "role", $_POST['role']);

include "/srv/athenace/lib/shared/adds.add.form.php";

html_textarea("Notes", "notes", $_POST['notes'], "body");

?>

	</fieldset>

	<fieldset class="buttons">

		<?php
html_button("Save changes");
?>

		or <a href="/contacts/" class="cancel" title="Cancel">Cancel</a>

	</fieldset>

</form>

<?php
include "../tmpl/footer.php";
?>
