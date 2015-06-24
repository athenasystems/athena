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

function generatePassword($length = 8) {
    $password = "";
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
    $maxlength = strlen($possible);
    
    if ($length > $maxlength) {
        $length = $maxlength;
    }
    
    $i = 0;
    
    while ($i < $length) {
        $char = substr($possible, mt_rand(0, $maxlength - 1), 1);
        if (! strstr($password, $char)) {
            $password .= $char;
            $i ++;
        }
    }
    
    return $password;
}

function prepare_text($array, &$input) {
    foreach ($array as $key => $value) {
        if (! is_array($value)) {
            $input[$key] = addslashes($value);
        }
    }
}

function logEvent($event, $content) {
    global $staffid;
    
    // Insert into DB
    $sitelogNew = new Sitelog();
    $sitelogNew->setIncept(time());
    $sitelogNew->setStaffid($staffid);
    $sitelogNew->setContent($content);
    $sitelogNew->setEventsid($event);
    
    $db_add_id = $sitelogNew->insertIntoDB();
    
    return $db_add_id;
}

function getStaffDets($staffid) {
    global $db;
    $sqltext = 'SELECT fname,sname,seclev FROM staff,pwd WHERE staff.staffid=pwd.staffid AND staff.staffid=?';
    // print $sqltext;
    $q = $db->select($sqltext, array($staffid), 'i');
    $r = $q[0];
    
    return $r;
}

function getContactDets($contid) {
    global $db;
    
    $sqltext = "SELECT contacts.contactsid, contacts.fname, contacts.sname,contacts.role ,
	add1, add2,add3, city,county, country,
	postcode, tel, fax, email, web
	FROM contacts,address
	WHERE contacts.addsid=address.addsid
	AND contacts.contactsid=?";
    // print $sqltext;
    $q = $db->select($sqltext, array($contid), 'i');
    $r = $q[0];
    
    return $r;
}

function getIfCustOrSupp($contid) {
    global $db;
    
    $sqltext = "SELECT custid, suppid FROM contacts	WHERE contacts.contactsid=?";
    $q = $db->select($sqltext, array($contid), 'i');
    $r = $q[0];
    
    if ((isset($r->custid)) && ($r->custid > 0)) {
        return 'customers';
    } elseif ((isset($r->suppid)) && ($r->suppid > 0)) {
        return 'suppliers';
    }
    
    return fales;
}

function siteDets() {
    global $db;
    
    $sqltext = "SELECT * FROM owner,address WHERE ownerid=? AND owner.addsid=address.addsid";
    // print $sqltext;
    $q = $db->select($sqltext, array(100), 'i');
    
    $r = $q[0];
    
    return $r;
}

function getAddress($addsid) {
    global $db;
    
    $sql = "SELECT * FROM address WHERE addsid=?";
    $q = $db->select($sql, array($addsid), 'i');
    $r = $q[0];
    
    return $r;
}

function tablerow($desc, $content) {
    $ret = <<< EOHTML
 <dl class="dl-horizontal">
  <dt>$desc</dt>
  <dd>$content</dd>
</dl>
    
EOHTML;
    
    print $ret;
}

function getCustExtName($contactsid) {
    global $db;
    
    $sqltext = "SELECT fname,sname FROM contacts WHERE contactsid=?";
    $q = $db->select($sqltext, array($contactsid), 'i');
    if (! empty($q)) {
        
        $r = $q[0];
        $ret = $r->fname . ' ' . $r->sname;
        return $ret;
    } else {
        return 0;
    }
}

function getStaffName($staffid) {
    global $db;
    
    $sqltext = "SELECT fname,sname FROM staff WHERE staffid=?";
    $q = $db->select($sqltext, array($staffid), 'i');
    if (! empty($q)) {
        $r = $q[0];
        $ret = $r->fname . ' ' . $r->sname;
    }
    
    return $ret;
}

function getVAT_Rate($vat_incept) {
    $vat_rate = 0;
    $vat_change_date_1 = 1294099200; // From 17.5% to 20% on 4/1/2011
    
    if ($vat_incept < $vat_change_date_1) {
        $vat_rate = 0.175;
    } else {
        $vat_rate = 0.2;
    }
    return $vat_rate;
}

function getVatText($vat_rate) {
    $vatTxt = ($vat_rate * 100);
    $vatTxt = $vatTxt . '%';
    return $vatTxt;
}

function chkUppercase($string) {
    return preg_match_all('/[A-Z]/', $string, $matches);
    return count($matches[0]);
}

function chkLowercase($string) {
    return preg_match_all('/[a-z]/', $string, $matches);
    return count($matches[0]);
}

function chkDigit($string) {
    return preg_match_all('/\d/', $string, $matches);
    return count($matches[0]);
}

function getTotalRows() {
    global $db;
    
    $sql = "SELECT FOUND_ROWS() AS `found_rows`;";
    $q = $db->query($sql);
    $rows = $q[0];
    return $rows->found_rows;
}

function db_updateAddress($input, $addsid) {
    // Update DB
    $addressUpdate = new Address();
    
    $addressUpdate->setAddsid($addsid);
    $addressUpdate->setAdd1($_POST['add1']);
    $addressUpdate->setAdd2($_POST['add2']);
    $addressUpdate->setAdd3($_POST['add3']);
    $addressUpdate->setCity($_POST['city']);
    $addressUpdate->setCounty($_POST['county']);
    $addressUpdate->setCountry($_POST['country']);
    $addressUpdate->setPostcode($_POST['postcode']);
    $addressUpdate->setTel($_POST['tel']);
    $addressUpdate->setMob($_POST['mob']);
    $addressUpdate->setFax($_POST['fax']);
    $addressUpdate->setEmail($_POST['email']);
    $addressUpdate->setWeb($_POST['web']);
    $addressUpdate->setFacebook($_POST['facebook']);
    $addressUpdate->setTwitter($_POST['twitter']);
    $addressUpdate->setLinkedin($_POST['linkedin']);
    
    $addressUpdate->updateDB();
    
    return $addsid;
}

function db_addAddress($input) {
    
    // Insert into DB
    $addressNew = new Address();
    $addressNew->setAdd1($_POST['add1']);
    $addressNew->setAdd2($_POST['add2']);
    $addressNew->setAdd3($_POST['add3']);
    $addressNew->setCity($_POST['city']);
    $addressNew->setCounty($_POST['county']);
    $addressNew->setCountry($_POST['country']);
    $addressNew->setPostcode($_POST['postcode']);
    $addressNew->setTel($_POST['tel']);
    $addressNew->setMob($_POST['mob']);
    $addressNew->setFax($_POST['fax']);
    $addressNew->setEmail($_POST['email']);
    $addressNew->setWeb($_POST['web']);
    $addressNew->setFacebook($_POST['facebook']);
    $addressNew->setTwitter($_POST['twitter']);
    $addressNew->setLinkedin($_POST['linkedin']);
    
    $addsid = $addressNew->insertIntoDB();
    
    return $addsid;
}

function getPwdID($staffid) {
    global $db;

    $sqltext = "SELECT pwdid FROM pwd WHERE staffid=?";
    $q = $db->select($sqltext, array($staffid), 'i');
    if (! empty($q)) {
        $r = $q[0];
        $ret = $r->pwdid;
    }
    
    return $ret;

}

function getContactPwdID($contactsId) {
    global $db;
    
    $sqltext = "SELECT pwdid FROM pwd WHERE contactsid=?";
    $q = $db->select($sqltext, array($contactsId), 'i');
    if (! empty($q)) {
        $r = $q[0];
        $ret = $r->pwdid;
    }
    
    return $ret;
}

?>