<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1179
Released: Mon Jun 29 09:29:29 2015 GMT
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
$pagetitle = "Suppliers";
$navtitle = 'Suppliers';
$keywords = '';
$description ='';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

if(!is_numeric($_GET['id'])){
    header("Location: /suppliers/?id=notFound");
    exit;
}

$sqltext = "SELECT * FROM supplier WHERE suppid=?";
// print "<br>$sqltext";
$q = $db->select($sqltext,array($_GET['id']),'i') ;
$r = $q[0];

$addsid = $r->addsid;

if (isset($_GET['remove']) && $_GET['remove'] == "y" && isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    // db_delete("supplier", $_GET['id'],'supplierid');
    
    header("Location: /suppliers/");
    exit();
}

// Check if we have Form Data to process
if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    // Update Address table
    $addsid = db_updateAddress($_POST, $addsid);
    

	# Update DB
	$supplierUpdate = new Supplier();

	$supplierUpdate->setSuppid($_GET['id']);
	$supplierUpdate->setCo_name($_POST['co_name']);
	$supplierUpdate->setContact($_POST['contact']);
	$supplierUpdate->setColour($_POST['colour']);
	$supplierUpdate->updateDB();
    
    header("Location: /suppliers/");
    
    exit();
}

// Define elements for the HTML Header include
$pagetitle = "Edit supplier";
$pagescript = array(
    "/js/picker.js"
);
$pagestyle = array();

include "../tmpl/header.php";

?>

<h1>Edit Supplier</h1>

<form role="form"
	action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $_GET['id']?>&amp;go=y"
	enctype="multipart/form-data" method="post" name="editsupp">


	<fieldset>

		<h3>Company Details</h3><?php

html_text("Company Name", "co_name", $r->co_name);

if (! isset($r->inv_contact)) {
    $r->inv_contact = '';
}
suppliercontact_select("Invoice Contact", "contactsid", $r->inv_contact, $_GET['id']);

?>
			
	<div class="form-group">
			<label for="colour">Colour&nbsp; <span style="width:10px;background-color:<?php echo $r->colour?>;float:right;margin-right:5px;">&nbsp;</span>&nbsp;
			</label> <input type="text" name="colour"
				value="<?php echo $r->colour?>"> <a
				href="javascript:TCP.popup(document.forms['editsupp'].elements['colour'])">
				<img width="15" height="13" border="0"
				alt="Click Here to Pick up the color" src="/img/sel.gif">
			</a>
		</div>

			<?php

include "/srv/athenace/lib/shared/adds.edit.form.php";

?>
		
	</fieldset>

	<fieldset class="buttons">
		<?php
html_button("Save changes");
?>
		or <a href="/suppliers/" class="cancel" title="Cancel">Cancel</a>

	</fieldset>

</form>
<br clear="all">
<br>
<hr>
<h3>Delete</h3>
<span> <a href="?id=<?php echo $_GET['id']?>&amp;remove=y"
	title="Remove this item" class="cancel">Delete Supplier</a>
</span>
<?php
include "../tmpl/footer.php";
?>
