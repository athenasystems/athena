<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1176
Released: Wed Jun 24 19:38:48 2015 GMT
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

if( (!isset($_GET['id'])) && (isset($argv[1])) ){
	$_GET['id'] = $argv[1];
}

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/athena_mail.php";
include "/srv/athenace/lib/shared/functions_email.php";

include ("../tmpl/blank_header.php");

$htmlBody = getInvoiceMailBody($_GET['id']);

$sqltext = "SELECT address.email,co_name
FROM customer, invoices, address
WHERE
    invoices.invoicesid = ?
    AND invoices.custid = customer.custid
    AND customer.addsid = address.addsid
LIMIT 1";

//print $sqltext;

$q = $db->select($sqltext,array($_GET['id']),'i') ;
if($q){
	$r = $q[0];
	

$invoiceno = $_GET['id'];
$email = $r->email;
$name = $r->co_name;

if( (!isset($email)) || ($email=='') ){
	echo "Mailer Error: Customer has No Email address on the system";
	exit;
}

$docTitlePrefix = preg_replace('/\W/', '_', $owner->co_name);
$docTitlePrefix = preg_replace('/__/', '_', $docTitlePrefix);
$docTitle = $docTitlePrefix."_Invoice_" . $invoiceno . '.pdf';
$docName = $dataDir . "/pdf/invoices/$docTitle";

# PDF Attachment
passthru("perl $athenaDir/lib/perl/bin/make_pdf_invoice.pl format=file id=" . $_GET['id']);


$esubject='Sales Invoice';
$ret = sendAthenaEmail($name,$email,$esubject ,$htmlBody,$docName,$docTitle);


}else{
    $ret = 'There was a problem ... ';
}
echo $ret;

include "../tmpl/blank_footer.php";


$logContent= "InvID: " . $_GET['id'] . " sent to $name - $email";
$logresult = logEvent(14,$logContent);

?>
