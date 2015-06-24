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

$total_rows = getTotalRows();

$endofsearch = ($total_rows <= $newfrom) ? $total_rows : ($newfrom);

$shown = ($retrnd <= $perpage) ? $retrnd : $perpage;
$shownfrom = $from + 1;
$searchRes = "Showing $shownfrom to $endofsearch (of  $total_rows  results)";
if ($endofsearch == ($newfrom)) {

    ?>
<div style="margin: 0; text-align: right;">
	<a href="javascript:void(0);" onclick="goNextPage();">Next --&gt;</a>
</div>

<?php
}
$idNo = 0;
if ((isset($custid))&&($custid>99)) {
    $idNo = $custid;
}
if ((isset($suppid))&&($suppid>99)) {
    $idNo = $suppid;
}
if ((isset($contactsid))&&($contactsid>999)) {
    $idNo = $contactsid;
}

if (! isset($_GET['q'])) {
    $_GET['q'] = '';
}
?>
<div class="panel panel-default">
	<div class="panel-body">
		<form role="form" action="<?php echo $_SERVER['PHP_SELF']?>"
			id="searchformbyid" method=get class="form-inline"
			onsubmit="document.getElementById('from').value=0;">
			<div class="pull-left">
				<div class="form-group">		
			
		<label for="q">Search </label>
		<input name="q" id="q" value="<?php echo $_GET['q'];?>" class="form-control" type="text" placeholder="<?php echo $txtPlaceholder;?>">
		 <input type="submit" value="Search">
				</div>

			</div>
			<input type="hidden" id="from" name="from" value="<?php echo $from?>">

			<div class="pull-right">
			<?php
			 echo $searchRes;
perpage_select('Per Page', 'perpage', $perpage, '', $idNo);
?>
		</div>		

		</form>

	</div>
</div>
