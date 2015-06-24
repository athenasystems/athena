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
?>
<!DOCTYPE html>
<html>
<title></title>
<body onload='document.forms[0].submit()'>
	<?php

//

$id = '';

if ((isset($_GET['conid'])) && ($_GET['conid'] != '')) {
    
    $u = getTypeFromContact($_GET['conid']);
    
    if ((isset($u['type'])) && ($u['type'] == 'cust')) {
        $_GET['cid'] = $u['id'];
        $id = $_GET['cid'];
        $user = getCustLogin($_GET['cid'], $_GET['conid']);
        $usr_id = $user['contactsid'];
        $usr = $user['usr'];
        $pw = $user['pw'];
        $url = "$cust_url/pass.php";
    } elseif ((isset($u['type'])) && ($u['type'] == 'supp')) {
        $_GET['sid'] = $u['id'];
        $id = $_GET['sid'];
        $user = getSuppLogin($_GET['sid'], $_GET['conid']);
        $usr_id = $user['contactsid'];
        $usr = $user['usr'];
        $pw = $user['pw'];
        $url = "$supp_url/pass.php";
    }
} 

elseif ((isset($_GET['stid'])) && ($_GET['stid'] != '')) {
    
    $url = "$staff_url/pass.php";
    $id = $_GET['stid'];
    
    $user = getStaffLogin($id);
    
    $usr_id = $user['staffid'];
    $usr = $user['usr'];
    $pw = $user['pw'];
} elseif ((isset($_GET['cid'])) && ($_GET['cid'] != '')) {
    
    $url = "$cust_url/pass.php";
    $id = $_GET['cid'];
    
    $user = getCustLogin($id);
    
    $usr_id = $user['contactsid'];
    $usr = $user['usr'];
    $pw = $user['pw'];
} 

elseif ((isset($_GET['sid'])) && ($_GET['sid'] != '')) {
    
    $url = "$supp_url/pass.php";
    $id = $_GET['sid'];
    
    $user = getSuppLogin($id);
    
    $usr_id = $user['contactsid'];
    $usr = $user['usr'];
    $pw = $user['pw'];
}

$int_cookie = $id . '.' . $usr_id . '.' . $usr . '.' . $pw . '.ATHENASECCHK';
// print $url . ' ' . $int_cookie;
// exit();
$token = base64_encode($int_cookie);

?>
	<h2>Redirecting you to the Control Panel ...</h2>
	<form action="<?php echo $url; ?>" method="post">

		<input type="hidden" name="pt" value="<?php echo $token; ?>"> <input
			type="hidden" name="passurl"
			value="<?php if(isset($_GET['passurl'])){echo $_GET['passurl'];} ?>">

	</form>
</body>
</html>
