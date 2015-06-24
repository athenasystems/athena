<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1167
Released: Wed Jun 24 17:12:27 2015 GMT
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
$pagetitle = "Add Staff";
$navtitle = 'Staff';
$keywords = '';
$description = '';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

include "../tmpl/header.php";
?>
<?php

$staff = new Staff();
// Load DB data into object
$staff->setStaffid($_GET['id']);
$staff->loadStaff();
$all = $staff->getAll();

$adds = getAddress($staff->getAddsid());

if (isset($all)) {
    ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<strong>Viewing <?php echo $staff->getStaffid();?></strong>
	</div>
	<div class="panel-body">

		<dl class="dl-horizontal">
			<dt>First Name</dt>
			<dd><?php echo $staff->getFname();?></dd>
		</dl>

	
		<dl class="dl-horizontal">
			<dt>Last Name</dt>
			<dd><?php echo $staff->getSname();?></dd>
		</dl>

	
		<dl class="dl-horizontal">
			<dt>Job Title</dt>
			<dd><?php echo $staff->getJobtitle();?></dd>
		</dl>

	
		<dl class="dl-horizontal">
			<dt>Notes</dt>
			<dd><?php echo $staff->getNotes();?></dd>
		</dl>

	
	</div>

<?php
    
    include "/srv/athenace/lib/shared/adds.view.php";
    
    ?>
	
</div>

<?php
} else {
    ?>
<h2>No results found</h2>
<?php
}
?>

<?php
include "../tmpl/footer.php";
?>
