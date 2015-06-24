<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1171
Released: Wed Jun 24 17:40:21 2015 GMT
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

$pagetitle = "staff";
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
$pwhelp='';

if( (isset($_GET['go'])) && ($_GET['go'] == "y") ){

    $pwdid = getPwdID($_GET['id']);
    

		# Update DB
		$pwdUpdate = new Pwd();
		
		$pwdUpdate->setPwdid($pwdid);
		$pwdUpdate->setStaffid($_GET['id']);
		$pwdUpdate->setSeclev($_POST['seclev']);
		
		$pwdUpdate->updateDB();

		#	$logresult = logEvent(15,$logContent);
		$done = 1;

}

$pagetitle = "staff";

include "../tmpl/header.php";
?>

<h1>Staff Access</h1>

<h2>Choose which site this user should log in to ...</h2>

<p>&nbsp;</p>

<form role="form" 
	action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $_GET['id']?>&amp;go=y"
	enctype="multipart/form-data" method="post">
	

	<fieldset>

	
			<?php 
			
			html_hidden('stfid', $_GET['id']);
			$group['1'] = 'Full Athena Access';
			$group['10'] = 'Staff Access';
			
			html_radios('Access', 'seclev', $group);

			?>

		

	</fieldset>
	<p>&nbsp;</p>
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
