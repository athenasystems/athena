<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1179
Released: Mon Jun 29 09:29:29 2015 GMT
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
include "/srv/athenace/lib/shared/functions_form.php";

$pagetitle = "Customers";
$navtitle = 'Customers';
$keywords = '';
$description = '';

$custid = ((isset($_GET['custid'])) && (is_numeric($_GET['custid'])) && ($_GET['custid'] > 0)) ? $_GET['custid'] : '';

$co_name = ((isset($_GET['q'])) && ($_GET['q'] != '')) ? $_GET['q'] : '';

include "../tmpl/header.php";

?>

<h1>Customers</h1>
<a href="/customers/add.php" title="Add a new customer">Add a new customer</a>

<?php
$from = ((isset($_GET['from'])) && (is_numeric($_GET['from']))) ? $_GET['from'] : 0;
$perpage = ((isset($_GET['perpage'])) && (is_numeric($_GET['perpage']))) ? $_GET['perpage'] : $ppage;
$newfrom = $from + $perpage;

if (($co_name != '') && ($custid != '')) {
    $sqltext = "SELECT SQL_CALC_FOUND_ROWS * FROM customer WHERE co_name LIKE '?%' AND custid=?  ORDER BY co_name LIMIT ?,?";
    $q = $db->select($sqltext, array($co_name,$custid,$from,$perpage), 'siii');
} elseif ($co_name != '') {
    $sqltext = "SELECT SQL_CALC_FOUND_ROWS * FROM customer WHERE co_name LIKE '?%' ORDER BY co_name LIMIT ?,?";
    $q = $db->select($sqltext, array($co_name,$from,$perpage), 'sii');
} elseif ($custid != '') {
    $sqltext = "SELECT SQL_CALC_FOUND_ROWS * FROM customer WHERE custid=?  ORDER BY co_name LIMIT ?,?";
    $q = $db->select($sqltext, array($custid,$from,$perpage), 'siii');
} else {
    $sqltext = "SELECT SQL_CALC_FOUND_ROWS * FROM customer ORDER BY co_name LIMIT ?,?";
    $q = $db->select($sqltext, array($from,$perpage), 'ii');
}

$retrnd = count($q);

$txtPlaceholder = 'Customer Name';

include "/srv/athenace/lib/shared/searchBar.php";

if (! empty($q)) {
    
    foreach ($q as $r) {
        
        $custid = $r->custid;
        $co_name = $r->co_name;
        $colour = $r->colour;
        
        ?>

<div class="panel panel-info">
	<div class="panel-heading">

		<div style="width:10px;background-color:<?php echo $colour;?>;float:left;margin-right:5px;">&nbsp;</div>
		<a href="/customers/view.php?id=<?php echo $custid;?>" title="Edit this Customers"><strong><?php echo $co_name;?></strong></a>
	</div>
	<div class="panel-body">
		<a href="/customers/view.php?id=<?php echo $custid;?>">View Full Details</a> | <a
			href="/customers/edit.php?id=<?php echo $custid;?>">Edit</a>| <a
			href="/customers/delete.php?id=<?php echo $custid;?>">Delete</a>

	</div>
</div>


<?php
    }
} else {
    ?>

<div>No results found ...</div>
<?php
}

?>


<script>
function goNextPage(){
	q = document.getElementById('q').value; +  "&q=" + q 
	perpage = document.getElementById('perpage').value;
	
	
	webpage = window.location.pathname;
	url = webpage + "?from=<?php echo $newfrom?>&perpage=" + perpage  ;
	
	 if (q!='') {
			url +=  "&q=" + q ; 
		}

		
	location = url;
	
}
</script>

<?php
if ($endofsearch == ($newfrom)) {
    ?>
<div style="text-align: right">
	<a href="javascript:void(0)" onclick="goNextPage()">Next --&gt;</a>
</div>

<?php
}
include "../tmpl/footer.php";
?>