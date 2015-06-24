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

function html_text($title, $name, $value, $required = '') {
    $html = <<<EOT
	<div class="form-group">
	<label for="$name">$title</label>
	<input type="text" name="$name" id="$name" value="$value" class="form-control" $required>
	</div> 
EOT;
    
    echo $html;
}

function html_select($title, $name, $param, $selected) {
    $html = <<<EOT
	<div class="form-group">
	<label for="$name">$title</label>
	<select name="$name" id="$name" class="form-control">
EOT;
    foreach ($param as $prm) {
        $html .= "<option value=\"$prm\"";
        if ($prm == $selected) {
            $html .= ' selected';
        }
        $html .= ">$prm</option>";
    }
    
    $html .= '</select></div>';
    
    echo $html;
}

function html_button($display, $class = "", $id = "") {
    $html = "<input type=\"submit\" name=\"todo\" value=\"$display\" class=\"btn btn-default btn-success $class\"";
    
    if ($id != "")
        $html .= " id=\"$id\"";
    
    $html .= ">";
    
    print $html;
}

function html_hidden($name, $value, $return = 'print') {
    $html = "";
    $html .= "<div class=\"form-group\"><input type=\"hidden\" name=\"$name\" value=\"$value\"></div>";
    
    if ($return == 'print') {
        print $html;
    } else {
        return $html;
    }
}

function html_pw($title, $name, $value = "",$required = '' ) {
    
   $html = <<<EOT
	<div class="form-group">
	<label for="$name">$title</label>
	<input type="password" name="$name" id="$name" value="$value" class="form-control" $required>
	</div> 
EOT;
    
    
    print $html;
}

function employee_status_select($display, $name, $selected) {
    $html = <<<EOT
    <div class="form-group"><label for="status"
EOT;
    
    $html .= ">$display</label>";
    
    $html .= <<<EOT
		<select name=status id=status>
EOT;
    
    $statii = array('active','retired','left','temp');
    
    foreach ($statii as $stat) {
        if ($selected == $stat) {
            $sel = ' selected="selected"';
        } else {
            $sel = '';
        }
        $html .= <<<EOT
		<option value="$stat" $sel>$stat</option>
EOT;
    }
    
    $html .= <<<EOT
		</select></div>
		
EOT;
    
    print $html;
}

function html_radios($display, $name, $group, $selected = '', $return = 'print') {
    $html = '';
    foreach ($group as $item => $display) {
        $html .= '<span>';
        
        $html .= "<div class=\"form-group\"><label for=\"{$item}_radio_\"";
        
        $html .= ">$display</label>";
        
        $html .= "<input type=\"radio\" name=\"$name\" id=\"{$item}_radio_\" value=\"$item\"";
        
        if (isset($selected) && ($selected == $item))
            $html .= " checked=\"checked\"";
        
        $html .= "></span>";
    }
    
    $html .= "<br clear=all>";
    
    if ($return == 'print') {
        print $html;
    } else {
        return $html;
    }
}

function customer_select($display, $name, $selected, $onsub = 0, $required = '') {
    global $db;
    
    $html = "<div class=\"form-group\"><label for=\"custid\"";
    
    $html .= ">$display</label><select name=$name id=$name $required";
    
    if ($onsub == 1) {
        $html .= ' onchange="submitFromZero(\'c\')"';
    }
    
    $html .= '><option value="0">Select Customer</option>' . "";
    
    $sqltext = "SELECT custid,co_name FROM customer WHERE custid>? ORDER BY co_name";
    
    $q = $db->select($sqltext, array(0), 'i');
    if ($q) {
        
        foreach ($q as $r) {
            
            if ($selected == $r->custid) {
                $sel = ' selected="selected"';
            } else {
                $sel = '';
            }
            $nid = $r->custid;
            $nname = $r->co_name;
            $html .= <<<EOT
		<option value="$nid" $sel>$nname</option>
EOT;
        }
    }
    $html .= "</select></div>";
    
    print $html;
}

function supplier_select($display, $name, $selected, $onsub = 0, $required = '') {
    global $db;
    
    $html = "<div class=\"form-group\"><label for=\"suppid\"";
    
    $html .= ">$display</label><select name=$name id=$name $required";
    
    if ($onsub == 1) {
        $html .= ' onchange="submitFromZero(\'s\')"';
    }
    
    $html .= ">";
    $html .= '<option value="0">Select Supplier</option>' . "\n";
    
    $sqltext = "SELECT suppid,co_name FROM supplier WHERE suppid>? ORDER BY co_name";
    
    $q = $db->select($sqltext, array(0), 'i');
    if ($q) {
        foreach ($q as $r) {
            
            if ($selected == $r->suppid) {
                $sel = ' selected="selected"';
            } else {
                $sel = '';
            }
            $nid = $r->suppid;
            $nname = $r->co_name;
            $html .= <<<EOT
		<option value="$nid" $sel>$nname</option>\n
EOT;
        }
    }
    $html .= "</select></div>";
    
    print $html;
}

function html_textarea($display, $name, $value = "", $id = "", $required = '') {
    if (isset($id) && ($id == ""))
        $id = $name;
    
    $html = "<div class=\"form-group\"><label for=\"$id\"";
    
    $html .= ">$display</label>";
    $html .= "<textarea name=\"$name\" rows=\"4\" cols=\"30\" id=\"$id\" class=\"form-control ";
    
    $html .= '">' . stripslashes($value) . "</textarea></div>";
    
    if (isset($help) && ($help != "")) {
        $html .= "<span class=\"help\">$help</span>";
    }
    
    print $html;
}

function staff_select($display, $name, $selected, $output = 1) {
    global $db;
    
    $html = "<div class=\"form-group\"><label for=\"staffid\"";
    
    $html .= ">$display</label><select name=$name id=$name>";
    
    $html .= '<option value="0">None</option>' . "";
    
    $sqltext = "SELECT staffid,fname,sname FROM staff
	WHERE status='active'
	AND (fname<>? AND sname<>?)
	AND staffid>1 ORDER BY staffid";
    
    $q = $db->select($sqltext, array('System','Administrator'), 'ss');
    
    foreach ($q as $r) {
        
        if ($selected == $r->staffid) {
            $sel = ' selected="selected"';
        } else {
            $sel = '';
        }
        $nid = $r->staffid;
        $nname = $r->fname . ' ' . $r->sname;
        $html .= <<<EOT
<option value="$nid" $sel>$nname</option>
EOT;
    }
    $html .= "</select></div>";
    
    if ($output) {
        print $html;
    } else {
        return $html;
    }
}

function html_dateselect($display, $name, $value = "", $showtime = "n", $return = 'print') {
    if ($value == "") {
        if ($showtime == "n") {
            $value = date("Y-m-d", strtotime("now"));
        } else {
            $value = date("Y-m-d H:i:s", strtotime("now"));
        }
    } elseif (is_array($value)) {
        $value = $value['year'] . "-" . $value['month'] . "-" . $value['day'] . " " . $value['hour'] . ":" . $value['minute'] . ":00";
    }
    // print $value;
    $cur_year = intval(date("Y", strtotime("now")));
    
    $date = strtotime($value);
    
    if ($cur_year > intval(date("Y", $date)))
        $cur_year = intval(date("Y", $date));
    
    $html = "<div class=\"form-group\"><label for=\"" . $name . "_day\"";
    
    $html .= ">$display</label>";
    $html .= "<select name=\"" . $name . "[day]\" id=\"" . $name . "_day\" class=\"auto";
    
    $html .= '">';
    
    for ($i = 1; $i <= 31; $i ++) {
        
        $html .= "\t<option value=\"$i\"";
        if ($i == intval(date("d", $date)))
            $html .= " selected=\"selected\"";
        $html .= ">$i</option>";
    }
    
    $html .= "</select>";
    $html .= "<select name=\"" . $name . "[month]\" id=\"" . $name . "_month\" class=\"auto";
    
    $html .= '">';
    
    for ($i = 1; $i < 13; $i ++) {
        
        $html .= "\t<option value=\"" . date("m", strtotime("2006-" . $i . "-01")) . "\"";
        
        if ($i == intval(date("m", $date)))
            $html .= " selected=\"selected\"";
        
        $html .= ">" . date("F", strtotime("2006-" . $i . "-01")) . "</option>";
    }
    
    $html .= "</select>";
    $html .= "<select name=\"" . $name . "[year]\" id=\"" . $name . "_year\" class=\"auto";
    
    $html .= '">';
    
    while ($cur_year < date("Y", strtotime("now +10 year"))) {
        
        $html .= "\t<option value=\"$cur_year\"";
        if ($cur_year == intval(date("Y", $date)))
            $html .= " selected=\"selected\"";
        $html .= ">$cur_year</option>";
        
        $cur_year ++;
    }
    
    $html .= "</select>";
    
    if ($showtime == "y") {
        
        $html .= " at <select name=\"" . $name . "[hour]\" id=\"" . $name . "_hour\" class=\"auto\">";
        
        for ($i = 0; $i <= 23; $i ++) {
            
            $html .= "\t<option value=\"" . date("H", strtotime("2006-01-01 " . $i . ":00:00")) . "\"";
            if ($i == date("G", $date))
                $html .= " selected=\"selected\"";
            $html .= ">" . date("H", strtotime("2006-01-01 " . $i . ":00")) . "</option>";
        }
        
        $html .= "</select>:";
        $html .= "<select name=\"" . $name . "[minute]\" id=\"" . $name . "_min\" class=\"auto\">";
        
        for ($i = 0; $i <= 59; $i ++) {
            
            $html .= "\t<option value=\"" . date("i", strtotime("2006-01-01 00:" . $i . ":00")) . "\"";
            if ($i == date("i", $date))
                $html .= " selected=\"selected\"";
            $html .= ">" . date("i", strtotime("2006-01-01 00:" . $i . ":00")) . "</option>";
        }
        
        $html .= "</select>";
    }
    
    $html .= "</div>";
    
    if ($return == 'print') {
        print $html;
    } else {
        return $html;
    }
}

function html_checkbox($display, $name, $value, $checked = 0, $return = 'print', $id = '') {
    $html = "";
    
    if ($id == '') {
        $id = $name;
    }
    
    $html .= "<div class=\"form-group\"><label for=\"" . $id . "\">" . $display;
    
    $html .= "<input type=\"checkbox\" name=\"" . $name . "\" value=\"" . $value . "\" id=\"" . $id . "\"";
    
    if ($checked)
        $html .= " checked=\"checked\"";
    
    $html .= " style=\"width:30px;\"></label></div> ";
    
    if ($return == 'print') {
        print $html;
    } else {
        return $html;
    }
}

function customer_invoice_select($display, $name, $selected) {
    global $db;
    
    $html = "<div class=\"form-group\"><label for=\"custid\"";
    
    $html .= ">$display</label><select name=custid id=custid";
    
    $html .= " onchange=\"refreshInvoiceContact();\"";
    
    $html .= " style=\"width:160px;\">";
    $html .= '<option value="0">Select Customer</option>' . "";
    
    $sqltext = "SELECT custid,co_name FROM customer WHERE custid>? ORDER BY co_name";
    
    $q = $db->select($sqltext, array(0), 'i');
    
    foreach ($q as $r) {
        
        if ($selected == $r->custid) {
            $sel = ' selected="selected"';
        } else {
            $sel = '';
        }
        $nid = $r->custid;
        $nname = $r->co_name;
        $html .= <<<EOT
		<option value="$nid" $sel>$nname</option>
EOT;
    }
    $html .= "</select></div>";
    
    print $html;
}

function custcontact_select($display, $name, $selected, $custid = '') {
    global $db;
    
    $html = "<div class=\"form-group\"><label for=\"contactsid\"";
    $html .= ">$display</label>\n<select name=contactsid><option value=\"0\">None</option>\n";
    
    $sqltext = "SELECT contactsid, fname,sname,role FROM contacts WHERE fname<>? AND sname<>?";
    
    if ((isset($custid)) && (is_numeric($custid))) {
        $sqltext .= " AND custid=?";
    }
    
    $sqltext .= " ORDER BY contactsid";
    
    if ((isset($custid)) && (is_numeric($custid))) {
        $q = $db->select($sqltext, array('System','Administrator',$custid), 'ssi');
    } else {
        $q = $db->select($sqltext, array('System','Administrator'), 'ss');
    }
    
    // print $sqltext;
    if ($q) {
        foreach ($q as $r) {
            
            if ($selected == $r->contactsid) {
                $sel = ' selected="selected"';
            } else {
                $sel = '';
            }
            
            $html .= <<<EOT
<option value="{$r->contactsid}" $sel>{$r->fname} {$r->sname}</option>\n
EOT;
        }
    }
    $html .= "</select></div>";
    
    print $html;
}

function suppliercontact_select($display, $name, $selected, $suppid = '') {
    global $db;
    
    $html = "<div class=\"form-group\"><label for=\"contactsid\"";
    
    $html .= ">$display</label>\n<select name=contactsid><option value=\"0\">None</option>\n";
    
    $sqltext = "SELECT contactsid, fname,sname,role FROM contacts";
    if ((isset($suppid)) && (is_numeric($suppid))) {
        $sqltext .= " WHERE suppid=?";
    } else {
        $sqltext .= " WHERE suppid>?";
    }
    $sqltext .= " ORDER BY contactsid";
    
    if ((isset($suppid)) && (is_numeric($suppid))) {
        $q = $db->select($sqltext, array($suppid), 'i');
    } else {
        $q = $db->select($sqltext, array(0), 'i');
    }
    
    // print $sqltext;
    
    foreach ($q as $r) {
        
        if ($selected == $r->contactsid) {
            $sel = ' selected="selected"';
        } else {
            $sel = '';
        }
        
        $html .= <<<EOT
<option value="{$r->contactsid}" $sel>{$r->fname} {$r->sname}</option>\n
EOT;
    }
    $html .= "</select></div>";
    
    print $html;
}

function event_select($display, $name, $selected) {
    global $db;
    
    $html = "<div class=\"form-group\"><label for=\"eventsid\"";
    
    $html .= ">$display</label>\n<select name=eventsid>";
    
    $html .= '<option value="0">All Events</option>' . "\n";
    
    $sqltext = "SELECT * FROM events WHERE eventsid>? ORDER BY eventsid";
    
    $q = $db->select($sqltext, array(0), 'i');
    
    foreach ($q as $r) {
        
        $eventsid = $r->eventsid;
        $eventName = $r->name;
        
        if ($selected == $r->eventsid) {
            $sel = ' selected="selected"';
        } else {
            $sel = '';
        }
        
        $html .= <<<EOT
<option value="$eventsid" $sel>$eventName</option>
EOT;
    }
    $html .= "</select></div>";
    
    print $html;
}

function days_select($display, $name, $selected) {
    $html = "<div class=\"form-group\"><label for=\"days\"";
    
    $html .= ">$display</label>\n<select name=days>";
    
    $html .= '<option value="1">1 Day</option>' . "\n";
    
    for ($counter = 7; $counter <= 370; $counter += 7) {
        
        if ($selected == $counter) {
            $sel = ' selected="selected"';
        } else {
            $sel = '';
        }
        
        $html .= <<<EOT
<option value="$counter" $sel>$counter Days</option>\n
EOT;
    }
    
    $html .= "</select> <input type=submit name=todo value=Go class=clickable> </div>";
    
    print $html;
}

?>