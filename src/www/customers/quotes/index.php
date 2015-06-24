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

$pagetitle = "Quotes";
$navtitle = 'Quotes';
$pagescript = array();
$pagestyle = array();

include "../tmpl/header.php";
?>

<h1>Quotes</h1>

<?php

$from = ((isset($_GET['from'])) && (is_numeric($_GET['from']))) ? $_GET['from'] : 0;
$perpage = ((isset($_GET['perpage'])) && (is_numeric($_GET['perpage']))) ? $_GET['perpage'] : $ppage;
$newfrom = $from + $perpage;

$sqltext = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(quotes.quotesid), quotes.incept,
quotes.quotesid, co_name, quotes.content, agree
FROM quotes, customer WHERE quotes.custid=customer.custid
AND quotes.quotesid>0 and (live=1 OR origin='ext') AND quotes.custid=?";

if (isset($_GET['q'])) {
    $sqltext .= " AND quotes.quotesid LIKE '?%' ORDER BY quotesid DESC LIMIT $from,$perpage";
    $q = $db->select($sqltext, array($custID,$_GET['q']), 'is');
} else {
    $sqltext .= " ORDER BY quotesid DESC LIMIT $from,$perpage";
    $q = $db->select($sqltext, array($custID), 'i');
}

// print $sqltext;

$retrnd = count($q);

$txtPlaceholder = 'Quote No.';

include "/srv/athenace/lib/shared/searchBar.php";

if (! empty($q)) {
    
    foreach ($q as $r) {
        
        $startDate = date('d-m-Y', $r->incept);
        
        $quotesid = $r->quotesid;
        $co_name = $r->co_name;
        
        $rand = rand();
        $startDate = date('d-m-Y', $r->incept);
        $content = stripslashes($r->content);
        
        $fromExtMark = '';
        $agreedMark = '';
        if ((! $r->incept) && (! $r->agree)) {
            $fromExtMark = 'Status: <div class="alert alert-danger">Awaiting Quotation from ' . $owner->co_name . '</div>';
        }
        if (($r->incept) && (! $r->agree)) {
            $fromExtMark = '<div class="alert alert-danger">Status: Awaiting Your Quotation Approval</div> ';
        }
        
        if ($r->agree) {
            $fromExtMark = 'Status: Quote Agreed';
        }
        
        ?>

<div class="panel panel-default">
	<div class="panel-heading">
		<strong><?php echo $owner->co_name;?> Quote No: <?php echo $quotesid;?></strong>  - Date: <?php echo $startDate;?>

	</div>
	<div class="panel-body">
		<a href="/quotes/view.php?id=<?php echo $quotesid;?>" title="View this Quote">View Quote</a> | <a
			href="/bin/make_pdf_quote.pl?id=<?php echo $quotesid;?>" title="Download a PDF of this Quote">Download PDF</a> <a
			href="/bin/make_pdf_quote.pl?id=<?php echo $quotesid;?>" title="Download a PDF of this Quote"> <img
			src="/img/pdf-logo.png" width=20 align=top border=0 /></a> 			
	 <?php echo $fromExtMark;?>	
	</div>

</div>

<?php
    }
}

?>

<br>
<br>
<script>
function goNextPage(){
	q = document.getElementById('q').value;
	perpage = document.getElementById('perpage').value;
	custid = <?php echo $custID?>;
	webpage = window.location.pathname;
	url = webpage + "?from=<?php echo $newfrom?>&perpage=" + perpage  + "&custid=" + custid + "&q=" + q;
	location = url;
}
</script>
<?php
if (count($q) > $perpage) {
    ?>
<div style="text-align: right">
	<a href="javascript:void(0)" onclick="goNextPage()">Next --&gt;</a>
</div>
<?php
}
include "../tmpl/footer.php";
?>
