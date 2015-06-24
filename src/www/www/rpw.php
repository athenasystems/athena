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
include "/srv/athenace/lib/shared/common.php";

if (!isset($_GET['t'])){
    header("Location: /");
    exit();
}

$t = decrypt(urldecode($_GET['t']));
$prms = preg_split("/\|/", $t);

echo "{$prms[0]} {$prms[0]}";exit;

if ($prms[0] < (time() - (60 * 60))) 
{
    
    header("Location: /resetpass?oldreq=y");
    exit();
}

$status = '<div class="alert alert-info" role="alert">Please choose and confirm a new Password</div>';

$pw_changed = 0;

if ((isset($_GET['go'])) && ($_GET['go'] == "y")) {
    
    if ($_POST['pw'] != $_POST['pw2']) {
        $status = '<div class="alert alert-danger" role="alert">The two passwords you just entered are not the same. <br>Please try again...</div>';
    } else {
        
        if (strlen($_POST['pw']) < 8) {
            $status = '<div class="alert alert-danger" role="alert">The Passwords you just entered is <strong>not long enough</strong>. <br>Please try again...</div>';
        } elseif (preg_match("/[A-Z]/", $_POST['pw']) === 0) {
            $status = '<div class="alert alert-danger" role="alert">The Passwords you just entered does not contain an <strong>uppercase letter</strong>. <br>Please try again...</div>';
        } elseif (preg_match("/[a-z]/", $_POST['pw']) === 0) {
            $status = '<div class="alert alert-danger" role="alert">The Passwords you just entered does not contain a <strong>lowercase letter</strong>. <br>Please try again...</div>';
        } elseif (preg_match("/\d/", $_POST['pw']) === 0) {
            $status = '<div class="alert alert-danger" role="alert">The Passwords you just entered does not contain a <strong>number</strong>. <br>Please try again...</div>';
        } 
        else {
            
            $sqltext = "SELECT staffid FROM staff,address WHERE email=?  AND staff.addsid=address.addsid";
            #print $sqltext;
            $q = $db->select($sqltext,array($prms[1]),'s') ;
            
            $r = $q[0];
           if (! empty($q)) {
                
                $dbvaluePwd = mkPwd($_POST['pw']);
                $q = $db->update('pwd',array('pw'=>$dbvaluePwd),'s',array('staffid'=>$r->staffid),'i') ;
                $pw_changed ++;
            } else {
                $sqltext = "SELECT contactsid FROM contacts,address WHERE email=? AND contacts.addsid=address.addsid";
                $q = $db->select($sqltext,array($prms[1],'s')) ;
                $r = $q[0];
               if (! empty($q)) {
                        
                    $dbvaluePwd = mkPwd($_POST['pw']);
                    
                    //$sqltext = "UPDATE pwd SET pw='$dbvaluePwd' WHERE contactsid=" . $r->contactsid;
                    
                    $q = $db->update('pwd',array('pw'=>$dbvaluePwd),'s',array('contactsid'=>$r->contactsid),'i') ;
                    
                    $pw_changed ++;
                }
            }
        }
        if ($pw_changed) {
            header("Location: /index.php?pwchngd=y");
            exit();
        }
    }
}

include "tmpl/header.php";
?>
<?php echo 'Issued: ' .date('l jS \of F Y h:i:s A',$prms[0]);?>
<h2 class="form-signin-heading">Athena Reset Password</h2>
<?php echo $status;?>

<form role="form" class="form-signin"
	action="<?php echo $_SERVER['PHP_SELF']?>?go=y&t=<?php echo urlencode($_GET['t']);?>"
	method="post" enctype="application/x-www-form-urlencoded">


	<label for="inputPassword" class="sr-only">Password</label> <input
		type="password" id="pw" name="pw" class="form-control"
		placeholder="Password" required> <label for="inputPassword"
		class="sr-only">Confirm Password</label> <input type="password"
		id="pw2" name="pw2" class="form-control"
		placeholder="Confirm Password" required>


	<button class="btn btn-lg btn-success btn-block" type="submit">Save New
		Password</button>


</form>

<?php

include "tmpl/footer.php";

?>