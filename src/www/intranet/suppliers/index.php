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
$pagetitle = "Suppliers";
$navtitle = 'Suppliers';
$keywords = '';
$description = '';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

// Set up HTML Form input error array

$pagetitle = "Suppliers";

$co_name = ((isset($_GET['q'])) && ($_GET['q'] != '')) ? $_GET['q'] : '';

include "../tmpl/header.php";
?>

<h1>Suppliers</h1>
<a href="/suppliers/add.php" title="Add a new supplier">Add a new supplier</a>

<?php
$from = ((isset($_GET['from'])) && (is_numeric($_GET['from']))) ? $_GET['from'] : 0;
$perpage = ((isset($_GET['perpage'])) && (is_numeric($_GET['perpage']))) ? $_GET['perpage'] : $ppage;
$newfrom = $from + $perpage;

if ($co_name != '') {
    $sqltext = "SELECT SQL_CALC_FOUND_ROWS * FROM supplier WHERE co_name LIKE '?%' ORDER BY co_name LIMIT ?,?";
    $q = $db->select($sqltext, array($co_name,$from,$perpage), 'sii');
} else {
    $sqltext = "SELECT SQL_CALC_FOUND_ROWS * FROM supplier ORDER BY co_name LIMIT ?,?";
    $q = $db->select($sqltext, array($from,$perpage), 'ii');
}


$retrnd = count($q);

$txtPlaceholder = 'Supplier Name';

include "/srv/athenace/lib/shared/searchBar.php";

if (! empty($q)) {
    $retHTML = '<ul>';
    foreach ($q as $r) {
        
        $suppid = $r->suppid;
        $co_name = $r->co_name;
        $colour = $r->colour;
        
        ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<div style="width: 10px; background-color: <?php echo $colour;?>; float: left; margin-right: 5px;">&nbsp;</div>
		<strong><?php echo $co_name;?></strong>
	</div>
	<div class="panel-body">
		<a href="/suppliers/view.php?id=<?php echo $suppid;?>">View Full Details</a> | <a
			href="/suppliers/edit.php?id=<?php echo $suppid;?>">Edit</a>
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
	q = document.getElementById('q').value;
	perpage = document.getElementById('perpage').value;
	
	
	if(document.getElementById('contactsid')){
		contactsid = document.getElementById('contactsid').value;
	}else{
		contactsid = '';
	}
	
	//if (suppid==0){contactsid = '';}
	
	webpage = window.location.pathname;
	url = webpage + "?from=<?php echo $newfrom?>&perpage=" + perpage  ;

	 if (q!='') {
			url +=  "&q=" + q ; 
		}
		
// 	if (suppid!='') {
// 		url +=  "&suppid=" + suppid; 
// 	}
// 	if (contactsid!='') {
// 		url += "&contactsid=" + contactsid ;
// 	}
	
	location = url;
	
}
</script>

<?php
if ($endofsearch == ($newfrom)) {
    
    ?>
<div style="margin: 0; text-align: right;">
	<a href="javascript:void(0);" onclick="goNextPage();">Next --&gt;</a>
</div>

<?php
}

include "../tmpl/footer.php";
?>