<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1178
Released: Thu Jun 25 10:53:50 2015 GMT
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

function sendContactAccessMail($contactsid)
{
    global $db;
    global $owner;
    global $cust_url;
    global $domain;
    
    $sqltext = "SELECT fname,sname,email,usr FROM contacts,address,pwd	WHERE contacts.addsid=address.addsid
        AND contacts.contactsid=pwd.contactsid	AND contacts.contactsid=?";
    // echo $sqltext;
    $q = $db->select($sqltext, array(
        $contactsid
    ), 'i');
    if (! empty($q)) {
        $r = $q[0];
        $user = $r->usr;
        $email = $r->email;
        
        if (isset($user) && ($user != '')) {
            $htmlBody = <<<EOHTML			<img src="http://www.$domain/img/email.header.jpg" border=0 alt="{$owner->co_name}" title="{$owner->co_name}"><br><br><h2>{$owner->co_name} Control Panel Access Details</h2>Your Access Details for the {$owner->co_name} Control Panel are below.<br><br>Web Address - https://www.$domain/<br>Username: $user<br>Email: $email<br><br>Many thanks<br><br>{$owner->co_name}<br><br>
P.S. You can you the "Forgot Password" feature to make a new password if neccessary.EOHTML;
            
            sendAthenaEmail($r->fname . ' ' . $r->sname, $r->email, $owner->co_name . ' Control Panel Access', $htmlBody);
            $ret = 'Sent mail to ' . $r->email;
            return $ret;
        } else {
            $ret = 'Mail not sent - Customer not known';
            return $ret;
        }
    } else {
        $ret = 'Mail not sent - Customer unknown';
        return $ret;
    }
}

function sendResetPass($name, $email)
{
    require_once 'athena_mail.php';
    
    global $domain;
    
    $token = time() . '|' . $_POST['email'];
    
    $resetLink = 'https://www.' . $domain . '/rpw?t=' . base64_encode($token);
    
    $htmlBody = getResetPassMailBody($resetLink);
    
    sendAthenaEmail($name, $email, 'Athena Password Reset ', $htmlBody);
    
    $ret = '';
    
    return $ret;
}

function getResetPassMailBody($resetLink)
{
    global $owner;
    
    $retHTML = <<< EOHTML
	<span style="font-size:170%;">{$owner->co_name}</span><br><br>
To change your Password from the {$owner->co_name} we site 
click the link below:-<br><br>
$resetLink
EOHTML;
    
    $retHTML .= emailSignature();
    
    return ($retHTML);
}

function getQuoteMailBody($quotesid)
{
    global $owner;
    
    $retHTML = <<< EOHTML
	<span style="font-size:170%;">{$owner->co_name}</span><br><br>
Please find your quote from {$owner->co_name} attached to this email 
as a PDF file:-<br><br>
EOHTML;
    
    $retHTML .= emailSignature();
    
    return ($retHTML);
}

function getInvoiceMailBody($invoicesid)
{
    global $owner;
    
    $retHTML = <<< EOHTML
	<span style="font-size:170%;">{$owner->co_name}</span><br><br>
Please find your invoice from {$owner->co_name} attached to this email 
as a PDF file:-<br><br>
EOHTML;
    
    $retHTML .= emailSignature();
    
    return ($retHTML);
}

function emailSignature()
{
    global $owner;
    
    $retHTML = <<< EOHTML
<br clear="both"><br><br>Best Regards,<br><br>
{$owner->co_name}<br><br>
-- <br>
{$owner->co_name}<br>
{$owner->add1},<br>
{$owner->add2} {$owner->add3}<br>
{$owner->city},	{$owner->county}<br>
{$owner->country}	{$owner->postcode}<br>
Tel: {$owner->tel}<br>
Fax: {$owner->fax}<br>
Email: {$owner->email}<br>
Website: {$owner->web}
    
EOHTML;
    
    return ($retHTML);
}

?>