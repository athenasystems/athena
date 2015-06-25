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

$pagetitle = "Invoices";
$navtitle = 'Invoices';
$keywords = '';
$description = '';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

if(!is_numeric($_GET['id'])){
    header("Location: /invoices/?id=notFound");
    exit;
}

if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    $invoicesDelete = new Invoices();
    $invoicesDelete->setInvoicesid($_GET['id']);
    $invoicesDelete->deleteFromDB();
    
    header("Location: /invoices/?ItemDeleted=y");
    
    exit();
}
include "../tmpl/header.php";
?>
<?php

$invoices = new Invoices();
// Load DB data into object
$invoices->setInvoicesid($_GET['id']);
$invoices->loadInvoices();
$all = $invoices->getAll();

if (isset($all)) {
    ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<strong>Viewing <?php echo $invoices->getInvoicesid();?></strong>
	</div>
	<div class="panel-body">
		<?php
    
    foreach ($all as $key => $value) {
        if (isset($value) && ($value != '')) {
            ?>
		    <dl class="dl-horizontal">
			<dt><?php echo $key;?></dt>
			<dd><?php echo $value;?></dd>
		</dl>
		    <?php
        }
    }
    
    ?>
	</div>
</div>
<?php
} else {
    ?>
<h2>No results found</h2>
<?php
}
?><form role="form" action="<?php echo $_SERVER['PHP_SELF']?>?go=y&amp;id=<?php echo $_GET['id']; ?>"
	enctype="multipart/form-data" method="post">
	<h2>Please confirm you wish to delete this item</h2>
	<div class="form-group">
		<input type="hidden" name="invoicesid" id="invoicesid" value="<?php echo $_GET['id'];?>">
	</div>

	<input type=submit value="Save Changes" class="btn btn-default btn-success">
</form>
<?php
include "../tmpl/footer.php";
?>
