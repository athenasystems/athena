<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1168
Released: Wed Jun 24 17:28:24 2015 GMT
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
$pagetitle = 'Reports';
$navtitle = 'Reports';
$keywords = '';
$description = '';

// Include Intranet Global Variables and Functions
include "/srv/athenace/lib/shared/common.php";
include "/srv/athenace/lib/intranet/common.php";
include "/srv/athenace/lib/shared/functions_form.php";

// Define elements for the HTML Header include
$pagetitle = "Site Log";

include "../tmpl/header.php";

$days = (isset($_GET['days'])) ? $_GET['days'] : 1;

// AND mem.memid=sitelog.staffid
// , memid, nick, fname, sname, prefname, email
$now = time();

// sitelogid, incept, staffid, level, event, content, eventsid

$days_ago = $now - (60 * 60 * 24 * $days);
$sqltext = "SELECT sitelogid, sitelog.incept, sitelog.content, sitelog.eventsid, staffid,events.name
FROM sitelog,events
WHERE events.eventsid=sitelog.eventsid";

if (isset($_GET['eventsid']) && $_GET['eventsid'] != '' && is_numeric($_GET['eventsid']) && $_GET['eventsid'] > 0) {
    $sqltext .= " AND sitelog.eventsid=" . $_GET['eventsid'];
}

$sqltext .= " AND sitelog.eventsid<>9 ";
$sqltext .= " AND sitelog.eventsid<>10 ";
$sqltext .= " AND sitelog.incept>? ORDER BY sitelog.incept DESC";

// $sqltext .= " LIMIT 60";

// print $sqltext;
$result = $db->select($sqltext,array($days_ago),'i') ;
$row_count = count($result);

$bodyHTML = <<< EOHTML
<br clear=all>
<div style="width: 150px; float: left;">When</div>
<div style="width: 250px; float: left;">Who</div>
<div style="width: 250px; float: left;">What</div>
<div style="width: 250px; float: left;">Detail</div>
<br clear="all">

<ul style="font-size: 70%;">

EOHTML;

$summaryHTML = "<table style=\"float:right;\">";
$totals = array();
if ($result) {
    
    $cnt = 0;
    
    foreach ($result as $r) {
        if (! isset($totals[$r->eventsid])) {
            $totals[$r->eventsid] = 0;
        }
        $totals[$r->eventsid] ++;
        
        $incept = date('d-m-Y h:i A', $r->incept);
        
        $staffName = getStaffName($r->staffid);
        $r->content = strip_tags($r->content);
        
        $bodyHTML .= <<< EOHTML

<li style="padding:2px;margin:2px;">
<div style="width:150px;float:left;">$incept</div>
<div style="width:250px;float:left;"> $staffName</div>
<div style="width:250px;float:left;"> {$r->name} </div>
<div style="width:280px;float:left;">
<div style="float:left;" id=fblogdetail$cnt><a href="javascript:void(0);" title="{$r->content}" onclick="showHide('logdetail$cnt')">Show Items</a></div>
<div style="padding:6px;margin:4px;border:1px #eee solid;width:250px;float:left;display:none;" id="logdetail$cnt">{$r->content}</div>
</div>
<br clear="all">
</li>

EOHTML;
        
        $cnt ++;
    }
    
    $bodyHTML .= "</ul>";
    ?>

<?php
    $summaryHTML .= "<tr><td>No of Events in the last $days days:</td><td>$row_count</td></tr>";
    foreach ($totals as $key => $value) {
        
        $name = getEventName($key);
        
        $summaryHTML .= "<tr><td>Total $name:</td><td>$value</td></tr>";
    }
}

$summaryHTML .= "</table><br clear=all>";

?>

<h1>Site Log</h1>

<div style="float: right; font-size: 80%; width: 350px;">

	<form role="form" action="<?php echo $_SERVER['PHP_SELF']?>" method=get>
		<fieldset>
		
<?php
if (! isset($_GET['eventsid'])) {
    $_GET['eventsid'] = 0;
}
event_select('Show Events', 'eventsid', $_GET['eventsid']);
days_select('Days to show', 'days', $days);
?>

		</fieldset>

	</form>

</div>
<div style="float: left; font-size: 80%; width: 350px;"><?php

print $summaryHTML;

?></div>

<br clear=all>

<div style="width: 1000px;"><?php

print $bodyHTML;

?></div>
<script>
setTimeout("beginrefresh()",80000);
function beginrefresh(){
location.reload(true);
}
</script>
<?php
include "../tmpl/footer.php";
?>
