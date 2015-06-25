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
$pagetitle = "Add Staff";
$navtitle = 'Staff';
$keywords = '';
$description = '';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

if(!is_numeric($_GET['id'])){
    header("Location: /staff/?id=notFound");
    exit;
}
if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    $staffDelete = new Staff();
    $staffDelete->setStaffid($_GET['id']);
    $staffDelete->deleteFromDB();
    
    header("Location: /staff/?ItemDeleted=y");
    
    exit();
}
include "../tmpl/header.php";
?>
<?php

$staff = new Staff();
// Load DB data into object
$staff->setStaffid($_GET['id']);
$staff->loadStaff();
$all = $staff->getAll();

if (isset($all)) {
    ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<strong>Delete <?php echo $staff->getFname() . ' ' . $staff->getSname() ;?>?</strong>
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
</div>
<?php
} else {
    ?>
<h2>No results found</h2>
<?php
}
?><form role="form" action="<?php echo $_SERVER['PHP_SELF']?>?go=y&amp;id=<?php echo $_GET['id']; ?>"
	enctype="multipart/form-data" method="post">
	<h2>Please confirm you wish to delete this item</h2>
	<div class="form-group">
		<input type="hidden" name="staffid" id="staffid" value="<?php echo $_GET['id'];?>">
	</div>

	<input type=submit value="Save Changes" class="btn btn-default btn-success">
</form>
<?php
include "../tmpl/footer.php";
?>
