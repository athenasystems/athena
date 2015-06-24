<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1172
Released: Wed Jun 24 17:40:52 2015 GMT
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
$addDetails = array (
		'add1',
		'add2',
		'add3',
		'city',
		'county',
		'country',
		'postcode',
		'tel',
		'mob',
		'fax',
		'email',
		'web',
		'facebook',
		'twitter',
		'linkedin' 
);
foreach ( $addDetails as $field ) {
	if (! isset ( $_POST [$field] )) {
		$_POST [$field] = '';
	}
}

echo '<h3>Contact Details</h3>';

html_text ( "Tel", "tel", $_POST ['tel'] );

html_text ( "Fax", "fax", $_POST ['fax'] );

html_text ( "Mobile", "mob", $_POST ['mob'] );

html_text ( "Email", "email", $_POST ['email'] );

echo '<h3>Address</h3>';

html_text ( "Address", "add1", $_POST ['add1'] );

html_text ( "&nbsp;", "add2", $_POST ['add2'] );

html_text ( "&nbsp;", "add3", $_POST ['add3'] );

html_text ( "City", "city", $_POST ['city'] );

html_text ( "County", "county", $_POST ['county'] );

html_text ( "Country", "country", $_POST ['country'] );

html_text ( "Postcode", "postcode", $_POST ['postcode'] );

html_text ( "Web", "web", $_POST ['web'] );

html_text ( "Facebook", "facebook", $_POST ['facebook'] );

html_text ( "Twitter", "twitter", $_POST ['twitter'] );

html_text ( "Linkedin", "linkedin", $_POST ['linkedin'] );

?>