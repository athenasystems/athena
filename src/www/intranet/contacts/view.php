<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1165
Released: Wed Jun 24 17:09:29 2015 GMT
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
$pagetitle = "Contacts";
$navtitle = 'Contacts';
$keywords = '';
$description = '';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

$pagetitle = "Contacts";

include "../tmpl/header.php";
?>

<h1>Contacts</h1>

<?php

if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
    
    $sqltext = "SELECT * FROM contacts,address WHERE contacts.addsid=address.addsid AND contactsid=?";
    // print $sqltext;
    $q = $db->select($sqltext,array($_GET['id']),'i') ;    
    $r = $q[0];
    
    tablerow("Name", $r->fname . ' ' . $r->sname);
    tablerow("Company Name", $r->co_name);
    tablerow("Role", $r->role);
    tablerow("Notes", $r->notes);
    
    $addsid = $r->addsid;
    $adds = getAddress($r->addsid);


    include "/srv/athenace/lib/shared/adds.view.php";
    
}
?>

<?php

include "../tmpl/footer.php";
?>
