<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1175
Released: Wed Jun 24 19:15:43 2015 GMT
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
include "/srv/athenace/lib/customers/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

$pagetitle = "Quotes";
$navtitle = 'Quotes';
$pagescript = array();
$pagestyle = array();
include "/srv/athenace/lib/shared/athena_mail.php";

if ((isset($_GET['agree'])) && ($_GET['agree'] == "y")) {

    $input['agree'] = time();

    $result = db_update("quotes", $input, $_GET['id'], 'quotesid');

    header("Location: /quotes/view.php?id=" . $_GET['id']);
}

if ((isset($_GET['agree'])) && ($_GET['agree'] == "n")) {

    $input['agree'] = 0;

    $result = db_update("quotes", $input, $_GET['id'], 'quotesid');

    header("Location: /quotes/view.php?id=" . $_GET['id']);
}

$pagetitle = "View Quote";
$pagescript = array();
$pagestyle = array();

include "../tmpl/header.php";

$sqltext = "SELECT * FROM quotes,customer WHERE quotes.custid=customer.custid AND customer.custid=?
AND quotes.quotesid=? AND quotesid>0";
// print $sqltext;
$q = $db->select($sqltext,array($custID,$_GET['id']),'ii') ;
if (! empty($q)) {
    $r = $q[0];
    
    $qno = $r->quotesid;
    
    $quotedate = date('d-m-Y', $r->incept);
    
    $r->content = preg_replace("/\r\n/", "<br>", $r->content);
       
    ?>

<h1>
	Quote No:
	<?php echo $r->quotesid?>
	for
	<?php echo $r->co_name?>
</h1>

<?php
    
    tablerow("Date", $quotedate);
    tablerow("Quote Description", stripslashes($r->content));
    
    ?>

<div style="text-align: right; font-weight: bold; width: 800px;">
	Quote Total Price: &pound;
	<?php echo $r->price?>
</div>

<?php
    if (! $r->agree) {
        ?>
<form class="focusfirst"
	action="<?php echo $_SERVER['PHP_SELF']?>?agree=y&id=<?php echo $_GET['id']?>"
	enctype="multipart/form-data" method="post">
				<?php
        html_button("Agree to this Quote");
        ?>
			</form>
<?php
    } else {
        ?> <form class="focusfirst"
	action="<?php echo $_SERVER['PHP_SELF']?>?agree=n&id=<?php echo $_GET['id']?>"
	enctype="multipart/form-data" method="post">
				<?php
        html_button("Undo Agreement on quote");
        ?>
			</form> <?php
    }
    
} else {
    
    ?>
<h1>No Quote to show</h1>
<?php
}

?>

<?php
include "../tmpl/footer.php";
?>
