<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1161
Released: Wed Jun 24 17:03:46 2015 GMT
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
$pagetitle = "Contacts";
$navtitle = 'Contacts';
$keywords = '';
$description = '';

include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

$pagetitle = "Contacts";

$search = ((isset($_GET['q'])) && ($_GET['q'] != '')) ? $_GET['q'] : '';

include "../tmpl/header.php";

?>

<h1>Contacts</h1>
<a href="/contacts/add.php" title="Add a new contact">Add a new contact</a>

<?php
$from = ((isset($_GET['from'])) && (is_numeric($_GET['from']))) ? $_GET['from'] : 0;
$perpage = ((isset($_GET['perpage'])) && (is_numeric($_GET['perpage']))) ? $_GET['perpage'] : $ppage;
$newfrom = $from + $perpage;

$sqltext = "SELECT SQL_CALC_FOUND_ROWS * FROM contacts,address
WHERE contacts.addsid=address.addsid";

if ($search != '') {
    $sqltext .= " AND (fname LIKE '?%' OR sname LIKE '?%' ) ORDER BY fname LIMIT ?,?";
    $q = $db->select($sqltext, array($search,$search,$from,$perpage), 'ssii');    
}else{
    $sqltext .= " ORDER BY fname LIMIT ?,?";
    $q = $db->select($sqltext, array($from,$perpage), 'ii');
}

$retrnd = count($q);

$txtPlaceholder = 'First or Surname';

include "/srv/athenace/lib/shared/searchBar.php";

if (! empty($q)) {
    foreach ($q as $r) {
        
        $fname = $r->fname;
        $sname = $r->sname;
        $tel = $r->tel;
        $mob = $r->mob;
        $email = $r->email;
        $contactsid = $r->contactsid;
        
        $contactString = '';
        
        if ($r->custid) {
            $co_name = 'Customer: ' . getCustName($r->custid);
        } elseif ($r->suppid) {
            $co_name = 'Supplier: ' . getSuppName($r->suppid);
        } else {
            $co_name = '';
        }
        
        // if($fname != '') $contactString .= ' | Name: ' . $fname . ' ' . $sname;
        if ($email != '')
            $contactString .= ' | <a href="mailto:' . $email . '">' . $email . "</a>";
        if ($tel != '')
            $contactString .= " | Tel: " . $tel;
        $mob = preg_replace("/\s/", '', $mob);
        if ($mob != '')
            $contactString .= " | Mobile: " . $mob; // . " | <a href=\"/sms/add.php?no=" . $mob . "\">Send SMS</a>";
        
        ?>

<div class="panel panel-info">
	<div class="panel-heading">

		<strong><?php echo $fname . ' ' . $sname;?></strong> <?php if(isset($co_name)&&($co_name!='')){echo "from $co_name";}?>
		</div>

	<div class="panel-body">

		<a href="/contacts/view.php?id=<?php echo $contactsid;?>" title="View contact">View</a> |
		 <a href="/contacts/edit.php?id=<?php echo $contactsid;?>" title="Edit contact">Edit</a> | <a
			href="/contacts/delete.php?id=<?php echo $contactsid;?>" target="_blank" title="Delete <?php echo $co_name;?>">Delete</a>
	
			<?php echo $contactString;?>

		</div>
</div>

<?php
    }
} else {
    ?>
			No results found ...
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
if ($endofsearch == ($newfrom)) {
    
    ?>
<div style="margin: 0; text-align: right;">
	<a href="javascript:void(0);" onclick="goNextPage();">Next --&gt;</a>
</div>

<?php
}

include "../tmpl/footer.php";
?>