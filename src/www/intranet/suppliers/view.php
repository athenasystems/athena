<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1160
Released: Wed Jun 24 17:00:02 2015 GMT
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
$pagetitle = "Suppliers";
$navtitle = 'Suppliers';
$keywords = '';
$description ='';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";
include "/srv/athenace/lib/shared/athena_mail.php";
include "/srv/athenace/lib/shared/functions_email.php";

if(!is_numeric($_GET['id'])){
    header("Location: /suppliers/?id=notFound");
    exit;
}

$done = '';

// Check if we have Form Data to process
if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    $logContent = sendContactAccessMail($_GET['cid']);
    
    $name = getContactName($_GET['cid']);
    
    $done = "An email has been sent to $name with access details for the Customer Control Panel";
    
    $logresult = logEvent(25, $logContent);
}

// Define elements for the HTML Header include
$pagetitle = "View supplier";

include "../tmpl/header.php";

$sqltext = "SELECT * FROM supplier WHERE suppid=?";

$q = $db->select($sqltext,array($_GET['id']),'i') ;

$r = $q[0];

$addsid = $r->addsid;
$adds = getAddress($r->addsid);
?>

<h1>View Supplier</h1>

<?php
if ($done != '') {
    ?>
<div id=help>
			<?php echo $done?>
		</div>
<?php
}

?>
<h3>Company Details</h3>
<?php

tablerow('Company Name', $r->co_name);

include "/srv/athenace/lib/shared/adds.view.php";
$invContact = '';
if ((isset($r->inv_contact)) && ($r->inv_contact != '') && ($r->inv_contact > 0)) {
    $invContact = getSuppExtName($r->inv_contact);
    tablerow('Invoice Contact', $invContact);
}

?>

<h3><?php echo $r->co_name;?> Staff</h3>

<?php
$sqltext2 = "SELECT * FROM contacts WHERE suppid=?";

$qq = $db->select($sqltext2,array($_GET['id']),'i') ;
foreach ($qq as $rr) {
    
    $staffHTML = <<< EOF
		<a	href="/contacts/edit.php?id={$rr->contactsid}" title="Edit External Supplier Contact">
		<strong>{$rr->fname} {$rr->sname}</strong></a>		
		<form role="form" action="/suppliers/view?id={$_GET['id']}&cid={$rr->contactsid}&amp;go=y" enctype="multipart/form-data" method="post" style="float:right;">
EOF;
    
    html_button("Send Access Details");
    $staffHTML .= '</form>';
    tablerow('Contact', $staffHTML);
}
?>

<br clear=all>

<?php
include "../tmpl/footer.php";
?>

