<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1173
Released: Wed Jun 24 19:00:41 2015 GMT
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

$pagetitle = "staff";
$navtitle = 'Staff';
$keywords = '';
$description = '';

$id = ((isset($_GET['q'])) && ($_GET['q'] != '')) ? $_GET['q'] : '';
include "../tmpl/header.php";
?>

<h1>Staff</h1>
<a href="/staff/add.php" title="Add new offer">New Staff Member</a>

<?php
$from = ((isset($_GET['from'])) && (is_numeric($_GET['from']))) ? $_GET['from'] : 0;
$perpage = ((isset($_GET['perpage'])) && (is_numeric($_GET['perpage']))) ? $_GET['perpage'] : $ppage;
$newfrom = $from + $perpage;

$sqltext = "SELECT SQL_CALC_FOUND_ROWS * FROM staff,pwd 
    WHERE status='active' AND staff.staffid=pwd.staffid";
if ($id != '') {
    $sqltext .= " AND fname LIKE '?%'";
}

$sqltext .= " ORDER BY fname LIMIT ?,?";

if ($id != '') {
    $q = $db->select($sqltext, array($id,$from,$perpage), 'iii');
} else {
    $q = $db->select($sqltext, array($from,$perpage), 'ii');
}

$retrnd = count($q);

$txtPlaceholder = 'First Name';

include "/srv/athenace/lib/shared/searchBar.php";

if (! empty($q)) {
    foreach ($q as $r) {
        ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<strong>
	<?php echo htmlentities($r->fname . ' ' . $r->sname)?></strong>
	<?php if ($r->jobtitle != '') {echo  ': ' . $r->jobtitle;}?></div>

	<div class="panel-body">
		<a href="/staff/view.php?id=<?php echo $r->staffid?>" title="View this person's details">View</a> |
		<a href="/staff/edit.php?id=<?php echo $r->staffid?>" title="Edit this person's details">Edit</a> |
		<a href="/staff/delete.php?id=<?php echo $r->staffid?>" title="Delete this person's details">Delete</a> | <a
			href="/staff/login.php?id=<?php echo $r->staffid?>" title="Log in Details">Log in Details</a> | <a
			href="/staff/access.php?id=<?php echo $r->staffid?>" title="Fill in Timesheets">Athena Access</a> (Current Access: <?php
        if ($r->seclev == 1) {
            echo 'Administrator';
        } else {
            echo 'Staff';
        }
        ?>)
	</div>
</div>

<?php
    }
} else {
    ?>
<li>No results found ...</li>
<?php
}
?>

<script>
function goNextPage(){
	q = document.getElementById('q').value;
	perpage = document.getElementById('perpage').value;
	webpage = window.location.pathname;
	url = webpage + "?q=" + q + "&from=<?php echo $newfrom?>&perpage=" + perpage   ;
	location = url;
}
</script>
<?php

include "../tmpl/footer.php";
?>
