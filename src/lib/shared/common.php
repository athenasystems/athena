<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1163
Released: Wed Jun 24 17:07:27 2015 GMT
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
error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL);
ini_set("display_errors", 1);

$config = parse_ini_file('/etc/athenace/athena.conf');
$domain = $config['domain'];
$athenaDir = $config['athenaDir'];
$webmasterEmail = $config['webmasterEmail'];

define('ROOT', '/srv/athenace');

include "/srv/athenace/lib/class/DB.php";
$db = new DB();

include "/srv/athenace/lib/class/Classes.php";
include "/srv/athenace/lib/shared/common_sec.php";
include "/srv/athenace/lib/shared/functions.php";

$SSLon = $config['ssl'];

$prefix='http';
if ($SSLon) {
   $prefix='https';
}

$www_url = "$prefix://www.$domain";
$int_url = "$prefix://intranet.$domain";
$cust_url = "$prefix://customers.$domain";
$supp_url = "$prefix://suppliers.$domain";
$staff_url = "$prefix://staff.$domain";
$login_url = "$prefix://www.$domain/login";

$pagescript = array();
$pagestyle = array();

$dataDir = $athenaDir . "/var/data";
?>