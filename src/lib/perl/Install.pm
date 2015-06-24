package Install;

# Web Modules Ltd. Athena Community Edition Software 2015
# https://github.com/athenasystems/athenace The Athena Systems GitHub project
# Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
# Version: 1.1177
# Released: Wed Jun 24 19:40:04 2015 GMT
# The MIT License (MIT)
# 
# Copyright (c) 2015 Web Modules Ltd. UK
# 
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
# 
# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.
# 
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.

use strict;

sub doPermissions() {
	my $athenaDir = shift;

	my $username = $ENV{LOGNAME} || $ENV{USER} || getpwuid($<);
	system("chown -R $username:root $athenaDir");
	system("chown -R www-data:$username $athenaDir/var/data");
	system("chmod -R 755 $athenaDir/*");
	system("chmod 644 $athenaDir/lib/*/*.php");
	system("chmod 644 $athenaDir/www/*/*.php");
	system("chmod 644 $athenaDir/www/*/*/*.php");
	system("");

}

sub section() {
	my $hmsg  = shift;
	my $msg   = shift;
	my $promt = shift;
	my $ret   = 1;

	#system("clear");
	my $spacer = '---------------------------------------------------';
	print "\n$spacer\n" . $hmsg . "\n$spacer\n";

	print "\n$msg";
	if ( $promt == 1 ) {
		print "\nHit 'Enter' to proceed or 'q' to quit ";
		my $ans = <STDIN>;
		chomp $ans;
		if ( $ans eq 'q' ) {
			exit;
		}
	}
	elsif ( $promt == 2 ) {
		print "\nHit 'Enter' to proceed or 's' to skip ";
		my $ans = <STDIN>;
		chomp $ans;
		if ( $ans eq 's' ) {
			return 0;
		}
	}
	else {

	}
	return $ret;
}

sub makeAthenaConf {

		# &makeAthenaConf($athenaDBpwd,$domain,$webmasterEmail,$dir)

	my $athenaDBpwd    = shift;
	my $domain         = shift;
	my $webmasterEmail = shift;
	my $dir            = shift;

	# Make Athena Configuration file
	my $conf = 'db=athenace
dbuser=athena
dbpw=DBPWD
host=localhost
domain=ATHENADOMAIN
athenaDir=ATHENADIR
webmasterEmail=WEBMASTEREMAIL
ssl=1
devserver=DEVSRV
liveserver=LIVESERVER';

	$conf =~ s/DBPWD/$athenaDBpwd/s;
	$conf =~ s/ATHENADOMAIN/$domain/s;
	$conf =~ s/WEBMASTEREMAIL/$webmasterEmail/s;
	$conf =~ s/ATHENADIR/$dir/s;

	if ( !-e "/etc/athenace" ) {
		system("mkdir /etc/athenace");
		system("mkdir /etc/athenace/httpd");
		system("mkdir /etc/athenace/ssl");
	}

	open( FHOUT, ">/etc/athenace/athena.conf" );
	print FHOUT $conf;
	close(FHOUT);

	system("chown www-data:root /etc/athenace/athena.conf");
	system("chmod 660 /etc/athenace/athena.conf");
}

sub setupDatabase {

	# &setupDatabase($dir,$athenarootpwd,$pwhash,$athenaDBpwd);

	my $dir             = shift;
	my $athenarootpwd   = shift;
	my $pwhash          = shift;
	my $athenaDBpwd     = shift;
	my $athenaRootDbPwd = shift;

	# Make SQL setup file
	my $sqlCore = &getSQL();

	$sqlCore =~ s/PASSWORD/$athenarootpwd/s;
	$sqlCore =~ s/PWDHASH/$pwhash/s;
	$sqlCore =~ s/ATHENAMYSQLPWD/$athenaDBpwd/s;

	my $sqlOutFile = '/tmp/Athena.SQL.' . time();

	open( FHOUT, ">$sqlOutFile" );
	print FHOUT $sqlCore;
	close(FHOUT);

	my $tmpFile = '/tmp/Athena.MyAcc' . time();

	open( FHOUT, ">$tmpFile" );
	print FHOUT "[mysql]\nuser=root\npassword=" . $athenaRootDbPwd;
	close(FHOUT);

	# Import DB into MySQL server
	my $sqlCmd = "mysql --defaults-extra-file=$tmpFile < $sqlOutFile";

	print "\n\nRunning the SQL script to import the database\n\n";

	system($sqlCmd);

	unlink($sqlOutFile);
	unlink($tmpFile);
}

sub addToHosts() {

	my $domain = shift;

	print "\n\nAdding the domain $domain to the /etc/hosts file\n\n";

	my $hosttext = '';
	open( FHH, "</etc/hosts" );
	while (<FHH>) {
		if ( !/$domain/ ) {
			$hosttext .= $_;
		}
	}
	close(FHH);

	$hosttext .= "
127.0.0.1       $domain
127.0.0.1       www.$domain
127.0.0.1       intranet.$domain
127.0.0.1       staff.$domain
127.0.0.1       customers.$domain
127.0.0.1       suppliers.$domain";

	open( FHOUT, ">/etc/hosts" );
	print FHOUT $hosttext;
	close(FHOUT);
}

sub setupApache() {

	# &setupApache($dir,$domain);

	my $dir    = shift;
	my $domain = shift;

	# Make Apache2 setup file

	my ( $apacheConf, $apacheSharedConf, $sslSharedTxt ) = &makeApacheConfs();
	$apacheConf =~ s/ATHENADIR/$dir/gs;
	$apacheConf =~ s/ATHENADOMAIN/$domain/gs;

	my $apacheOutFile = "/etc/athenace/httpd/athena.conf";

	open( FHOUT, ">$apacheOutFile" );
	print FHOUT $apacheConf;
	close(FHOUT);

	my $apacheAvailableConfFile = "/etc/apache2/sites-available/athena.conf";

	open( FHOUT, ">$apacheAvailableConfFile" );
	print FHOUT "Include /etc/athenace/httpd/athena.conf";
	close(FHOUT);

	$apacheSharedConf =~ s/ATHENADIR/$dir/gs;

	my $apacheSharedOutFile = "/etc/athenace/httpd/shared.conf";

	open( FHOUT, ">$apacheSharedOutFile" );
	print FHOUT $apacheSharedConf;
	close(FHOUT);

	my $apacheSharedSSLOutFile = "/etc/athenace/httpd/ssl.shared.conf";

	$sslSharedTxt =~ s/ATHENADOMAIN/$domain/gs;

	open( FHOUT, ">$apacheSharedSSLOutFile" );
	print FHOUT $sslSharedTxt;
	close(FHOUT);

	system("a2enmod cgi");
	system("a2enmod ssl");
	system("a2enmod rewrite");
	system("php5enmod mcrypt");
	system("a2ensite athena.conf");
	system("/etc/init.d/apache2 restart");
	system("service apache2 restart");

}

sub makeApacheConfs() {
	my $apacheTxt = q|<VirtualHost *:80>	
	ServerName ATHSITE.ATHENADOMAIN
	DocumentRoot ATHENADIR/www/ATHSITE
    
    RewriteEngine On
    RewriteCond %{SERVER_PORT} ^80$
    RewriteRule ^(.*)$ https://ATHSITE.ATHENADOMAIN%{REQUEST_URI} [L,R=301]
  
</VirtualHost>
<VirtualHost *:443>
ServerName ATHSITE.ATHENADOMAIN
ServerAlias www.ATHSITE.ATHENADOMAIN
	DocumentRoot ATHENADIR/www/ATHSITE

	<Directory ATHENADIR/www/ATHSITE/>
		AllowOverride None
		Options +Multiviews
		MultiviewsMatch Any
		Require all granted
    </Directory>	
    
	Include /etc/athenace/httpd/shared.conf	
	Include /etc/athenace/httpd/ssl.shared.conf
	
</VirtualHost>
|;

	my @sites = ( "customers", "intranet", "staff", "suppliers", "www" );
	my $apacheConfFile = '';
	my $cnt            = 0;
	foreach (@sites) {
		my $site           = $sites[$cnt];
		my $apacheTemplate = $apacheTxt;
		$apacheTemplate =~ s/ATHSITE/$site/gs;
		$apacheConfFile .= $apacheTemplate;
		$cnt++;
	}

	my $apacheSharedTxt = q|# Athena Shared Apache Configuration

ServerAdmin webmaster@localhost

AddHandler cgi-script .pl

ScriptAlias "/bin/" "ATHENADIR/lib/perl/bin/"

<Directory ATHENADIR/lib/perl/bin/>
	SetHandler cgi-script
    Options +ExecCGI
	Require all granted
</Directory>

php_admin_value open_basedir /tmp:ATHENADIR:/etc/athenace

Alias /bootstrap/ ATHENADIR/lib/pub/bootstrap/

<Directory ATHENADIR/lib/pub/bootstrap/>
	AllowOverride None
	Require all granted
</Directory>

ErrorLog ${APACHE_LOG_DIR}/error.log

LogFormat "%v %h %l %u %t \"%r\" %>s %b" comonvhost
CustomLog ATHENADIR/var/logs/access_log comonvhost
|;

	my $sslSharedTxt = 'SSLEngine on
SSLCertificateKeyFile /etc/athenace/ssl/ATHENADOMAIN.key
SSLCertificateFile /etc/athenace/ssl/ATHENADOMAIN.crt
SetEnvIf User-Agent ".*MSIE.*" nokeepalive ssl-unclean-shutdown;';

	return ( $apacheConfFile, $apacheSharedTxt, $sslSharedTxt );

}

sub getSQL() {

	my $athenaSQL = '';
	
	open (FH,"</srv/athenace/adm/t/rsc/athena.core.sql");
	while(<FH>){
		$athenaSQL .= $_;
	}
	close(FH);
	
	return ($athenaSQL);

}

sub makeSSL() {

	my $domain = shift;

	my $ssl_script = '#!/usr/bin/env bash

# Specify where we will install
# the athenace certificate
SSL_DIR="/etc/athenace/ssl"

# Set the wildcarded domain
# we want to use
DOMAIN="*.' . $domain . '"

# A blank passphrase
PASSPHRASE=""

# Set our CSR variables
SUBJ="
C=GB
ST=Leicestershire
O=
localityName=Leicester
commonName=$DOMAIN
organizationalUnitName=
emailAddress=
"

# Create our SSL directory
# in case it doesnt exist
sudo mkdir -p "$SSL_DIR"

# Generate our Private Key, CSR and Certificate
sudo openssl genrsa -out "$SSL_DIR/' . $domain . '.key" 2048
sudo openssl req -new -subj "$(echo -n "$SUBJ" | tr "\n" "/")" -key "$SSL_DIR/'
	  . $domain
	  . '.key" -out "$SSL_DIR/'
	  . $domain
	  . '.csr" -passin pass:$PASSPHRASE
sudo openssl x509 -req -days 365 -in "$SSL_DIR/' 
	  . $domain
	  . '.csr" -signkey "$SSL_DIR/'
	  . $domain
	  . '.key" -out "$SSL_DIR/'
	  . $domain . '.crt"
';

	my $tmpFile = '/tmp/Athena.SSL.' . time() . '.sh';

	open( FH, ">$tmpFile" );
	print FH $ssl_script;
	close(FH);
	system("chmod +x $tmpFile");

	system("/bin/sh $tmpFile ");

}

sub mkPwd() {

	my $pwd;
	my @my_char_list = ( ( 'A' .. 'Z' ), ( 'a' .. 'z' ), ( 0 .. 9 ) );
	my $range_dis = $#my_char_list + 1;
	for ( 1 .. 17 ) {
		$pwd .= $my_char_list[ int( rand($range_dis) ) ];
	}
	return $pwd;
}

sub make_tmp_sec_file() {

	my $secFile = shift;
	my $secBody = q|<?php
parse_str(implode('&', array_slice($argv, 1)), $_GET);
$salt = substr(str_replace('+', '.', base64_encode(md5(mt_rand(), true))), 0, 16);
$rounds = 10000;
$given_hash = crypt($_GET['pw'], sprintf('$5$rounds=%d$%s$', $rounds, $salt));
echo $given_hash;?>
|;

	open( FHOUT, ">$secFile" );
	print FHOUT $secBody;
	close(FHOUT);
}

1;
