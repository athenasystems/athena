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
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/athena_mail.php";
include "/srv/athenace/lib/shared/functions_email.php";

include ("../tmpl/blank_header.php");

$htmlBody = getQuoteMailBody($_GET['id']);

$sqltext = "SELECT customer.custid, customer.co_name, quotes.contactsid,quotes.quotesid,
contacts.fname, contacts.sname FROM customer,quotes,contacts 
WHERE quotes.custid=customer.custid AND quotesid=? LIMIT 1";
// print $sqltext;

$q = $db->select($sqltext,array($_GET['id']),'i') ;
if (! empty($q)) {
    $r = $q[0];
    
    $custid = $r->custid;
    $quoteno = $r->quotesid;    
    $email = getCustEmailAdd($custid);
    
    if ((! isset($email)) || ($email == '')) {
        $email = getCustEmailAdd($custid);
    }
    
    if ((! isset($email)) || ($email == '')) {
        echo "Mailer Error: Customer has No Email address on the system";
        exit();
    }
    
    $docTitlePrefix = preg_replace('/\W/', '_', $owner->co_name);
    $docTitlePrefix = preg_replace('/__/', '_', $docTitlePrefix);
    $docTitle = $docTitlePrefix . "_Quote_" . $quoteno . '.pdf';
    $docName = $dataDir . "/pdf/quotes/$docTitle";
    
    // Make PDF
    passthru("perl $athenaDir/lib/perl/bin/make_pdf_quote.pl format=file id=" . $_GET['id']);
    
    $name = $r->fname . ' ' . $r->sname;
    $esubject = "Your Quote from " . $owner->co_name;
    
    $ret = sendAthenaEmail($name, $email, $esubject, $htmlBody, $docName, $docTitle);
    
    $logContent = "QuoteID: " . $_GET['id'] . " sent to $name - $email";
    $logresult = logEvent(2, $logContent);
} else {
    $ret = 'There was a problem ... ';
}

echo $ret;

include "../tmpl/blank_footer.php";

?>
