<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1168
Released: Wed Jun 24 17:28:24 2015 GMT
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
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

// Define elements for the HTML Header include

$pagetitle = "Quotes";
$navtitle = 'Quotes';
$description = '';
$keywords = '';
$pagescript = array();
$pagestyle = array();

$query = ((isset($_GET['q'])) && ($_GET['q'] != '')) ? $_GET['q'] : '';

$contactsid = ((isset($_GET['contactsid'])) && (is_numeric($_GET['contactsid'])) && ($_GET['contactsid'] > 0)) ? $_GET['contactsid'] : '';

$custid = ((isset($_GET['custid'])) && (is_numeric($_GET['custid'])) && ($_GET['custid'] > 0)) ? $_GET['custid'] : '';

$from = ((isset($_GET['from'])) && (is_numeric($_GET['from']))) ? $_GET['from'] : 0;
$perpage = ((isset($_GET['perpage'])) && (is_numeric($_GET['perpage']))) ? $_GET['perpage'] : $ppage;
$newfrom = $from + $perpage;

include "../tmpl/header.php";

?>

<h1>Quotes</h1>
<span> <a href="/quotes/add" title="Add new quote">Add a New Quote</a>
</span>

<?php
$sqltext = "SELECT SQL_CALC_FOUND_ROWS DISTINCT * FROM quotes,customer
WHERE quotesid>1
AND quotes.custid=customer.custid
AND quotesid>0 ";

if ($query != '') {
    $sqltext .= "AND quotes.quotesid LIKE '?%' ";
}

if ($custid != '') {
    $sqltext .= "AND quotes.custid=? ";
}

$sqltext .= "ORDER BY quotesid DESC LIMIT ?,?";
// print $sqltext;



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


$txtPlaceholder = 'Quote No.';

include "/srv/athenace/lib/shared/searchCustBar.php";


if (! empty($q)) {
    $searchHelp = '';
    $helpcnt = 1;
    foreach ($q as $r) {
        if ($helpcnt) {
            if ($query != '') {
                $searchHelp .= 'Quote No:' . $query . ' ';
            }
            if ($custid != '') {
                $searchHelp .= ' Customer:' . $r->co_name;
                // if ($contactsid != '') {
                // $searchHelp .= ' Contact:' . getCustExtName($r->contactsid);
                // }
            }
            $helpcnt --;
        }
        
        $sqltext = "SELECT price
	FROM quotes,customer
	WHERE  quotes.custid=customer.custid
	AND quotesid=?";
        $qq = $db->select($sqltext,array($r->quotesid),'i') ;
        if (! empty($qq)) {
            
            $priceOK = 1;
            $cnt = 0;
            $itemHTML = '';
            $firstitemHTML = '';
            foreach ($qq as $rr) {
                if (! $rr->price) {
                    $priceOK = 0;
                }
            }
        }
        
        $startDate = date('d-m-Y', $r->incept);
        $quotesid = $r->quotesid;
        $co_name = $r->co_name;
        $colour = $r->colour;
        $ext_contact = (isset($r->contactsid)) ? getCustExtName($r->contactsid) : '';
        
        $content = stripslashes($r->content);
        
        ?>


<div class="panel panel-info">
	<div class="panel-heading">
		<strong>Quote
		No: <?php echo $quotesid;?></strong> <?php echo $co_name;?> - (<?php echo $ext_contact;?>)
        </div>

	<div class="panel-body">
		<div style="width:10px;background-color:<?php echo $colour;?>;float:left;margin-right:5px;">&nbsp;</div>
		<a href="/quotes/view.php?id=<?php echo $quotesid;?>">View</a> | <a
			href="/quotes/edit.php?id=<?php echo $quotesid;?>">Edit</a> | <a
			href="/quotes/print.php?id=<?php echo $quotesid;?>">Print</a> | <a
			href="/bin/make_pdf_quote.pl?id=<?php echo $r->quotesid?>"
			title="Download PDF">Download PDF</a><img src="/img/pdf-logo.png"
			width=20 align=top>

	</div>
</div>
<?php
    }
} else {
    ?>
<div class="panel panel-default">No results found ...</div>
<?php
}

?>

<script>
function goNextPage(){
	q = document.getElementById('q').value;
	perpage = document.getElementById('perpage').value;
	custid = document.getElementById('custid').value;
	
	if(document.getElementById('contactsid')){
		contactsid = document.getElementById('contactsid').value;
	}else{
		contactsid = '';
	}
	
	if (custid==0){contactsid = '';}
	
	webpage = window.location.pathname;
	url = webpage + "?from=<?php echo $newfrom?>&perpage=" + perpage ;

	if (q!='') {
		url +=  "&q=" + q; 
	}
	if (custid!='') {
		url +=  "&custid=" + custid; 
	}
	if (contactsid!='') {
		url += "&contactsid=" + contactsid ;
	}
	
	location = url;
	
}
</script>
<?php
if ($endofsearch == ($newfrom)) {
    ?>
<div style="text-align: right">
	<a href="javascript:void(0)" onclick="goNextPage()">Next --&gt;</a>
</div>

<?php
}
include "../tmpl/footer.php";
?>
