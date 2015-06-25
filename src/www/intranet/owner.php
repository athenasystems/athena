<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1178
Released: Thu Jun 25 10:53:50 2015 GMT
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

// Include Intranet Global Variables and Functions
include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

$pagetitle = 'Company Details';
$navtitle = 'Home';
$keywords = '';
$description = '';

$addsid = 100;

$fields = array(
    'co_name',
    'co_no',
    'vat_no',
    'colour',
    'athenaemail',
    'athenaemailpw',
    'emailsmtpsrv'
);

foreach ($fields as $field) {
    if (! isset($_POST[$field])) {
        $_POST[$field] = '';
    }
}

// Check if we have Form Data to process
if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    $logContent = '';
    // Update Address table
    $addsid = db_updateAddress($_POST, 100);
    
	# Update DB
	$ownerUpdate = new Owner();

	$ownerUpdate->setOwnerid(100);
	$ownerUpdate->setCo_name($_POST['co_name']);
	$ownerUpdate->setVat_no($_POST['vat_no']);
	$ownerUpdate->setCo_no($_POST['co_no']);
	$ownerUpdate->setAthenaemail($_POST['athenaemail']);
	$ownerUpdate->setAthenaemailpw($_POST['athenaemailpw']);
	$ownerUpdate->setEmailsmtpsrv($_POST['emailsmtpsrv']);

	$res = $ownerUpdate->updateDB();
    
    header("Location: index.php?updateOwner=" . $res);
    exit();
}

// Define elements for the HTML Header include
$pagetitle = "Edit Your Company Details";

include "tmpl/header.php";

$owner = siteDets();

?>

<h1>Edit Your Company Details</h1>

<form role="form" action="<?php echo $_SERVER['PHP_SELF']?>?go=y"
	enctype="multipart/form-data" method="post" name="editcust">

	<fieldset>

		<h3>Company Name</h3>
			
<?php

// id, co_name, contact, add1, add2, add3, city, county, postcode, tel, fax, email

html_text("Company Name *", "co_name", $owner->co_name,'required');

html_text("Company Vat Number", "vat_no", $owner->vat_no);

html_text("Company Number", "co_no", $owner->co_no);

include "/srv/athenace/lib/shared/adds.edit.form.php";
?>
<div>
			<h3>Web Mail Details for sending Athena Mail</h3>
			(e.g. Google Gmail, Yahoo Mail etc)
		</div>
	
<?php
html_text("Athena Email Address", "athenaemail", $owner->athenaemail);

html_pw("Athena Email Password", "athenaemailpw", $owner->athenaemailpw);

html_text("Athena Email SMTP Server (e.g. smtp.gmail.com)", "emailsmtpsrv", $owner->emailsmtpsrv);
?>

	</fieldset>

	<fieldset class="buttons"><?php
html_button("Save changes");
?> 

</fieldset>

</form>

<?php
include "tmpl/footer.php";
?>
