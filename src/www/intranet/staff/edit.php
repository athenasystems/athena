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
$pagetitle = "Edit Staff Details";
$navtitle = 'Staff';
$keywords = '';
$description ='';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

if(!is_numeric($_GET['id'])){
    header("Location: /staff/?id=notFound");
    exit;
}
$sqltext = "SELECT * from staff WHERE staffid=?";
// print "<br>$sqltext";
$q = $db->select($sqltext,array($_GET['id']),'i') ;
$r = $q[0];

if(!is_numeric($_GET['id'])){
    header("Location: /staff/?id=notFound");
    exit;
}

$addsid = $r->addsid;

if (isset($_GET['remove']) && $_GET['remove'] == "y" && isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    db_delete("staff", $_GET['id'], 'staffid');
    
    header("Location: /staff/");
    exit();
}

if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
      
        // Add to Address table
        $addsid = db_updateAddress($_POST, $addsid);
 
        # Update DB
        $staffUpdate = new Staff();
        
        $staffUpdate->setStaffid($_POST['staffid']);
        $staffUpdate->setFname($_POST['fname']);
        $staffUpdate->setSname($_POST['sname']);
        $staffUpdate->setNotes($_POST['notes']);
        $staffUpdate->setJobtitle($_POST['jobtitle']);
        $staffUpdate->setStatus($_POST['status']);
        
        $staffUpdate->updateDB();
                
        header("Location: /staff/");
        
        exit();
}

$pagetitle = "Edit Staff Member";

include "../tmpl/header.php";

?>

<h1>Edit Staff Members Details</h1>
<?php
if ($staffid < 4) {
    ?>
<span><a href="?id=<?php echo $_GET['id']?>&amp;remove=y"
	title="Remove this item" class="cancel">Delete Staff Member</a> </span>
<?php
}
?>
<form role="form" 
	action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $_GET['id']?>&amp;go=y"
	enctype="multipart/form-data" method="post">
	

	<fieldset>

			<?php
// id, fname, sname, add1, add2, city, county, dob

echo '<li><h3>Personal Details</h3></li>';

html_text("First Name *", "fname", $r->fname);

html_text("Family Name", "sname", $r->sname);

html_text("Job Title", "jobtitle", $r->jobtitle);

employee_status_select('Status', 'status', $r->status);

html_textarea("Notes", "notes", $r->notes, "notes");

include "/srv/athenace/lib/shared/adds.edit.form.php";

?>

	</fieldset>

	<fieldset class="buttons">
		<?php
html_button("Save changes");
?>
		or <a href="/staff/" class="cancel" title="Cancel">Cancel</a>

	</fieldset>

</form>

<?php
include "../tmpl/footer.php";
?>
