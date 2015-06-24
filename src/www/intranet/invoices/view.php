<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1171
Released: Wed Jun 24 17:40:21 2015 GMT
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
// Define elements for the HTML Header include
$pagetitle = "Home";
$pagescript = array();
$pagestyle = array();

$owner = siteDets();

include "../tmpl/header.php";

$sqltext = "SELECT customer.co_name,addsid,price,
invoices.invoicesid,invoices.incept as invincept,content
FROM customer,invoices
WHERE invoices.custid=customer.custid
AND invoices.invoicesid=?";

// print $sqltext;

$q = $db->select($sqltext, array(
    $_GET['id']
), 'i');
if (! empty($q)) {
    $r = $q[0];
} else {
    header("Location: /jobs/");
    exit();
}

$adds = getAddress($r->addsid);

$url = base64_encode("/mail/invoice.php?id=" . $_GET['id']);

    ?>

<form role="form" action="/mail/send_owl" method="post"
	enctype="multipart/form-data" style="display: inline;"><?php
    
    html_button("Email This Quote to " . $r->co_name);
    
    ?>
	<input type="hidden" name=url value="<?php echo $url; ?>">
</form>

<h1>
	View Invoice
	<?php echo $r->invoicesid;?>
</h1>

<div>
	<div
		style="float: right; width: 270px; text-align: right; padding-right: 0px;">
		<table>
			<tr>
				<td align=right>Date:</td>
				<td><?php echo date("Y-m-d", $r->invincept)?></td>
			</tr>
			<tr>
				<td align=right><?php echo $owner->co_name; ?> Invoice No:</td>
				<td><?php echo $r->invoicesid?></td>
			</tr>
		</table>
	</div>
	Invoice to:-<br>
	<?php

echo $r->co_name . '<br>';

include '/srv/athenace/lib/shared/adds.view.html.sm.php';

?>
	
	<br clear="all">

	<h2>SALES INVOICE</h2>

	<br>

	<table>
		<tr>
			<td>Details</td>
			<td>Price</td>
		</tr>
		<tr>
			<td>
<?php echo stripslashes($r->content)?>
</td>
			<td>&pound;<?php echo $r->price?>
			</td>

		</tr>

	</table>
	<br> <br>
	<?php
$total = $r->price;
setlocale(LC_MONETARY, 'en_GB');

$vat_rate = getVAT_Rate($r->invincept);
$vat_rateText = getVatText($vat_rate);

$vat = round($total * $vat_rate, 2);
$totalprice = $total + $vat;
?>

	<div class="pull-right">
		Price &pound;
		<?php echo money_format('%i', $total)?>
		<br> VAT @
		<?php echo $vat_rateText?>
		= &pound;
		<?php echo money_format('%i', $vat)?>
		<br>

		<h2>
			Amount Due &pound;
			<?php echo money_format('%i', $totalprice)?>
		</h2>

		<br>
	</div>
	<br clear="all">

</div>
<?php
include "../tmpl/footer.php";
?>