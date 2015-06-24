<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1170
Released: Wed Jun 24 17:33:12 2015 GMT
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
$pagetitle = "Invoices";
$navtitle = 'Invoices';
$keywords = '';
$description = '';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

if (! is_numeric($_GET['id'])) {
    header("Location: /invoices/?id=notFound");
    exit();
}

// Check if we have Form Data to process
if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    $_POST['incept'] = mktime(0, 0, 0, $_POST['incept']['month'], $_POST['incept']['day'], $_POST['incept']['year']);
    
    $logContent = "\n";
    
    // Update DB
    $invoicesUpdate = new Invoices();
    
    $invoicesUpdate->setInvoicesid($_GET['id']);
    $invoicesUpdate->setCustid($_POST['custid']);
    $invoicesUpdate->setIncept($_POST['incept']);
    $invoicesUpdate->setContent($_POST['content']);
    $invoicesUpdate->setPrice($_POST['price']);
    $invoicesUpdate->setNotes($_POST['notes']);
    
    $invoicesUpdate->updateDB();
    
    $logresult = logEvent(5, $logContent);
    
    header("Location: /invoices/?Edited=" . $_GET['id']);
    exit();
}

// Define elements for the HTML Header include
$pagetitle = "Edit Invoice";
$pagescript = array("/pub/calpop/calendar_eu.js");
$pagestyle = array("/css/calendar.css");

include "../tmpl/header.php";

$sqltext = "SELECT * FROM invoices WHERE invoicesid=?";
$q = $db->select($sqltext, array($_GET['id']), 'i');
if (! empty($q)) {
    $r = $q[0];
} else {
    header("Location: /invoices/");
    exit();
}

?>

<h1>Edit Invoice</h1>

<span> <a href="delete.php?id=<?php echo $_GET['id']?>" title="Remove this item">Delete Invoice</a>
</span>


<form role="form" action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $_GET['id']?>&amp;go=y"
	enctype="multipart/form-data" method="post">
	<h3>Invoice No: <?php echo $r->invoicesid?></h3>
	<fieldset>

<?php
if (! $r->incept) {
    $r->incept = time();
}
$value = date("Y-m-d", $r->incept);
html_dateselect("Date", "incept", $value);

customer_select("Customer", "custid", $r->custid, 0, 'required');

html_textarea("Invoice Description *", "content", $r->content, "body", 'required');

html_text("Price *", "price", $r->price, 'required');

html_textarea("Notes", "notes", $r->notes, "notes");
?>

</fieldset>

	<fieldset class="buttons"><?php
html_button("Save changes");
?> or <a href="/invoices/" class="cancel" title="Cancel">Cancel</a>

	</fieldset>

</form>

<script type="text/javascript">
<!--
$(document).ready(function() {
    // Use Glyphicons icons
    $(form).formValidation({
        icon: {
            required: 'glyphicon glyphicon-asterisk',
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        }
    });

 
});
//-->
</script>

<?php
include "../tmpl/footer.php";
?>
