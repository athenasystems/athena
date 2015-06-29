<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1179
Released: Mon Jun 29 09:29:29 2015 GMT
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

if((isset($adds->add1))&&($adds->add1!='')){
	echo $adds->add1.'<br>';
}

if((isset($adds->add2))&&($adds->add2!='')){
	echo $adds->add2.'<br>';
}
if((isset($adds->add3))&&($adds->add3!='')){
	echo $adds->add3.'<br>';
}
if((isset($adds->city))&&($adds->city!='')){
	echo $adds->city.'<br>';
}

if((isset($adds->county))&&($adds->county!='')){
	echo $adds->county.'<br>';
}

if((isset($adds->country))&&($adds->country!='')){
	echo $adds->country.'<br>';
}

if((isset($adds->postcode))&&($adds->postcode!='')){
	echo $adds->postcode.'<br>';
}

if((isset($adds->tel))&&($adds->tel!='')){
	echo 'Tel: '.$adds->tel.'<br>';
}

if((isset($adds->fax))&&($adds->fax!='')){
	echo 'Fax: '.$adds->fax.'<br>';
}

if((isset($adds->email))&&($adds->email!='')){
	echo 'Email: '.$adds->email.'<br>';
}

if((isset($adds->web))&&($adds->web!='')){
	echo 'Web: ' . $adds->web.'<br>';
}

if((isset($adds->facebook))&&($adds->facebook!='')){
	echo 'Facebook: ' . $adds->facebook.'<br>';
}

if((isset($adds->twitter))&&($adds->twitter!='')){
	echo 'Twitter: ' . $adds->twitter.'<br>';
}

if((isset($adds->linkedIn))&&($adds->linkedIn!='')){
	echo 'LinkedIn: ' . $adds->linkedIn.'<br>';
}

?>