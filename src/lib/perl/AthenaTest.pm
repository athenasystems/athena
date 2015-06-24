package AthenaTest;

# Web Modules Ltd. Athena Community Edition Software 2015
# https://github.com/athenasystems/athenace The Athena Systems GitHub project
# Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
# Version: 1.1162
# Released: Wed Jun 24 17:05:47 2015 GMT
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

our $athenaDir = '';
open( FH, "</etc/athenace/athena.conf" );
while (<FH>) {
	chomp;
	if (/athenaDir/) {
		my ( $lbl, $d ) = split( /=/, $_ );
		$athenaDir = $d;
	}
}
close(FH);

sub login {
	use MIME::Base64;

	my ( $mech, $logon, $pass, $host ) = @_;

	my $uri = $host . '/login.php';

	#print "Tried:" . $uri . "\n";

	$mech->get($uri);

	#print $mech->content();

	my $hiddenPW = encode_base64($pass);

	my %fields = (
		user => $logon,
		pw   => $pass
	);

	$mech->submit_form( with_fields => \%fields );

	#print "Tried:" . $logon . ':' . $pass . "\n";
	#print "Got to ..." . $mech->uri() . "\n";

	#
	return $mech;

}

sub generate_random_sentence {
	return
'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat';
}

sub generate_random_string {
	my $length_of_randomstring = shift;    # the length of
	                                       # the random string to generate

	my @chars = ( 'a' .. 'z', 'A' .. 'Z', '0' .. '9', '_' );
	my $random_string;
	foreach ( 1 .. $length_of_randomstring ) {

		# rand @chars will generate a random
		# number between 0 and scalar @chars
		$random_string .= $chars[ rand @chars ];
	}
	return $random_string;
}

sub cleanText {
	my $text = shift;
	$text =~ s/'/\\'/gs;
	return $text;

}

sub getPhoneNumber {
	return
	    '+44' . ' '
	  . int( rand(1000) + 800 ) . ' '
	  . int( rand(100000) + 80000 );
}

sub getStreetName {

	my @nms = ();
	open( FHMQ, "<$athenaDir/adm/t/rsc/streets" );
	while (<FHMQ>) {
		chomp;
		s/\r//g;
		tr/[A-Z]/[a-z]/;
		s/\b([a-z])/\u$1/g;
		push( @nms, $_ );
	}
	close(FHMQ);

	my $ret = $nms[ ( int( rand( @nms + 1 ) ) ) ];

	return $ret;
}

sub getAddress {

	open( FHM, "<$athenaDir/adm/t/rsc/uk_postcodes.csv" );

	my $toGet = int( rand(2849) );
	my $cnt   = 0;
	my ( $postcode, $town, $county, $country );
	while (<FHM>) {
		$cnt++;
		if ( $cnt != $toGet ) { next; }
		chomp;
		s/\r//g;

		#print "\nhere\n";

		#s/\b([a-z])/\u$1/g;
		( $postcode, $town, $county, $country ) = split( /,/, $_ );
		last;
	}
	close(FHM);
	my $strName = int( rand(300) ) . ' ' . getStreetName;
	$postcode =
	  $postcode . ' ' . uc( generate_random_string(2) ) . int( rand(100) );
	if ( $town eq '' ) { $town = $county; }

	#print "\n$strName\n";
	return ( $strName, $town, $county, $country, $postcode );
}

sub getAdminLogin {
	my $pass = '';
	open( FH, "</etc/athenace/root" );
	while (<FH>) {
		chomp;
		$pass = $_;
	}
	close(FH);

	my $admin_logon = 'root';

	return ( $admin_logon, $pass );
}

sub getSuppLogin {

	my $dbh    = shift;
	my $suppid = shift;

	my $statement = "SELECT logon,init_pw FROM contacts ";
	if ( defined($suppid) ) {
		$statement .= " WHERE suppid=$suppid";
	}
	else {
		$statement .= " WHERE suppid IS NOT NULL AND suppid<>0 ";

	}

	$statement .= " ORDER BY contactsid LIMIT 1";

	my ( $supp_logon, $pass ) = $dbh->selectrow_array($statement);

	return ( $supp_logon, $pass );
}

sub getCustLogin {

	my $dbh    = shift;
	my $custid = shift;

	my $statement = "SELECT logon,init_pw FROM contacts ";
	if ( defined($custid) ) {
		$statement .= " WHERE custid=$custid";
	}
	else {
		$statement .= " WHERE custid IS NOT NULL AND custid<>0 ";

	}

	$statement .= " ORDER BY contactsid LIMIT 1";
	my ( $cust_logon, $pass ) = $dbh->selectrow_array($statement);

	return ( $cust_logon, $pass );
}

sub getStaffLogin {

	my $dbh = shift;

	my $statement = "SELECT logon,init_pw FROM staff,pwd 
					 WHERE fname<>'System' 
					 AND sname<>'Administrator' 
					 AND staff.staffid=pwd.staffid
					 AND pwd.seclev>1
					 ORDER BY RAND() LIMIT 1";

	my ( $staff_logon, $pass ) = $dbh->selectrow_array($statement);

	return ( $staff_logon, $pass );
}

sub getACustID {
	my $dbh = shift;

	my $statement = "SELECT custid FROM customer ORDER BY RAND() LIMIT 1";
	my ($custid) = $dbh->selectrow_array($statement);
	return $custid;
}

sub getASuppID {
	my $dbh = shift;

	my $statement = "SELECT suppid FROM supplier ORDER BY RAND() LIMIT 1";
	my ($suppid) = $dbh->selectrow_array($statement);
	return $suppid;
}

sub getAStaffID {
	my $dbh       = shift;
	my $statement = "SELECT staffid FROM staff 
	WHERE fname<>'System' 
	AND sname<>'Administrator' 
	ORDER BY RAND() LIMIT 1";
	my ($staffid) = $dbh->selectrow_array($statement);
	return $staffid;
}

sub getACustContactID {
	my $dbh    = shift;
	my $custid = shift;

	my $statement = "SELECT contactsid FROM contacts 
	WHERE fname<>'System' 
	AND sname<>'Administrator' 
	AND custid=$custid 
	ORDER BY RAND() LIMIT 1";
	my ($contactsid) = $dbh->selectrow_array($statement);
	return $contactsid;
}

sub generatelogon {
	my $fname = shift;
	my $sname = shift;
	my $logon = lc("$fname.$sname");
	my $cnt   = 1;

	return $logon;

}

sub mkPwd() {

	my $pwd;
	my @my_char_list =
	  ( ( 'A' .. 'Z' ), ( 'a' .. 'z' ), ( '@', '%', '^' ), ( 0 .. 9 ) );
	my $range_dis = $#my_char_list + 1;
	for ( 1 .. 17 ) {
		$pwd .= $my_char_list[ int( rand($range_dis) ) ];
	}
	return $pwd;
}

sub getHTMLColor {
	my @colors = map {
		join "",
		  map { sprintf "%02x", rand(255) }
		  ( 0 .. 2 )
	} ( 0 .. 63 );
	my $rand = int( rand($#colors) );

	return '#' . $colors[$rand];
}

our @male    = ();
our @female  = ();
our @surname = ();

sub loadNames {

	open( FHM, "<$athenaDir/adm/t/rsc/names.male" );
	while (<FHM>) {
		chomp;
		s/^(\w+).*$/$1/;
		tr/A-Z/a-z/;
		s/\b([a-z])/\u$1/g;
		push( @male, $_ );
	}

	open( FHF, "<$athenaDir/adm/t/rsc/names.female" );
	while (<FHF>) {
		chomp;
		s/^(\w+).*$/$1/;
		tr/A-Z/a-z/;
		s/\b([a-z])/\u$1/g;
		push( @female, $_ );
	}

	open( FHS, "<$athenaDir/adm/t/rsc/names.surname" );
	while (<FHS>) {
		chomp;
		s/^(\w+).*$/$1/;
		tr/A-Z/a-z/;
		s/\b([a-z])/\u$1/g;
		push( @surname, $_ );
	}

	#	return(\@male,\@female,\@surname);
}

sub getName {
	my $type = shift;

	my @coname = (
		"Corp",    "PLC",        "Products",    "Studios",
		"Company", "Enterprise", "Associates",  "Automation",
		"and Co.", "Inc.",       "Pty. Ltd.",   "Co. Ltd",
		"Foundry", "Logistics",  "Corporation", "Industries",
		"Ltd",     "and Sons",   "Agency",      "Institute",
		"GmbH"
	);

	my $ret = '';
	if ( $type eq 'rf' ) {    # Random First Name
		if ( time % 2 ) {
			$type = 'm';
		}
		else {
			$type = 'f';
		}
	}
	if ( $type eq 'rn' ) {    # Random Full Name
		if ( time % 2 ) {
			$type = 'ms';
		}
		else {
			$type = 'fs';
		}
	}

	if ( $type eq 'm' ) {
		$ret = $male[ ( int( rand( @male + 1 ) ) ) ];
	}
	elsif ( $type eq 'f' ) {
		$ret = $female[ ( int( rand( @female + 1 ) ) ) ];
	}
	elsif ( $type eq 's' ) {
		$ret = $surname[ ( int( rand( @surname + 1 ) ) ) ];
	}
	elsif ( $type eq 'ms' ) {
		$ret =
		    $male[ ( int( rand( @male + 1 ) ) ) ] . ' '
		  . $surname[ ( int( rand( @surname + 1 ) ) ) ];

	}
	elsif ( $type eq 'fs' ) {
		$ret =
		    $female[ ( int( rand( @female + 1 ) ) ) ] . ' '
		  . $surname[ ( int( rand( @surname + 1 ) ) ) ];

	}
	elsif ( $type eq 'c' ) {
		$ret =
		    $surname[ ( int( rand( @surname + 1 ) ) ) ] . ' '
		  . $coname[ ( int( rand(@coname) ) ) ];

	}
	else {

	}

	return $ret;
}
1;
