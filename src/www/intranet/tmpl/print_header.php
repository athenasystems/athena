<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>
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
echo $pagetitle;
?>
</title>

</head>
<body>

	<br clear=all>

	<div style="width: 720px;">
		<div style="float:right;">
			<h2><?php echo $owner->co_name; ?></h2>
	  
	  	<?php echo $owner->add1; ?>,
		<?php echo $owner->add2; ?>
		<?php echo $owner->add3; ?><br>
		<?php echo $owner->city; ?>,
		<?php echo $owner->county; ?>,
		<?php echo $owner->country; ?>,
		<?php echo $owner->postcode; ?><br>
		Tel: <?php echo $owner->tel; ?><br>
		Fax: <?php echo $owner->fax; ?><br>
	  Co. Reg. No:<?php echo $owner->co_no; ?><br>
	  V.A.T. No:<?php echo $owner->vat_no; ?><br>
	  Email: <?php echo $owner->email; ?><br>
	  Website: <?php echo $owner->web; ?><br>
			
		</div>

	</div>
	<br clear=all><br><br>