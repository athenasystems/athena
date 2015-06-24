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

$pagetitle = 'Sign In Page';
$navtitle = 'Sign In';
$keywords = '';
$description = '';
include "tmpl/header.php";

// Root: mzM15dBXQdQv11mVD
// Staff: tm tDMv2Bkp
// Cust: mikkic QkXmyZBM
// Supp: jamels N3QKkfDV

$user = $_POST['user'];
$pw = $_POST['pw'];

$site = '';

$sqltext = "SELECT staffid,custid,suppid,usr,seclev FROM pwd WHERE usr=? LIMIT 1";
$q = $db->select($sqltext,array($user),'s');
$r = $q[0];

if ((defined($row['staffid'])) && ($row['staffid'] > 1) && ($row['seclev'] == 1)) { // Management log in
    $site = 'intranet';
} elseif ((defined($row['staffid'])) && ($row['staffid'] > 1) && ($row['seclev'] > 1)) { // Staff log in
    $site = 'staff';
} elseif (defined($row['custid']) && ($row['custid'] > 0)) { // Customer log in
    $site = 'customers';
} elseif (defined($row['suppid']) && ($row['suppid'] > 0)) { // Supplier log in
    $site = 'suppliers';
}else{
    failOut('No site to go to');
}


$token = base64_encode(encrypt("$user|$pw"));

header("Location: https://$site.$domain/pass.php?t=$token");

?>


<?php

include "tmpl/footer.php";

?>