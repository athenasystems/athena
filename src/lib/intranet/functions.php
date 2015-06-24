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

function generateContactlogon($fname, $sname)
{
    $initial1 = $fname;
    $initial2 = substr($sname, 0, 1);
    $logon = strtolower($initial1 . $initial2);
    $cnt = 1;
    while (! chkLogonNameIsUnique($logon)) {
        if ($cnt > 100) {
            $logon = substr($logon, 0, - 3);
        } elseif ($cnt > 10) {
            $logon = substr($logon, 0, - 2);
        } elseif ($cnt > 1) {
            $logon = substr($logon, 0, - 1);
        } else {}
        $logon = $logon . $cnt;
        $cnt ++;
    }
    
    return $logon;
}

function generateStafflogon($fname, $sname)
{
    $initial1 = substr($fname, 0, 1);
    $initial2 = substr($sname, 0, 1);
    $logon = strtolower($initial1 . $initial2);
    $cnt = 1;
    while (! chkLogonNameIsUnique($logon)) {
        if ($cnt > 100) {
            $logon = substr($logon, 0, - 3);
        } elseif ($cnt > 10) {
            $logon = substr($logon, 0, - 2);
        } elseif ($cnt > 1) {
            $logon = substr($logon, 0, - 1);
        } else {}
        $logon = $logon . $cnt;
        $cnt ++;
    }
    
    return $logon;
}

function getTypeFromContact($contactsID)
{
    global $db;
    $sqltext = "SELECT custid,suppid FROM contacts WHERE contactsid=?";
    // print $sqltext;exit;
    $q = $db->select($sqltext,array($contactsID),'i') ;
    $r = $q[0];
    $suppid = $r->suppid;
    $custid = $r->custid;
    
    if (isset($custid) && is_numeric($custid) && ($custid > 99)) {
        $ret['type'] = 'cust';
        $ret['id'] = $custid;
        return $ret;
    } elseif (isset($suppid) && is_numeric($suppid) && ($suppid > 99)) {
        $ret['type'] = 'supp';
        $ret['id'] = $suppid;
        return $ret;
    } else {
        $ret['type'] = 'none';
        $ret['id'] = 0;
        return $ret;
    }
}

function chkLogonNameIsUnique($login)
{
    global $db;
    
    $sqltext = 'SELECT usr FROM pwd WHERE usr=?';
    // print $sqltext;
    
    $q = $db->select($sqltext,array($login),'s');
   if (! empty($q)) {
        return false;
    } else {
        return true;
    }
}

function getSuppName($suppid)
{
    global $db;
    
    $sqltext = "SELECT co_name FROM supplier WHERE suppid=?";
    $q = $db->select($sqltext,array($suppid),'i') ;
   if (! empty($q)) {
        
        $r = $q[0];
        $ret = $r->co_name;
    }
    
    return $ret;
}

function getCustName($custid)
{
    global $db;
    
    $sqltext = "SELECT co_name FROM customer WHERE custid=?";
    $q = $db->select($sqltext,array($custid),'i') ;
   if (! empty($q)) {
        
        $r = $q[0];
        $ret = $r->co_name;
    }
    
    return $ret;
}

function getCustEmailAdd($custid)
{
    global $db;
    $sqltext = "SELECT email FROM customer,address WHERE custid=? AND customer.addsid=address.addsid LIMIT 1";
    // print $sqltext;exit;
    $q = $db->select($sqltext,array($custid),'i') ;
    $r = $q[0];
    $r = $r->email;
    
    return $r;
}

function getEventName($eventsid)
{
    global $db;
    $sqltext = "SELECT name FROM events WHERE eventsid=?";
    $q = $db->select($sqltext,array($eventsid),'i') ;
    $r = $q[0];
    return $r->name;
}
?>