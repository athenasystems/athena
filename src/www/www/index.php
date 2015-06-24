<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1170
Released: Wed Jun 24 17:33:12 2015 GMT
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
$pagetitle = 'Home Page';
$navtitle = 'Home';
$keywords = '';
$description ='';

include "tmpl/header.php";
?>

<h1>Athena Community Edition</h1>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3>Business Software Framework</h3>
	</div>
	<div class="panel-body">
		<p>This software is a boiler plate web based business system that sets
			you up with the required framework to develop the features a business
			needs.</p>
		<p>It is designed to run on the LAMP stack, specifically designed and
			tested on Ubuntu Desktop and Server versions, but can run on any
			Linux based system.</p>
		<p>The majority of the programming code is in simple to understand,
			consistent, PHP. We have tried to write the code in the simplest way
			possible as it is intended primarily to be further developed by other
			programmers. The Test Suite provided is written in Perl using
			WWW::Mechanize.</p>
		<p>The front end HTML is based on the Bootstrap Web framework by
			Twitter. This allows for speedy, industry standard development
			optimised for a range of devices, mobile, tablet and PCs. The design
			has been made as simple as possible, again, intending for each
			company to have their own preference.</p>
	</div>
</div>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3>Why build it like this?</h3>
	</div>
	<div class="panel-body">

		<p>One of the problems with writing software for business is no two
			businesses are the same. Generic business software cannot exist
			without being bloated with a zillion options, and even then it never
			quite works as your company does. However to actually implement a
			system designed specifically for your company's principles and
			practices is not that hard, and can produce very simple to use,
			intuitive designs, without any options that are not relevent. The
			software then becomes easy to use as well as easy to maintain and
			adapt.</p>
	</div>
</div>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3>What you get...</h3>
	</div>
	<div class="panel-body">
		<ul>
			<li>Company Intranet</li>
			<li>Staff, Customer, Supplier, Portal</li>
			<li>Basic Quotes and Invoices Modules</li>
			<li>Users Guides for
			<ul>
			<li>Users</li>
			<li>Athena Project Managers</li>
			<li>Athena Coders</li>
			</ul></li>
			<li>Documentation for
			<ul>
			<li>Installation</li>
			<li>Modules</li>
			</ul>
			</li>
			<li>Setup script to configure your computer and set up a development
				environment</li>
			<li>Test Suite to run tests on, and populate a development version</li>
			<li>Script to configure a live server</li>
			<li>Scripts to upload new development to live site</li>
		</ul>
	</div>
</div>

<?php

include "tmpl/footer.php";

?>