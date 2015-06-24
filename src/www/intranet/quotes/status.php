<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1160
Released: Wed Jun 24 17:00:02 2015 GMT
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

$pagetitle = "Quotes";
$navtitle = 'Quotes';
$keywords = '';
$description ='';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

if(!is_numeric($_GET['id'])){
    header("Location: /quotes/?id=notFound");
    exit;
}
# Set up HTML Form input error array

# Check if we have Form Data to process
if( (isset($_GET['go'])) && ($_GET['go'] == "y") ){

	if(empty($errors)){

		if($_POST['live']!=1){
			$_POST['live']=0;
		}

		# Update DB
		$quotesUpdate = new Quotes();
		
		$quotesUpdate->setQuotesid($_GET['id']);
		$quotesUpdate->setLive($_POST['live']);
		$quotesUpdate->updateDB();
				
		$logContent='Changed Quote Status to ' . $input['live'];
		$logresult = logEvent(5,$logContent);

		header("Location: /quotes/view.php?id=".$_GET['id']);
		
		exit();

	}

}

# Define  elements for the HTML Header include
$pagetitle = "Edit Quote";
$pagescript = array("/pub/calpop/calendar_eu.js");
$pagestyle = array("/css/calendar.css");

include "../tmpl/header.php";

$sqltext = "SELECT * FROM quotes WHERE quotesid=?";
$q = $db->select($sqltext,array($_GET['id']),'i') ;
if($q){
	$r = $q[0];
}else{
	header("Location: /quotes/");
	exit();
}

?>
<div id="fbhelptext" style="float: right;">
	<a href="javascript:void(0);" onclick="showHideHelp('helptext')">Show
		Help</a>
</div>
<br clear="all">
<div id="helptext" style="display:none;">

	Athena Quotes Status
	<ul>
		<li>Setting a Quote "Live" allows your customers to see it in Athena
			Customer Portal.</li>
		<li>This allows you to create and work on Quotes, but not have them
			available until you are ready.</li>
		<li>Once a Quote is Live, customers are able to log in to the Athena
			Customer Portal and respond to the Quote immediately.</li>
		<li>When a customer responds, Athena will send you an Email, and a link to the
			Submitted Quote will be visible on the front page summary panels, and
			Quotes section, flagged in red.</li>
	</ul>

</div>
<h1>Quote Status</h1>

<form role="form" 
	action="<?php echo $_SERVER['PHP_SELF']?>?id=<?php echo $_GET['id']?>&amp;go=y"
	enctype="multipart/form-data" method="post">

	<h1>
		Quote No:
		<?php echo $r->quotesid?>
	</h1>
<br>
	<h2>If this Quote finished and ready to be seen by the customer, tick the box below.</h2>
<br><br>
	<fieldset>

	

			<?php 

			$chkd = ($r->live) ? 1 : 0;
			html_checkbox ('Make Live?', 'live', 1,$chkd);

			?>

		

	</fieldset>

	<fieldset class="buttons">
		<?php 
		html_button("Save changes");
		?>
		or <a href="/quotes/" class="cancel" title="Cancel">Cancel</a>

	</fieldset>

</form>

<?php 
include "../tmpl/footer.php";
?>
