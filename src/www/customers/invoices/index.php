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

$pagetitle = "Invoices";
$navtitle = 'Invoices';
$pagescript = array();
$pagestyle = array();

$done = 0;
$id = '';
if (! isset($_GET['q'])) {
    $_GET['q'] = '';
} else {
    $id = $_GET['q'];
}

include "../tmpl/header.php";

$from = ((isset($_GET['from'])) && (is_numeric($_GET['from']))) ? $_GET['from'] : 0;
$perpage = ((isset($_GET['perpage'])) && (is_numeric($_GET['perpage']))) ? $_GET['perpage'] : $ppage;
$newfrom = $from + $perpage;
?>

<h1>Invoices</h1>

<?php

$sqltext = "SELECT SQL_CALC_FOUND_ROWS DISTINCT invoices.invoicesid,invoices.paid,
invoices.incept,co_name,colour
FROM invoices,customer WHERE invoices.custid=customer.custid 
AND invoices.custid=?";

if ($id != '') {
    $sqltext .= " AND invoices.invoicesid LIKE '?%' ORDER BY invoicesid DESC LIMIT ?,?";
    $q = $db->select($sqltext, array($custID,$id,$from,$perpage), 'iiii');
} else {
    $sqltext .= " ORDER BY invoicesid DESC LIMIT ?,?";
    $q = $db->select($sqltext, array($custID,$from,$perpage), 'iii');
}

// print $sqltext;

$retrnd = count($q);

$txtPlaceholder = 'Invoice No.';

include "/srv/athenace/lib/shared/searchBar.php";

if (! empty($q)) {
    
    foreach ($q as $r) {
        
        $co_name = $r->co_name;
        $invoicesid = $r->invoicesid;
        $date = date("d/m/Y", $r->incept);
        ?>


<div class="panel panel-default">
	<div class="panel-heading">
		<strong>Invoice No: <?php echo $invoicesid;?></strong> 
		| Date: <?php echo $date;?>
	| <a href="/bin/make_pdf_invoice.pl?id=<?php echo $invoicesid;?>" title="Download a PDF of this Invoice">Download PDF</a>
		<a href="/bin/make_pdf_invoice.pl?id=<?php echo $invoicesid;?>" title="Download a PDF of this Invoice"> <img
			src="/img/pdf-logo.png" width=20 align=top border=0 /></a>

	</div>

</div>

<?php
    }
} else {
    ?>
			No results found ... 
		<?php
}

?>
<script>
function goNextPage(){
	q = document.getElementById('q').value;
	perpage = document.getElementById('perpage').value;
	custid = <?php echo $custID?>;
	webpage = window.location.pathname;
	url = webpage + "?from=<?php echo $newfrom?>&perpage=" + perpage  + "&custid=" + custid + "&q=" + q ;
	location = url;
}
</script>

<div style="text-align: right">
	<a href="javascript:void(0)" onclick="goNextPage()">Next --&gt;</a>
</div>

<?php
include "../tmpl/footer.php";
?>