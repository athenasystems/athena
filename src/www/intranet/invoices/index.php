<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1173
Released: Wed Jun 24 19:00:41 2015 GMT
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

$owner = siteDets();

$done = 0;

// Check if we have Form Data to process
if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    if ((isset($_GET['id'])) && (is_numeric($_GET['id'])) && ($_GET['id'])) {
         
        $logContent = 'Invoice Paid for InvoiceID:' . $_GET['id'];

        # Update DB
        $invoicesUpdate = new Invoices();        
        $invoicesUpdate->setInvoicesid($_GET['id']);
        $invoicesUpdate->setPaid(time());
        $invoicesUpdate->updateDB();
        
        $logresult = logEvent(26, $logContent);
        
        $done = 1;
    }
}

if ((isset($_GET['go'])) && ($_GET['go'] == "undopaid")) {
    
    if ((isset($_GET['id'])) && (is_numeric($_GET['id'])) && ($_GET['id'])) {
        
        $logContent = 'Invoice marked UNPaid for InvoiceID:' . $_GET['id'];        

        # Update DB
        $invoicesUpdate = new Invoices();
        $invoicesUpdate->setInvoicesid($_GET['id']);
        $invoicesUpdate->setPaid(0);
        $invoicesUpdate->updateDB();
        
        
        $logresult = logEvent(26, $logContent);
        
        $done = 1;
    }
}

// Define elements for the HTML Header include
$pagetitle = "Home";
$pagescript = array();
$pagestyle = array();

$query = ((isset($_GET['q'])) && ($_GET['q'] != '')) ? $_GET['q'] : '';

$custid = ((isset($_GET['custid'])) && (is_numeric($_GET['custid'])) && ($_GET['custid'] > 0)) ? $_GET['custid'] : '';

include "../tmpl/header.php";

?>

<h1>Invoices</h1>
<span><a href="/invoices/add">Add New Invoice</a></span>
<?php
$from = ((isset($_GET['from'])) && (is_numeric($_GET['from']))) ? $_GET['from'] : 0;
$perpage = ((isset($_GET['perpage'])) && (is_numeric($_GET['perpage']))) ? $_GET['perpage'] : $ppage;
$newfrom = $from + $perpage;
?>
<?php

$sqltext = "SELECT SQL_CALC_FOUND_ROWS DISTINCT invoices.invoicesid,invoices.paid,invoices.incept,co_name,colour
FROM invoices,customer
WHERE customer.custid=invoices.custid ";

$searchHelp = '';
if ($query != '') {
    $sqltext .= "AND invoices.invoicesid LIKE '?%' ";
}

if ($custid != '') {
    $sqltext .= "AND invoices.custid=? ";
}

$sqltext .= "ORDER BY invoicesid DESC LIMIT ?,?";


if (($query != '')&&($custid != '')){
	$q = $db->select($sqltext,array($query,$custid,$from,$perpage),'siii');
}elseif (($query != '')){
	$q = $db->select($sqltext,array($query,$from,$perpage),'sii');
}elseif ($custid != ''){
	$q = $db->select($sqltext,array($custid,$from,$perpage),'iii');
}else{
	$q = $db->select($sqltext,array($from,$perpage),'ii');
}

$retrnd = count($q);

$txtPlaceholder = 'Invoice No.';

include "/srv/athenace/lib/shared/searchCustBar.php";


?>
<div id=searchres>

		<?php

if ($searchHelp != '') {
    ?>
				<div>
		<span
			style="color: #333; font-size: 80%; border: 1px #ccc solid; padding: 6px; margin: 4px;">Searching
						on: <?php echo $searchHelp ; ?>
				</span>
	</div>
				<?php
}

if (! empty($q)) {
    
    $helpcnt = 1;
    foreach ($q as $r) {
        if ($helpcnt) {
            if ($query != '') {
                $searchHelp .= 'Invoice No:' . $query . ' ';
            }
            if ($custid != '') {
                $searchHelp .= ' Customer:' . $r->co_name;
//                 if ($contactsid != '') {
//                     $searchHelp .= ' Contact:' . getCustExtName($_GET['contactsid']);
//                 }
            }
            $helpcnt --;
        }
        
        $co_name = $r->co_name;
        $invoicesid = $r->invoicesid;
        $date = date("d/m/Y", $r->incept);
        $colour = $r->colour;
        
        $paidLink = "<a href=\"/invoices/?go=y&amp;id=$invoicesid\" title=\"Mark as Paid\">Mark as Paid</a>";
        $nonpaidLink = "<a href=\"/invoices/?go=undopaid&amp;id=$invoicesid\" title=\"Mark as Not Paid\"> Undo</a>";
        
        $paid = ($r->paid > 0) ? "Paid: " . date("d-m-Y", $r->paid) . $nonpaidLink : $paidLink;
        
        $emailurl = base64_encode("/mail/invoice?id=" . $r->invoicesid);
        
        $pdfExists = 0;
        $docTitlePrefix = preg_replace('/\W/', '_', $owner->co_name);
        $docTitlePrefix = preg_replace('/__/', '_', $docTitlePrefix);
        $docTitlePrefix = preg_replace('/__/', '_', $docTitlePrefix);
        $docTitle = $dataDir . "/pdf/invoices/" . $docTitlePrefix . "_Invoice_" . $invoicesid . '.pdf';
        $docWebName = "/pdf/invoices/" . $docTitlePrefix . "_Invoice_" . $invoicesid . '.pdf';
        
        ?>
	
		<div class="panel panel-info">
		<div class="panel-heading">

			<strong>Invoice No: <?php echo $invoicesid;?></strong> - <?php echo $date;?> - For: <?php echo $co_name;?>
</div>

		<div class="panel-body">
			<div style="width:10px;background-color:<?php echo $colour;?>;float:left;margin-right:5px;">&nbsp;</div>

			<a href="/invoices/view?id=<?php echo $invoicesid;?>"
				title="View the Invoice">View</a> | <a
				href="/invoices/edit?id=<?php echo $invoicesid;?>"
				title="View the Invoice">Edit</a> |  <a
				href="/bin/make_pdf_invoice.pl?id=<?php echo $invoicesid;?>"
				title="Make Invoice PDF"> Download PDF </a> <a
				href="/bin/make_pdf_invoice.pl?id=<?php echo $invoicesid;?>"
				title="Download PDF"> <img src="/img/pdf-logo.png" width=20
				align=top>
			</a> | (<?php echo $paid;?>)
		

		<!--  
<a href="javascript:void(0);" onclick="openInvoiceMail('$invoicesid')" title="Email this Invoice to the Customer">Email to Customer</a> |
      
 -->
		</div>
	</div>
	
<?php
    }
} else {
    ?>
		<div>No results found ...</div>
<?php
}

?>

</div>

<script>
function goNextPage(){
	q = document.getElementById('q').value;
	perpage = document.getElementById('perpage').value;
	custid = document.getElementById('custid').value;
	webpage = window.location.pathname;
	url = webpage + "?from=<?php echo $newfrom?>&perpage=" + perpage  + "&custid=" + custid + "&q=" + q ;
	location = url;
}
</script>

<?php
if ($endofsearch == ($newfrom)) {
    
    ?>
<div style="margin: 0; text-align: right;">
	<a href="javascript:void(0);" onclick="goNextPage();">Next --&gt;</a>
</div>
<?php
}

include "../tmpl/footer.php";
?>
