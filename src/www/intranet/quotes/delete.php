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
$pagetitle = "Quotes";
$navtitle = 'Quotes';
$keywords = '';
$description = '';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

if(!is_numeric($_GET['id'])){
    header("Location: /quotes/?id=notFound");
    exit;
}
if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    $quotesDelete = new Quotes();
    $quotesDelete->setQuotesid($_GET['id']);
    $quotesDelete->deleteFromDB();
    
    header("Location: /quotes/?ItemDeleted=y");
    
    exit();
}
include "../tmpl/header.php";
?>
<?php

$quotes = new Quotes();
// Load DB data into object
$quotes->setQuotesid($_GET['id']);
$quotes->loadQuotes();
$all = $quotes->getAll();

if (isset($all)) {
    ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<strong>Viewing <?php echo $quotes->getQuotesid();?></strong>
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
		<input type="hidden" name="quotesid" id="quotesid" value="<?php echo $_GET['id'];?>">
	</div>

	<input type=submit value="Save Changes" class="btn btn-default btn-success">
</form>
<?php
include "../tmpl/footer.php";
?>
