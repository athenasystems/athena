package DevOwner;

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

our $sentQuote   = 0;
our $sentInvoice = 0;

sub add_staff {

	my $dbh   = shift;
	my $mech  = shift;
	my $fname = shift;
	my $sname = shift;

	my ( $webhost, $inthost, $custhost, $supphost, $admhost, $staffhost ) = Athena::getHostName();

	my $mob   = AthenaTest::getPhoneNumber;
	my $email = $fname . '.' . $sname . '@athena.systems';
	my $logon = Athena::generatePwd(10);

	my $uri = $inthost . '/staff/add.php';

	#print "$uri ...............\n";
	$mech->get($uri);

	#print $mech->content;

	my ( $strName, $city, $county, $country, $postcode ) = AthenaTest::getAddress;

	# form details
	my $add1 = $strName;
	my $add2 = '';
	my $add3 = '';
	my $tel  = AthenaTest::getPhoneNumber;
	my $fax  = AthenaTest::getPhoneNumber;

	# field details

	my %fields = (
		fname    => $fname,
		sname    => $sname,
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

	$mech->submit_form( with_fields => \%fields );

	if ( $mech->success() ) {
		return 1;
	}
	else {
		return 0;
	}
}

sub staffAccess {
	my $dbh  = shift;
	my $mech = shift;

	my $sql = "SELECT staffid FROM staff 
	
	ORDER BY staffid
	LIMIT 4;";

	my $cursor = $dbh->prepare($sql);
	my $rtn    = $cursor->execute();

	while ( my @row = $cursor->fetchrow_array ) {

		my %fields = ( seclev => 1 );

		my $uri = $inthost . '/staff/access?id=' . $row[0];
		$mech->get($uri);

		# add staff member to admin group
		$mech->submit_form( with_fields => \%fields );

	}

	if ( $mech->success() ) {
		return 1;
	}
	else {
		return 0;
	}
}

sub add_cust {

	my $dbh     = shift;
	my $mech    = shift;
	my $co_name = shift;

	my ( $webhost, $inthost, $custhost, $supphost, $admhost, $staffhost ) = Athena::getHostName();

	my $co_nameStr = $co_name;
	$co_nameStr =~ s/\W/\./g;
	my $email = $co_nameStr . '@athena.systems';

	# form details

	my ( $strName, $city, $county, $country, $postcode ) = AthenaTest::getAddress;

	# form details
	my $add1 = $strName;
	my $add2 = '';
	my $add3 = '';
	my $tel  = AthenaTest::getPhoneNumber;
	my $fax  = AthenaTest::getPhoneNumber;
	my $mob  = AthenaTest::getPhoneNumber;

	my %fields = (
		co_name => $co_name,

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

	my $uri = $inthost . '/customers/add.php';
	$mech->get($uri);

	# add the supp
	$mech->submit_form( with_fields => \%fields );

	if ( $mech->success() ) {
		return 1;
	}
	else {
		return 0;
	}
}

sub add_cust_contact {

	my $dbh   = shift;
	my $mech  = shift;
	my $fname = shift;
	my $sname = shift;

	my ( $strName, $city, $county, $country, $postcode ) = AthenaTest::getAddress;

	my $add1  = $strName;
	my $add2  = '';
	my $add3  = '';
	my $tel   = AthenaTest::getPhoneNumber;
	my $fax   = AthenaTest::getPhoneNumber;
	my $mob   = AthenaTest::getPhoneNumber;
	my $email = $fname . '.' . $sname . '@athena.systems';

	my ( $webhost, $inthost, $custhost, $supphost, $admhost, $staffhost ) = Athena::getHostName();

	# field details

	my %fields = (
		fname    => $fname,
		sname    => $sname,
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

	# Get Random custid
	$custid = AthenaTest::getACustID($dbh);

	$fields{'custid'} = $custid;

	my $uri = $inthost . '/contacts/add.php';
	$mech->get($uri);

	# add the contact
	$mech->submit_form( with_fields => \%fields );

	#my $HTML = $mech->content();
	#print $HTML;

	if ( $mech->success() ) {
		return 1;
	}
	else {
		return 0;
	}
}

sub add_supp {

	my $dbh     = shift;
	my $mech    = shift;
	my $co_name = shift;

	my ( $webhost, $inthost, $custhost, $supphost, $admhost, $staffhost ) = Athena::getHostName();

	my $co_nameStr = $co_name;
	$co_nameStr =~ s/\W/\./g;
	my $email = $co_nameStr . '@athena.systems';

	# form details

	my ( $strName, $city, $county, $country, $postcode ) = AthenaTest::getAddress;

	# form details
	my $add1 = $strName;
	my $tel  = AthenaTest::getPhoneNumber;
	my $fax  = AthenaTest::getPhoneNumber;

	my %fields = (
		co_name  => $co_name,
		add1     => $add1,
		city     => $city,
		county   => $county,
		country  => $country,
		postcode => $postcode,
		tel      => $tel,
		fax      => $fax,
		email    => $email
	);

	my $uri = $inthost . '/suppliers/add.php';
	$mech->get($uri);

	# add the supp
	$mech->submit_form( with_fields => \%fields );

	if ( $mech->success() ) {
		return 1;
	}
	else {
		return 0;
	}
}

sub add_supp_contact {

	my $dbh   = shift;
	my $mech  = shift;
	my $fname = shift;
	my $sname = shift;

	my ( $strName, $city, $county, $country, $postcode ) = AthenaTest::getAddress;

	my $add1  = $strName;
	my $add2  = '';
	my $add3  = '';
	my $tel   = AthenaTest::getPhoneNumber;
	my $fax   = AthenaTest::getPhoneNumber;
	my $mob   = AthenaTest::getPhoneNumber;
	my $email = $fname . '.' . $sname . '@athena.systems';

	my ( $webhost, $inthost, $custhost, $supphost, $admhost, $staffhost ) = Athena::getHostName();

	# field details

	my %fields = (
		fname    => $fname,
		sname    => $sname,
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

	# Get Random suppid
	my $suppid = AthenaTest::getASuppID($dbh);

	$fields{'suppid'} = $suppid;

	my $uri = $inthost . '/contacts/add.php';
	$mech->get($uri);

	# add the contact
	$mech->submit_form( with_fields => \%fields );

	#my $HTML = $mech->content();
	#print $HTML;

	if ( $mech->success() ) {
		return 1;
	}
	else {
		return 0;
	}
}

sub add_quote {

	my $dbh  = shift;
	my $mech = shift;

	my ( $webhost, $inthost, $custhost, $supphost, $admhost, $staffhost ) = Athena::getHostName();

	my $uri = $inthost . '/quotes/add';

	#print "$uri ...............\n";

	$mech->get($uri);

	my %fields = ();

	my $custid = AthenaTest::getACustID($dbh);
	$fields{"custid"}  = $custid;
	$fields{"content"} = AthenaTest::generate_random_sentence;
	$fields{"notes"}   = AthenaTest::generate_random_sentence;
	$fields{"price"}   = int( rand(1200) );
	$mech->submit_form( with_fields => \%fields );

	if ( $mech->success() ) {
		my $statement = "SELECT quotesid FROM quotes  ORDER BY quotesid DESC LIMIT 1";
		my ($quotesid) = $dbh->selectrow_array($statement);

		#
		# Make Quote Live
		#

		if ( !DevOwner::make_quote_live( $dbh, $mech, $quotesid ) ) {
			print "Couldn't Make Quote Live\n";
		}
		if ( $DevOwner::sentQuote == 0 ) {
			if ( !DevOwner::send_quote_email( $dbh, $mech, $quotesid ) ) {
				print "Couldn't Make Quote Live\n";
			}
		}
		return 1;
	}
	else {
		return 0;
	}
}

sub make_quote_live {

	my $dbh      = shift;
	my $mech     = shift;
	my $quotesid = shift;

	my ( $webhost, $inthost, $custhost, $supphost, $admhost, $staffhost ) = Athena::getHostName();

	my $uri = $inthost . '/quotes/status?id=' . $quotesid;

	#print "$uri ...............\n";

	$mech->get($uri);

	#my $HTML =  $mech->content();
	#	print $HTML;
	# field details

	my %fields = ();

	$fields{"live"} = 1;

	$mech->submit_form( with_fields => \%fields );

	# my @keys = sort { $a cmp $b } keys %fields;
	#    foreach my $key ( @keys ) {
	#   print "$key => $fields{$key} \n";
	#   }
	#my $HTML =  $mech->content();
	#print $HTML;

	if ( $mech->success() ) {
		return 1;
	}
	else {
		return 0;
	}
}

sub send_quote_email {

	my $dbh      = shift;
	my $mech     = shift;
	my $quotesid = shift;

	my ( $webhost, $inthost, $custhost, $supphost, $admhost, $staffhost ) = Athena::getHostName();

	my $uri = $inthost . '/mail/quote.php?id=' . $quotesid;

	#print "$uri ...............\n";

	$mech->get($uri);
	$DevOwner::sentQuote++;

	if ( $mech->success() ) {
		return 1;
	}
	else {
		return 0;
	}
}

sub add_invoice {
	my $dbh  = shift;
	my $mech = shift;

	my $custid = AthenaTest::getACustID($dbh);
	$fields{"custid"} = $custid;

	my $uri = $inthost . "/invoices/add?id=" . $custid;
	$mech->get($uri);

	$fields{"content"} = AthenaTest::generate_random_sentence;
	$fields{"notes"}   = AthenaTest::generate_random_sentence;
	$fields{"price"}   = int( rand(1200) );

	$mech->submit_form( with_fields => \%fields );

	if ( $mech->success() ) {
		my $statement = "SELECT invoicesid FROM invoices ORDER BY invoicesid DESC LIMIT 1";
		my ($invoicesid) = $dbh->selectrow_array($statement);
		if ( $DevOwner::sentInvoice == 0 ) {
			if ( !DevOwner::send_invoice_email( $dbh, $mech, $invoicesid ) ) {
				print "Couldn't Send Invoice Email\n";
			}
		}
		return 1;
	}
	else {
		return 0;
	}

	if ( $mech->success() ) {
		return 1;
	}
	else {
		return 0;
	}

}

sub send_invoice_email {
	my $dbh        = shift;
	my $mech       = shift;
	my $invoicesid = shift;

	my ( $webhost, $inthost, $custhost, $supphost, $admhost, $staffhost ) = Athena::getHostName();

	my $uri = $inthost . '/mail/invoice.php?id=' . $invoicesid;

	#print "$uri ...............\n";

	$mech->get($uri);
	$DevOwner::sentInvoice++;

	if ( $mech->success() ) {
		return 1;
	}
	else {
		return 0;
	}
}

sub editCustomer {

	my $dbh  = shift;
	my $mech = shift;

	my ( $webhost, $inthost, $custhost, $supphost, $admhost, $staffhost ) = Athena::getHostName();

	my $rand_hex = join "", map { unpack "H*", chr( rand(256) ) } 1 .. 6;
	$rand_hex = '#' . $rand_hex;

	my %fields = ( colour => &AthenaTest::getHTMLColor() );

	my $uri = $inthost . '/customers/edit.php?id=' . AthenaTest::getACustID($dbh);
	$mech->get($uri);

	# edit the cust
	$mech->submit_form( with_fields => \%fields );

	if ( $mech->success() ) {
		return 1;
	}
	else {
		return 0;
	}
}

sub editSupplier {

	my $dbh  = shift;
	my $mech = shift;

	my ( $webhost, $inthost, $custhost, $supphost, $admhost, $staffhost ) = Athena::getHostName();

	my %fields = ( colour => &AthenaTest::getHTMLColor() );

	my $uri = $inthost . '/suppliers/edit.php?id=' . AthenaTest::getASuppID($dbh);
	$mech->get($uri);

	# edit the supplier
	$mech->submit_form( with_fields => \%fields );

	if ( $mech->success() ) {
		return 1;
	}
	else {
		return 0;
	}
}

1;

