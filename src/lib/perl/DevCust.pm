package DevCust;

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

sub custAgreeToQuote {

	my $dbh     = shift;
	my $mech    = shift;
	my $sitesid = shift;

	my ( $webhost, $inthost, $custhost, $supphost, $admhost, $staffhost ) = Athena::getHostName();

	my $custid = AthenaTest::getACustID($dbh);

	my ( $admin_logon, $pass ) = AthenaTest::getCustLogin( $dbh, $sitesid, $custid );
	&AthenaTest::login( $mech, $admin_logon, $pass, $webhost, $sitesid );

	my $uri = $custhost . '/quotes/';

	#print $uri . "\n";
	$mech->get($uri);

	# get all links whose text is "View Offer"
	my @orders = $mech->find_all_links(
		tag        => "a",
		text_regex => qr/^View Offer/,
	);

	#print $#orders . " - $custid\n";

	#exit;

	my $lnk2follow = int( rand($#orders) );

	# go to quote
	$mech->follow_link( text => 'View Offer', n => $lnk2follow );

	my @frms = $mech->forms;
	if ( defined( $frms[0] ) ) {

		#	print $#frms . " Agree to this Items\n";

		my $form2click = int( rand($#frms) );

		# agree to quote
		$mech->submit_form( form_number => $form2click );
	}
	else {
		print "No Forms here\n";
	}
	if ( $mech->success() ) {
		return 1;
	}
	else {
		return 0;
	}
}



1;
