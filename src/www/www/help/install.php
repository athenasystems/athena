<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1171
Released: Wed Jun 24 17:40:21 2015 GMT
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
$pagetitle = 'Documentation Page';
$navtitle = 'Documentation';
$keywords = '';
$description = '';
include "../tmpl/header.php";

?>

<h1>Athena Help Files</h1>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3>To set up Athena CE Development Environment (TL;DR Version)</h3>
	</div>
	<div class="panel-body">

		<p>Pick your favourite Linux distro</p>
		<code>
			<pre>
wget https://raw.githubusercontent.com/athenasystems/athenace/master/athena-setup
sudo bash athena-setup
</pre>
		</code>
		Follow instructions
	</div>
</div>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3>Athena Installation Details</h3>
	</div>
	<div class="panel-body">
		<p>
			There is a setup bash script available at <a
				href="https://github.com/athenasystems/athenace">https://github.com/athenasystems/athenace</a>
		</p>
	</div>
</div>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3>Configure</h3>
	</div>
	<div class="panel-body">

		<p>The first part of the installation prepares the operating system
			for use with Athena. It checks that all the required programs are
			already installed and installs those which are missing.</p>

		<ul>
			<li>Programs that will be needed include:-
				<p>apache2 mysql-server php5 libapache2-mod-php5
					libapache2-mod-auth-mysql expect libcrypt-ssleay-perl
					libexpect-perl php5-mysql php5-mcrypt libtest-www-mechanize-perl
					liblocale-currency-format-perl libpdf-create-perl libcrypt-cbc-perl
					libdatetime-perl</p>
			</li>

		</ul>

	</div>
</div>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3>Athena Directory</h3>
	</div>
	<div class="panel-body">

		<p>The default is /srv/athenace which should be available on most
			systems, but you are free to choose your own location. Depending on
			how you like to code, it can make life easier if you decide to use
			the same directory on both the development and server versions. If
			you choose a folder that already exists, the script will prompt for
			confirmation before it deletes everything in there.</p>
	</div>
</div>


<div class="panel panel-info">
	<div class="panel-heading">
		<h3>Choosing the Development Domain Name</h3>
	</div>
	<div class="panel-body">

		<p>The default is to use athenace.uk and point this domain via your
			/etc/hosts file to your own computer, i.e.127.0.0.1, so that requests
			for this domain name never leave your computer. Consequently you can
			choose whatever name you like here.</p>
	</div>
</div>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3>MySQL Password</h3>
	</div>
	<div class="panel-body">

		<p>The install script will temporarily need your MySQL root password
			to create the database and populate it with data. The database will
			be called 'athenace'. Once installation is done the default database
			user is named 'athena' who will have been granted permissions only
			for the athenace database, and only from the localhost.</p>
	</div>
</div>


<div class="panel panel-info">
	<div class="panel-heading">
		<h3>Getting the source files</h3>
	</div>
	<div class="panel-body">

		<p>The install script will next download the source files from
			http://www.athenace.co.uk and unpack them and then move them to the
			directory you specified. Then chown and chmod are run to create the
			right ownership and file permissions.</p>
	</div>
</div>


<div class="panel panel-info">
	<div class="panel-heading">
		<h3>Setting Up /etc/hosts</h3>
	</div>
	<div class="panel-body">

		<p>
			<code>
				<pre>

127.0.0.1       $domain
127.0.0.1       www.$domain
127.0.0.1       intranet.$domain
127.0.0.1       staff.$domain
127.0.0.1       customers.$domain
127.0.0.1       suppliers.$domain

</pre>
			</code>

			where $domain is the name you chose earlier
		</p>
	</div>
</div>


<div class="panel panel-info">
	<div class="panel-heading">
		<h3>Athena Configuration</h3>
	</div>
	<div class="panel-body">

		<p>
			Athena keeps a configuration file in /etc/athenace/athena.conf
			containing several entries specific to your installation. This file
			should have the right permissions and not be readable to normal
			users. The file looks something like:-
			<code>
				<pre>
db=athenace
dbuser=athena
dbpw=DBPWD
domain=ATHENADOMAIN
athenaDir=ATHENADIR
webmasterEmail=WEBMASTEREMAIL
ssl=1
devserver=DEVSRV
liveserver=LIVESERVER
</pre>
			</code>

			and the capitlised words are replaced at installation time with
			options you have chosen, or that where randomly generated for your
			version of Athena.
		</p>
	</div>
</div>


<div class="panel panel-info">
	<div class="panel-heading">
		<h3>Setting Up the MySQL database</h3>
	</div>
	<div class="panel-body">

		<p>Within the setup script is the SQL to create the database. This
			will now be imported into your MySQL server and GRANT statements will
			run to allow permissions for the 'athena' user to access the
			'athenace' database.</p>
	</div>
</div>


<div class="panel panel-info">
	<div class="panel-heading">
		<h3>SSL Certificate</h3>
	</div>
	<div class="panel-body">

		<p>Next we will create a wildcard SSL certificate for your apache for
			the *.$domain you chose earlier. This is just a self signed SSL
			certificate for development. You will probably need to buy one for
			your live installation. These keys will be stored in
			'/etc/athenace/ssl'. When you use them for the first time your
			browser will ask you to "Confirm Exception" or similar.</p>
	</div>
</div>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3>Setting up the Apache Web Server</h3>
	</div>
	<div class="panel-body">

		<p>Next the Apache2 configurations files will be created and stored in
			'/etc/athenace/httpd'. This will create 5 different sub domains under
			the domain you chose. "customers", "intranet", "staff", "suppliers",
			"www" subdomains will be needed for a full Athena system, however you
			can disable some of these if you dont need them later. A file named
			/etc/apache2/sites-available/athena.conf will be created that will
			contain the line 'Include /etc/athenace/httpd/athena.conf' which will
			be used to start the configuration using the 'a2ensite' feature
			within Apache2</p>
	</div>
</div>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3>Populate Athena with Dummy Data</h3>
	</div>
	<div class="panel-body">

		<p>In order to test Athena effectively you may well need some dummy
			data to start things off. We provide a feature that uses the Athena
			Test Suite to populate your install of Athena with customers,
			suppliers contacts and a few quotes and invoices to get things
			working. The test suite should be a vital part of the development
			process, and keeping on top of testing will always pay off in the
			end.</p>
	</div>
</div>


<div class="panel panel-info">
	<div class="panel-heading">
		<h3></h3>
	</div>
	<div class="panel-body">

		<p></p>
	</div>
</div>




<?php

include "../tmpl/footer.php";

?>