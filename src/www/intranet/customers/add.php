<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1163
Released: Wed Jun 24 17:07:27 2015 GMT
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
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

$pagetitle = "Add a New Customer";
$navtitle = 'Customers';
$keywords = '';
$description = '';

$fields = array(
    "co_name",
    "inv_email"
);

foreach ($fields as $field) {
    if (! isset($_POST[$field])) {
        $_POST[$field] = '';
    }
}
if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    $logContent = "";
    
    // Add to Address table
    $addsid = db_addAddress($_POST);
    
	# Insert into DB
	$customerNew = new Customer();
	$customerNew->setCo_name($_POST['co_name']);
	$customerNew->setAddsid($addsid);

	$result['id']=$customerNew->insertIntoDB();
    
    $logresult = logEvent(13, $logContent);
    header("Location: /contacts/add.php?custid=" . $result['id'] . '&FromAddCustomer=Indeed');
    
    exit();
}

include "../tmpl/header.php";


?>

<h1>Add a New Customer</h1>

<form role="form" action="<?php echo $_SERVER['PHP_SELF']?>?go=y"
	enctype="multipart/form-data" method="post">


	<h3>Company Details</h3>
	<fieldset>

			<?php

html_text("Company Name *", "co_name", $_POST['co_name']);

include "/srv/athenace/lib/shared/adds.add.form.php";

?>

	</fieldset>

	<fieldset class="buttons">

		<?php
html_button("Save changes");
?>

		or <a href="/customers/" class="cancel" title="Cancel">Cancel</a>

	</fieldset>

</form>

<?php
include "../tmpl/footer.php";
?>
