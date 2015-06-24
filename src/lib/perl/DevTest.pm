package DevTest;

# Web Modules Ltd. Athena Community Edition Software 2015
# https://github.com/athenasystems/athenace The Athena Systems GitHub project
# Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
# Version: 1.1173
# Released: Wed Jun 24 19:00:41 2015 GMT
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

sub db_init {
	my $dir      = shift || die "No dir passed";
	my $style    = shift;
	my $dbrootpw = shift;

	if ( !defined($style) ) { $style = 'part' }

	system("$dir/adm/t/dev/setup-lite.pl $style $dbrootpw");

	#my $ret = `$dir/root/setup-lite.pl > /dev/null 2>/dev/null`;

	return 1;
}

sub updateOwner {

	my $dbh    = shift;
	my $mech   = shift;
	my $coname = shift;

	print "\n#### Updating Site Owner;\n";

	my ( $webhost, $inthost, $custhost, $supphost, $admhost ) =
	  Athena::getHostName();
	my ( $strName, $city, $county, $country, $postcode ) =
	  AthenaTest::getAddress;

	# form details
	my $add1  = $strName;
	my $add2  = '';
	my $add3  = '';
	my $tel   = AthenaTest::getPhoneNumber;
	my $fax   = AthenaTest::getPhoneNumber;
	my $mob   = AthenaTest::getPhoneNumber;
	my $email = 'test@athena.systems';

	my %fields = (
		co_name  => $coname,
		add1     => $add1,
		add2     => $add2,
		add3     => $add3,
		city     => $city,
		county   => $county,
		country  => $country,
		postcode => $postcode,
		fax      => $fax,
		tel      => $tel,
		mob      => $mob,
		email    => $email
	);
	my $uri = $inthost . '/owner';
	$mech->get($uri);

	#print $mech->content;	exit;

	$mech->submit_form( with_fields => \%fields );

}



sub showLogins {

	my $dbhsys = shift;
	my $domain = shift;

	print "\n----------------------------------------------\n
You can now log in at https://www.$domain

with the following accounts:-\n";

	my $pwds = '';
	open( FH, "</etc/athenace/pwd" );
	while (<FH>) {
		$pwds .= $_;
	}
	close(FH);
	print $pwds . "\n";

}
1;
