<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1172
Released: Wed Jun 24 17:40:52 2015 GMT
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
include "/srv/athenace/lib/shared/functions_form.php";
include "/srv/athenace/lib/staff/common.php";

$pagetitle = "Home";
$navtitle = 'Your Details';
$description = '';
$keywords = '';
$pagescript = array();
$pagestyle = array();

$sqltext = "SELECT * from staff WHERE staffid=?";

// print "<br/>$sqltext";

$q = $db->select($sqltext, array($staffid), 'i');

$r = $q[0];

$addsid = $r->addsid;

$errors = array();

if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    // Add to Address table
    $addsid = db_updateAddress($_POST, $addsid);
    
    // Update DB
    $staffUpdate = new Staff();
    $staffUpdate->setStaffid($staffid);
    $staffUpdate->setFname($_POST['fname']);
    $staffUpdate->setSname($_POST['sname']);
    $staffUpdate->setJobtitle($_POST['jobtitle']);
    $staffUpdate->updateDB();
    
    header("Location: /");
    
    exit();
}

$pagetitle = "Edit Staff Member";
$pagescript = array();
$pagestyle = array();

if (($seclevel > 3) && ($r->staffid != $staffid)) {
    header("Location: /staff/?Oooopppsss=1");
    exit();
}

include "tmpl/header.php";

?>

<h1>
	Edit Staff Members Details <span> <?php
if ($staffid < 4) {
    ?> <!-- <a href="?id=<?php echo $staffid?>&amp;remove=y"
		title="Remove this item" class="cancel">Delete Staff Member</a> -->
	</span>
	<?php
}
?>
</h1>

<form class="focusfirst" action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $staffid?>&amp;go=y"
	enctype="multipart/form-data" method="post">

	<fieldset>

		<ol>


			<?php
// id, fname, sname, add1, add2, city, county, dob

echo '<li id=subtitle><h3>Personal Details</h3></li>';

html_text("First Name *", "fname", $r->fname);

html_text("Family Name", "sname", $r->sname);

html_text("Job Title", "jobtitle", $r->jobtitle);

include "/srv/athenace/lib/shared/adds.edit.form.php";

?>


		</ol>

	</fieldset>

	<fieldset class="buttons">
		<?php
html_button("Save changes");
?>
		or <a href="/staff/" class="cancel" title="Cancel">Cancel</a>

	</fieldset>

</form>



<?php
include "tmpl/footer.php";
?>
