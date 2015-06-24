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
$pagetitle = "Quotes";
$navtitle = 'Quotes';
$keywords = '';
$description = '';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

if (! is_numeric($_GET['id'])) {
    header("Location: /quotes/?id=notFound");
    exit();
}
$sqltext = "SELECT * FROM quotes,customer
WHERE quotes.custid=customer.custid
AND quotes.quotesid=?
AND quotesid>0";

$q = $db->select($sqltext, array(
    $_GET['id']
), 'i');
if (! empty($q)) {
    $r = $q[0];
} else {
    header("Location: /quotes/");
    exit();
}

// Define elements for the HTML Header include
$pagetitle = "View Quote";
$pagescript = array();
$pagestyle = array();

include "../tmpl/header.php";

$quotedate = date('d-m-Y', $r->incept);

$r->content = preg_replace("/\r\n/", "<br>", $r->content);

$murl = base64_encode("/mail/quote.php?id=" . $_GET['id']);

$purl = base64_encode('/quotes/view?id=' . $r->quotesid);
if ($r->live) {
    ?>

<form role="form" action="/mail/send_owl" method="post"
	enctype="multipart/form-data" style="display: inline;"
	name="emailtocust">
		<?php
    
    html_button("Email This Quote to " . $r->co_name);
    
    ?>
		<input type="hidden" name=url value="<?php echo $murl; ?>">
</form>
<?php
}
?>
<h1>
	Quote No:
	<?php echo $r->quotesid?>
	for
	<?php echo $r->co_name?>

</h1>

<?php
if ($r->live) {
    $status = '<span style="color:green">This Quote is Live</span>';
} else {
    $status = '<span style="color:brown">This Quote is not Live</span>';
}

tablerow("Quote Status", '<a href="/quotes/status?id=' . $r->quotesid . '" title="Click to change Quote Status">' . $status . '</a>');
tablerow("Date", $quotedate);
tablerow("Quote Description", stripslashes($r->content));
tablerow("Notes", stripslashes($r->notes));
?>
<br>
<br>

<div class="clearfix"></div>
<br>

<br clear="all">

<br>
<div style="text-align: right; font-weight: bold; width: 800px;">
	Quote Total Price: &pound;
	<?php echo $r->price?>
</div>
<br>
<br>

<?php
include "../tmpl/footer.php";
?>
