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
$pagetitle = "Quotes";
$navtitle = 'Quotes';
$keywords = '';
$description ='';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";

if(!is_numeric($_GET['id'])){
    header("Location: /quotes/?id=notFound");
    exit;
}
$pagetitle = "View Quote";
$pagescript = array();
$pagestyle = array();

$owner = siteDets();

include "../tmpl/print_header.php";

$sqltext = "SELECT * FROM quotes,customer,address WHERE quotes.custid=customer.custid 
 AND customer.addsid=address.addsid AND quotes.quotesid=?";
$q = $db->select($sqltext,array($_GET['id']),'i') ;
if (! empty($q)) {
    $r = $q[0];
} else {
    header("Location: /quotes/");
    exit();
}

$quotedate = date('d-m-Y', $r->incept);

$r->content = preg_replace("/\r\n/", "<br>", $r->content);
// $r->content = preg_replace("/\r/", "<br>" , $r->content);
$r->terms = 'Delivery quoted is dependent upon workload at time of order placement.

Price quoted is dependent upon adequate data with no significant deviation from data issued for quotation purposes.
';

?>

<div style="display: inline; width: 350px; float: left;">
	<br> Quote To:<br>
  <?php echo $r->co_name?><br>  
      <?php echo $r->add1?>
      <?php echo $r->add2?>
      <?php echo $r->add3?><br>
      <?php echo $r->city?>
      <?php echo $r->county?>
    <?php echo $r->postcode?>
  </div>

<div
	style="display: inline; width: 350px; float: left; text-align: right">
  Date: <?php echo $quotedate?><br> 
Our Reference: Quote No <?php echo $r->quotesid?><br>
  </div>

<br clear="all">
<br>
<br>

<table width="720" border="0" align="left" cellpadding="1" cellspacing="3" style="font-weight: bold;">

	<tr style="vertical-align: top">
		<td valign="top" style="font-size: 80%;">Description</td>
		<td valign="top" style="font-size: 80%;">Price</td>

	</tr>
  
  <tr style="vertical-align: top">
		<td valign="top"><?php echo stripslashes($r->content)?></td>
		<td valign="top" width="120">&pound;<?php echo $r->price?></td>
  
	</tr>    
    
</table>
<br clear="all">
<br>
<br>
<div id="header" class="clearfix"></div>

<?php
include "../tmpl/print_footer.php";
?>
