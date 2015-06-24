<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1166
Released: Wed Jun 24 17:10:40 2015 GMT
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

$fields = array("custid",'price','content',"notes");

foreach ($fields as $field) {
    if (! isset($_POST[$field])) {
        $_POST[$field] = '';
    }
}

// Check if we have Form Data to process
if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    $logContent = "\n";
    
    // Insert into DB
    $invoicesNew = new Invoices();
    $invoicesNew->setCustid($_POST['custid']);
    $invoicesNew->setIncept(time());
    $invoicesNew->setContent($_POST['content']);
    $invoicesNew->setPrice($_POST['price']);
    $invoicesNew->setNotes($_POST['notes']);
    $invoicesNew->insertIntoDB();
    
    $logresult = logEvent(4, $logContent);
    
    header("Location: /invoices/?highlight=" . $result['id']);
    exit();
}

// Define elements for the HTML Header include
$pagetitle = "Create An Invoice";
$pagescript = array('/pub/js/jquery.js');
$pagestyle = array();

include "../tmpl/header.php";

?>

<h1><?php echo $pagetitle; ?></h1>

<form role="form" action="<?php echo $_SERVER['PHP_SELF']?>?go=y&id=<?php echo $_GET['id']?>"
	enctype="multipart/form-data" method="post" id=searchform>


	<fieldset>
		

			<?php
if (! isset($_POST['custid'])) {
    $_POST['custid'] = 0;
}
if (isset($_GET['id'])) {
    $_POST['custid'] = $_GET['id'];
}
if (! isset($_POST['price'])) {
    $_POST['price'] = '';
}
if (! isset($_POST['content'])) {
    $_POST['content'] = '';
}
if (! isset($_POST['notes'])) {
    $_POST['notes'] = '';
}
customer_invoice_select("Customer", "custid", $_POST['custid'], 0, 1);

html_text("Price", "price", $_POST['price']);

html_textarea("Invoice Description *", "content", $_POST['content'], "body", 'required');

html_textarea("Internal Notes<br>(not seen by Customer)", "notes", $_POST['notes'], "notes");

?>

	</fieldset>

	<fieldset class="buttons">

		<?php
html_button("Add Invoice");
?>

		or <a href="javascript:history.go(-1);" class="cancel" title="Cancel">Cancel</a>

	</fieldset>

</form>
<br>
<br>

<?php
include "../tmpl/footer.php";
?>
