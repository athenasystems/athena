package Athena;

# Web Modules Ltd. Athena Community Edition Software 2015
# https://github.com/athenasystems/athenace The Athena Systems GitHub project
# Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
# Version: 1.1179
# Released: Mon Jun 29 09:29:29 2015 GMT
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

sub athenaConfig {
	my %config;
	open( FH, "</etc/athenace/athena.conf" );
	while (<FH>) {
		chomp;
		my @cnfs = split( /=/, $_ );
		$config{ $cnfs[0] } = $cnfs[1];
	}
	close(FH);
	return %config;
}

sub dbconnect {
	my %config = &Athena::athenaConfig;
	my $db     = $config{'db'};
	my $pwd    = $config{'dbpw'};
	my $user   = $config{'dbuser'};
	my $dbh =
	  DBI->connect( "DBI:mysql:$db:localhost", $user, $pwd,
		{ RaiseError => 1 } );
	return ($dbh);
}

sub getOwnerDetails {
	my $dbh = shift;

	my $sqltext =
	  "SELECT * FROM owner,address WHERE owner.addsid=address.addsid";

	my $sth = $dbh->prepare($sqltext);
	$sth->execute();
	my $rItems = $sth->fetchrow_hashref;
	my %r      = %{$rItems};

	return %r;
}

sub getDataDir {
	my %config = &Athena::athenaConfig;

	my $d = $config{'athenaDir'} . '/var/data';

	return $d;
}

sub getVAT_Rate {
	my $vat_incept        = shift;
	my $vat_rate          = 0;
	my $vat_change_date_1 = 1294099200;    # From 17.5% to 20% on 4/1/2011

	if ( $vat_incept < $vat_change_date_1 ) {
		$vat_rate = 0.175;
	}
	else {
		$vat_rate = 0.2;
	}
	return $vat_rate;
}

sub getVatText {
	my $vat_rate = shift;
	my $vatTxt   = ( $vat_rate * 100 );
	$vatTxt = $vatTxt . '%';
	return $vatTxt;
}

sub getCustExtName {
	my $dbh        = shift;
	my $contactsid = shift;

	if ( ( !defined($contactsid) ) || ( $contactsid == 0 ) ) {
		return 'Unknown';
	}
	my $sqltext =
	  "SELECT fname,sname FROM contacts WHERE contactsid=" . $contactsid;

	my $sth = $dbh->prepare($sqltext);
	$sth->execute();
	my $rName = $sth->fetchrow_hashref;
	my %r     = %{$rName};
	my $ret   = $r{'fname'} . ' ' . $r{'sname'};

	return $ret;
}

sub getHostName {

	#
	# Decide URL
	#

	my $ssl    = shift;
	my $prefix = 'https';
	if ( defined($ssl) && ( $ssl == 'http' ) ) {
		$prefix = 'https';
	}

	my $webhost   = '';
	my $inthost   = '';
	my $custhost  = '';
	my $supphost  = '';
	my $admhost   = '';
	my $staffhost = '';

	my %config = &Athena::athenaConfig;
	my $domain = $config{'domain'};

	$webhost   = $prefix . '://www.' . $domain;
	$inthost   = $prefix . '://intranet.' . $domain;
	$custhost  = $prefix . '://customers.' . $domain;
	$supphost  = $prefix . '://suppliers.' . $domain;
	$staffhost = $prefix . '://staff.' . $domain;

	return ( $webhost, $inthost, $custhost, $supphost, $staffhost );
}

sub generatePwd {
	my $length   = shift;
	my $password = '';
	my $possible = 'abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
	while ( length($password) < $length ) {
		$password .=
		  substr( $possible, ( int( rand( length($possible) ) ) ), 1 );
	}
	return $password;
}

sub login {

	my ( $mech, $logon, $pass, $host, $sitesid ) = @_;

	my $uri = $host . '/login.php';

	#print "Tried:" . $uri . "\n";

	$mech->get($uri);

	#print $mech->content();

	my %fields = (
		sid  => $sitesid,
		nick => $logon,
		pw   => $pass
	);

	$mech->submit_form( with_fields => \%fields );

	#print "Tried:" . $logon . ':' . $pass . "\n";
	#print "Got to ..." . $mech->uri() . "\n";

	#
	return $mech;

}

sub backUpLocal() {

	my $athDir = shift;

	my $tmpDir   = '/tmp';
	my $filename = 'AthenaDev.' . time() . '.tar.gz';
	my $fileto   = $athDir . '/var/backup';
	&Install::section( "Backup", "Making a Back up to $fileto/$filename", 0 );

	system("tar -czf $tmpDir/$filename $athDir");
	system("mv $tmpDir/$filename $fileto");
}

1;
