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
$pagetitle = "Contacts";
$navtitle = 'Contacts';
$keywords = '';
$description = '';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

if (! is_numeric($_GET['id'])) {
    header("Location: /staff/?id=notFound");
    exit();
}

$sqltext = "SELECT * FROM contacts,address WHERE contacts.addsid=address.addsid AND contactsid=?";
// print "<br>$sqltext";
$q = $db->select($sqltext, array($_GET['id']), 'i');
$r = $q[0];

$addsid = $r->addsid;

if (isset($_GET['remove']) && $_GET['remove'] == "y" && isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    db_delete("contacts", $_GET['id'], 'contactsid');
    
    header("Location: /contacts/");
    exit();
}

if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    // Add to Address table
    $addsid = db_updateAddress($_POST, $addsid);
    
    $contactsUpdate = new Contacts();
    // Update DB
    $contactsUpdate->setContactsid($_GET['id']);
    $contactsUpdate->setFname($_POST['fname']);
    $contactsUpdate->setSname($_POST['sname']);
    $contactsUpdate->setCo_name($_POST['co_name']);
    $contactsUpdate->setRole($_POST['role']);
    $contactsUpdate->setCustid($_POST['custid']);
    $contactsUpdate->setSuppid($_POST['suppid']);
    $contactsUpdate->setNotes($_POST['notes']);    
    $contactsUpdate->updateDB();
    
    header("Location: /contacts/?Updated=" . $result['id']);
    exit();
}

$pagetitle = "Edit contact";

include "../tmpl/header.php";

if ((isset($_GET['SentAccessEmail'])) && ($_GET['SentAccessEmail'] == 1)) {
    
    ?>
<div class="panel panel-success">
	<div class="panel-heading">Sent an Email</div>
	<div class="panel-body">
  
	An email has been sent to
	<?php echo $r->fname . ' ' . $r->sname . ' (' . $r->email. ')';?>
	with access details for the Athena Control Panel
  
  </div>
</div>
<?php
}

?>

<script>
<!--
function confirmSubmit()
{
var agree=confirm("Are you sure you wish to delete this Contact?");
if (agree)
	return true ;
else
	return false ;
}
// -->
</script>
<br>
<span id=pageactions> <span style="font-size: 90%; color: #999;">For this Contact:- </span> <?php
if ((isset($r->email)) && ($r->email != '') && (((isset($r->custid)) && ($r->custid != '')) || ((isset($r->suppid)) && ($r->suppid != '')))) 

{
    ?> <a href="/mail/contact.access?cid=<?php echo $_GET['id'];?>" title="Send Athena Access Details">Send Athena Access
		Details</a> <?php
}
if ($r->fname != 'System') {
    ?> | <a href="?id=<?php echo $_GET['id']?>&amp;remove=y" title="Remove this Contact" class="cancel"
	onclick="return confirmSubmit()">Delete This Contact</a> <?php
}
?>
</span>

<h1>Edit Contact</h1>

<form role="form" action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $_GET['id']?>&amp;go=y"
	enctype="multipart/form-data" method="post">



	<fieldset>

	

			<?php

html_text("First Name", "fname", $r->fname, 'required');

html_text("Surname", "sname", $r->sname);

html_text("Company Name", "co_name", $r->co_name);

customer_select("Customer", "custid", $r->custid);

supplier_select("Supplier", "suppid", $r->suppid);

html_text("Role", "role", $r->role);

html_textarea("Notes", "notes", $r->notes, "notes");

include "/srv/athenace/lib/shared/adds.edit.form.php";

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
