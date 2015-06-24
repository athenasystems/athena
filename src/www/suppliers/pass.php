<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1161
Released: Wed Jun 24 17:03:46 2015 GMT
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

if ((isset($_GET ['pg']))&&($_GET ['pg'] == 'logout')) {
	$loggedin = chkCookie();
	logEvent("30",$loggedin);
	killCookie ();
	header ( "Location: $www_url" );
	exit ();
}

$token = base64_decode($_GET['t']);
$parts = preg_split('/\|/', decrypt($token));

$usr = $parts[0];
$pw = $parts[1];

if((!isset($_POST['pt']))||($_POST['pt'] == '')){
    $contid = pass (  $usr, $pw, 'contacts' );
}else{
    $cke = base64_decode($_POST['pt']);
    $keywords = preg_split("/\./", $cke);
    if($keywords[4]=='ATHENASECCHK'){
        $cid = $keywords[0];
        $contid = $keywords[1];
        $usr = $keywords[2];
        $pw = $keywords[3];

        #echo "$stfid, $usr, $pw";exit;
    }
}

// echo "$stfid,$sid,$usr,$pw";exit;

if ($contid) {
	
	dropCookie (  $contid, $usr, $pw );

	$contactsUpdate = new Contacts();
	$contactsUpdate->setContactsid($contid);
	$contactsUpdate->setLastlogin(time());
	$contactsUpdate->updateDB();
	// logEvent("26",$staffid,"Username:".$user);
	
	header ( "Location: $supp_url" );
	
} else {
	
	killCookie ();
	
	// logEvent("31",0,"Username:".$user);
	
	header ( "Location: $login_url/?pf=y" );
}

?>