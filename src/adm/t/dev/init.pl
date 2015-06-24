#!/usr/bin/perl

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
use Term::ReadKey;
use WWW::Mechanize;
use DBI;
use Cwd 'realpath';
use lib do {
	my ($dir) = __FILE__ =~ m{^(.*)/};     # $dir = path of current file
	realpath("$dir/../../../lib/perl");    # path of '../lib' relative to $dir
};

use Athena;
use AthenaTest;
use DevTest;
use DevOwner;
use DevCust;
use DevSupp;
use DevStaff;
use Install;

my %config = &Athena::athenaConfig;
my $athDir = $config{'athenaDir'};
my $domain = $config{'domain'};

my $user = $>;
if ($user) { print "\nGotta be root! Not " . $user . "\n"; exit; }

$ENV{PERL_LWP_SSL_VERIFY_HOSTNAME} = 0;

my $mysqlrootpw = ( defined( $ARGV[0] ) ) ? $ARGV[0] : '';
my $full        = ( defined( $ARGV[1] ) ) ? $ARGV[1] : 'full';

#system("clear");

if ( $full eq 'full' ) {

	&Install::section( "Clear the existing data in the database?", 'Delete the current Database? y/N', 0 );
	my $dbDeleteConfirm = <STDIN>;
	chomp $dbDeleteConfirm;
	if ( $dbDeleteConfirm eq "" ) { $dbDeleteConfirm = 'n'; }

	if ( $dbDeleteConfirm eq 'y' ) {
		&Install::section( "Athena Database", 'Clearing and Remaking the Athena Database ...', 0 );

		# Clear Sys Database
		print "Clearing and Remaking the Athena Database ...\n";
		if ( !DevTest::db_init( $athDir, 'part', $mysqlrootpw ) ) {
			print "Couldn't clear and remake Sys DB\n";
		}
	}else {

	# Clear the pwd file
	open( FH, ">/etc/athenace/pwd" );
	print FH '';
	close(FH);
}
}


# db details
print "\nConnecting to Database ...\n";
my $dbhsys = &Athena::dbconnect();

AthenaTest::loadNames();

# Clearing the old PDF files
print "\nClearing the old PDF files ...\n";
system("rm -f $athDir/var/data/pdf/quotes/*");
system("rm -f $athDir/var/data/pdf/invoices/*");

&Install::doPermissions($athDir);

my @statlines = ();

# Set $quick to 0 for a full test, and 1 for a fast check with minimum data
my $quick = 0;

my $noOfStaff = ($quick) ? 8 : 24;

my $noOfCustomers    = ($quick) ? 1 : 16;
my $noOfCustContacts = ($quick) ? 1 : $noOfCustomers * 6;

my $noOfSuppliers    = ($quick) ? 1 : 6;
my $noOfSuppContacts = ($quick) ? 1 : $noOfSuppliers * 6;

my $noOfQuotes   = ($quick) ? 1 : 80;
my $noOfInvoices = ($quick) ? 1 : 120;

push(
	@statlines,
	"no Of Staff: $noOfStaff
no Of Customers: $noOfSuppliers
no Of Customer Contacts: $noOfSuppContacts
no Of Suppliers: $noOfSuppliers
no Of Supplier Contacts: $noOfSuppContacts
no Of Quotes: $noOfQuotes
no Of Invoices: $noOfInvoices"
);

# Get Mechanize Handle
my $mech = WWW::Mechanize->new( 'ssl_opts' => { SSL_verify_mode => 'SSL_VERIFY_NONE' } );

my ( $webhost, $inthost, $custhost, $supphost, $staffhost ) = Athena::getHostName();
my ( $admin_logon, $pass ) = AthenaTest::getAdminLogin($dbhsys);
&AthenaTest::login( $mech, $admin_logon, $pass, $webhost );

# Update Owner Details
print "\n#### Updating Site Owner Details \n";
print ".";
my ($coname) = &AthenaTest::getName('c');
if ( !DevTest::updateOwner( $dbhsys, $mech, $coname ) ) {
	print "Couldn't update owner\n";
}

# Clear the Dev password list
#open( FH, ">/etc/athenace/pwd" );
#print FH '';
#close(FH);

# Add Staff
print "\n#### Adding new Staff \n";
for ( 1 .. $noOfStaff ) {
	print ".";
	my ( $fname, $sname ) = split( / /, &AthenaTest::getName('rn') );
	if ( !DevOwner::add_staff( $dbhsys, $mech, $fname, $sname ) ) {
		print "Couldn't add staff\n";
	}
}

# Adding Admin Staff permissions
print "\n#### Adding Admin Staff permissions \n";
for ( 1 .. $noOfStaff ) {
	print ".";
	my ( $fname, $sname ) = split( / /, &AthenaTest::getName('rn') );
	if ( !DevOwner::staffAccess( $dbhsys, $mech ) ) {
		print "Couldn't add Admin Staff permissions\n";
	}
}

# Add Customers
print "\n#### Adding new Customers \n";
for ( 1 .. $noOfCustomers ) {
	print ".";
	my ($coname) = &AthenaTest::getName('c');
	if ( !DevOwner::add_cust( $dbhsys, $mech, $coname ) ) {
		print "Couldn't add Customers\n";
	}
}

# Edit Customers
print "\n#### Editing Customers \n";
for ( 1 .. $noOfCustomers ) {
	print ".";
	if ( !DevOwner::editCustomer( $dbhsys, $mech ) ) {
		print "Couldn't edit Customers\n";
	}
}

# Add Customers Contacts
print "\n#### Adding a new Customers's Contacts \n";
for ( 1 .. $noOfCustContacts ) {
	print ".";
	my ( $fname, $sname ) = split( / /, &AthenaTest::getName('rn') );
	if ( !DevOwner::add_cust_contact( $dbhsys, $mech, $fname, $sname ) ) {
		print "Couldn't add staff\n";
	}
}

# Add Suppliers
print "\n#### Adding new Suppliers \n";
for ( 1 .. $noOfSuppliers ) {
	print ".";
	my ($coname) = &AthenaTest::getName('c');
	if ( !DevOwner::add_supp( $dbhsys, $mech, $coname ) ) {
		print "Couldnt add supplier\n";
	}
}

# Editing Suppliers

print "\n#### Editing Supplier \n";
for ( 1 .. $noOfSuppliers ) {
	print ".";
	if ( !DevOwner::editSupplier( $dbhsys, $mech ) ) {
		print "Couldn't edit Supplier\n";
	}
}

# Add Suppliers Contacts
print "\n#### Adding a new Supplier's Contacts \n";
for ( 1 .. $noOfSuppContacts ) {
	print ".";
	my ( $fname, $sname ) = split( / /, &AthenaTest::getName('rn') );

	if ( !DevOwner::add_supp_contact( $dbhsys, $mech, $fname, $sname ) ) {
		print "Couldn't add supplier\n";
	}
}

# Owner Adds a Quote
print "\n#### Owner Adding new Quotes \n";
for ( 1 .. $noOfQuotes ) {
	print ".";
	if ( !DevOwner::add_quote( $dbhsys, $mech ) ) {
		print "Couldn't add quote\n";
	}
}

# Create Invoice
print "\n#### Creating new Invoice\n";
for ( 1 .. $noOfInvoices ) {
	print ".";
	if ( !DevOwner::add_invoice( $dbhsys, $mech ) ) {
		print "Couldn't add invoice\n";
	}
}

print "\n\n#### Site Stats ...;\n";

# Print Out Stats
foreach (@statlines) {
	print $_ . "\n";
}

print "\n\n";

# Clear the access log
my $log = $athDir . "/var/logs/access_log";
print "Clearing log $log\n";
open( FH, ">$log" );
print FH '';
close(FH);

if ( !DevTest::showLogins( $dbhsys, $domain ) ) {
	print "Couldn't show logins\n";
}

exit;

