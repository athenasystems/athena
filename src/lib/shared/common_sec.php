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

// Sec Globals
$sec_key = "gg65RxmMmJjk9Io0OhR4eDit"; // 24 bit Key
$sec_iv = "fYfhHeDm"; // 8 bit IV
$sec_bit_check = 8;
// bit amount for diff algor.
function mkPwd($pw)
{
    $salt = substr(str_replace('+', '.', base64_encode(md5(mt_rand(), true))), 0, 16);
    $rounds = 10000;
    
    $pwd = crypt($pw, sprintf('$5$rounds=%d$%s$', $rounds, $salt));
    return $pwd;
}

function pass($usr, $pw, $kind = 'staff')
{
    global $db;
    
    $field = $kind . 'id';
    
    $sqltext = "SELECT $field,pw FROM pwd WHERE usr=?";
    // print $sqltext;exit;
    $q = $db->select($sqltext, array(
        $usr
    ), 's');
    $r = $q[0];
    
    $parts = explode('$', $r->pw);
    $test_hash = crypt($pw, sprintf('$%s$%s$%s$', $parts[1], $parts[2], $parts[3]));
    
    // compare
    // echo $given_hash . "\n" . $test_hash . "\n" . var_export($given_hash === $test_hash, true);
    
    if ($test_hash == $r->pw) {
        return $r->$field;
    } else {
        failOut('failed_pass');
    }
}

function dropCookie($usrid, $usr, $pw)
{
    //
    // Cookie Format
    // $usrid , $user , $pw, time()
    // where $usrid is either a staffid or a contactsid
    //
    $now = time();
    $line = $usrid . '.' . $usr . '.' . $pw . '.' . $now;
    $value = encrypt($line);
    // echo $line ."\n";exit;
    // $value = $line;
    setcookie("ATHENA", $value, time() + 7200, '/'); /* expire in 2 hour */
}

function chkCookie($kind = 'staff')
{
    //
    // Cookie Format
    // $usrid , $user , $pw, time()
    // where $usrid is either a staffid or a contactsid
    //
    global $db;
    if (! isset($_COOKIE["ATHENA"])) {
        failOut('cookie_not_set');
    }
    $cke = decrypt($_COOKIE["ATHENA"]);
    // $cke = $_COOKIE["ATHENA"];
    
    $keywords = preg_split("/\./", $cke);
    
    $usrid = $keywords[0];
    $usr = $keywords[1];
    $pw = $keywords[2];
    
    if (! pass($usr, $pw, $kind)) {
        failOut('cookie_pass_failed');
    } else {
        dropCookie($usrid, $usr, $pw);
        $retID = $kind . 'id';
        $r[$retID] = $usrid;
        return $r;
    }
    return 0;
}

function killCookie()
{
    setcookie("ATHENA", '', time() - 3600); /* expire 1 hour ago */
}

function encrypt($text)
{
    global $sec_key;
    global $sec_iv;
    global $sec_bit_check;
    
    $key=$sec_key;
    $iv=$sec_iv;
    $bit_check=$sec_bit_check;
    
    $text_num = str_split($text, $bit_check);
    $text_num = $bit_check - strlen($text_num[count($text_num) - 1]);
    for ($i = 0; $i < $text_num; $i ++) {
        $text = $text . chr($text_num);
    }
    $cipher = mcrypt_module_open(MCRYPT_TRIPLEDES, '', 'cbc', '');
    mcrypt_generic_init($cipher, $key, $iv);
    $decrypted = mcrypt_generic($cipher, $text);
    mcrypt_generic_deinit($cipher);
    return base64_encode($decrypted);
}

function decrypt($encrypted_text)
{
    global $sec_key;
    global $sec_iv;
    global $sec_bit_check;
    
    $key=$sec_key;
    $iv=$sec_iv;
    $bit_check=$sec_bit_check;
    $cipher = mcrypt_module_open(MCRYPT_TRIPLEDES, '', 'cbc', '');
    mcrypt_generic_init($cipher, $key, $iv);
    $decrypted = mdecrypt_generic($cipher, base64_decode($encrypted_text));
    mcrypt_generic_deinit($cipher);
    $last_char = substr($decrypted, - 1);
    for ($i = 0; $i < $bit_check - 1; $i ++) {
        if (chr($i) == $last_char) {
            
            $decrypted = substr($decrypted, 0, strlen($decrypted) - $i);
            break;
        }
    }
    return $decrypted;
}

function failOut($why)
{
    global $domain;
    // TODO Log this
    
    header("Location: https://www.$domain/login?pf=y&w=$why");
    
    exit();
}

?>