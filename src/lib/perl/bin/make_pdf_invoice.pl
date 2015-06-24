#!/usr/bin/perl

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
use POSIX qw(strftime);
use DBI;
use CGI;
use Locale::Currency::Format;    # liblocale-currency-format-perl

use FindBin qw($Bin);
use lib "$Bin/..";

use Athena;

my %config = &Athena::athenaConfig;
my $athDir = $config{'athenaDir'};

my $form = new CGI;
my %fds = map { $_ => scalar $form->param($_) } $form->param;

my $id = $fds{'id'};
my $format = ( defined( $fds{'format'} ) ) ? $fds{'format'} : 'web';

my $dataDir = Athena::getDataDir();

my $dbh = &Athena::dbconnect();

my %owner = Athena::getOwnerDetails($dbh);

my $dom = $ENV{'SERVER_NAME'};

use PDF::Create;    # libpdf-create-perl

my $total      = 0;
my $totalprice = 0;

my $sql = "SELECT customer.custid,customer.co_name,addsid,price,
invoices.invoicesid,invoices.incept as invincept,content
FROM customer,invoices
WHERE invoices.custid=customer.custid
AND invoices.invoicesid=?";

#print $sql . "\n\n";

my $sth = $dbh->prepare($sql);

my $ret = $sth->execute($id);

#f(( !defined($r{'invoicesid'}) )||($r{'invoicesid'} eq '')){
if ( $ret < 1 ) {
	print "No Invoice Found - exiting\n";
	exit;
}
my $row = $sth->fetchrow_hashref;
my %r   = %{$row};

my $docPrefix = $owner{'co_name'};
$docPrefix =~ s/\W/_/g;
$docPrefix =~ s/__/_/g;
my $docName    = '-';
my $docPDFName = '-';    # default for web delivery

if ( $format eq 'web' ) {
	$docName = $docPrefix . "_Invoice_" . $r{'invoicesid'} . '.pdf';

	# CGI Header designating the pdf data
	print CGI::header(
		-type       => 'application/x-pdf',
		-attachment => $docName
	);
}
else {
	$docPDFName =
	    $dataDir
	  . '/pdf/invoices/'
	  . $docPrefix
	  . "_Invoice_"
	  . $r{'invoicesid'} . '.pdf';
}

# initialize PDF
my $pdf = new PDF::Create(
	'filename'     => $docPDFName,
	'PageMode'     => 'UseOutlines',
	'Author'       => $owner{'co_name'},
	'Title'        => $owner{'co_name'} . " Invoice No: " . $r{'invoicesid'},
	'CreationDate' => [localtime],
);

my $sqlCust =
"SELECT * FROM customer,address WHERE customer.addsid=address.addsid AND custid=?";

$sth = $dbh->prepare($sqlCust);

$sth->execute( $r{'custid'} );
my $rowCust    = $sth->fetchrow_hashref;
my %co_details = %{$rowCust};

my ( $sec, $min, $hour, $mday, $mon, $year, $wday, $yday, $isdst ) =
  localtime( $r{'invincept'} );
$year = $year + 1900;
$mon++;
my $incept = "$mday/$mon/$year";

my $ypos      = 720;
my $fontSmall = 12;

# add a A4 sized page
my $a4 = $pdf->new_page( 'MediaBox' => $pdf->get_page_size('A4') );

# Add a page which inherits its attributes from $a4
my $curr_page = &newPage();

# Prepare a font
my $f1 = $pdf->font( 'BaseFont' => 'Times-Roman' );

# Prepare a Table of Content
my $toc =
  $pdf->new_outline( 'Title' => 'Title Page', 'Destination' => $curr_page );

pdf_header();

$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
	"Invoice No: " . $r{'invoicesid'} );
$ypos -= 20;
$curr_page->stringr( $f1, $fontSmall, 522, $ypos, "Invoice Date: " . $incept );
$ypos -= 20;
$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
	"Your Reference: " . $r{'custref'} );
$ypos -= 20;

$ypos += 60;

$curr_page->printnl( "Invoice To:- ", $f1, $fontSmall, 42, $ypos );
$ypos -= 20;
$curr_page->printnl( "$co_details{'co_name'}", $f1, $fontSmall, 42, $ypos );
$ypos -= 20;
$curr_page->printnl( "$co_details{'add1'}", $f1, $fontSmall, 42, $ypos );
$ypos -= 20;
if ( $co_details{'add2'} ne '' ) {
	$curr_page->printnl( "$co_details{'add2'}", $f1, $fontSmall, 42, $ypos );
	$ypos -= 20;
}
if ( $co_details{'add3'} ne '' ) {
	$curr_page->printnl( "$co_details{'add3'}", $f1, $fontSmall, 42, $ypos );
	$ypos -= 20;
}
if ( $co_details{'city'} ne '' ) {
	$curr_page->printnl( "$co_details{'city'}", $f1, $fontSmall, 42, $ypos );
	$ypos -= 20;
}
if ( $co_details{'county'} ne '' ) {
	$curr_page->printnl( "$co_details{'county'}", $f1, $fontSmall, 42, $ypos );
	$ypos -= 20;
}
$curr_page->printnl( "$co_details{'postcode'}", $f1, $fontSmall, 42, $ypos );
$ypos -= 20;

if ( $co_details{'inv_contact'} > 0 ) {
	$curr_page->printnl(
		"FAO: " . Athena::getCustExtName( $dbh, $co_details{'inv_contact'} ),
		$f1, $fontSmall, 42, $ypos );
	$ypos -= 20;
}

$curr_page->printnl( 'SALES INVOICE', $f1, 16, 222, $ypos );
$ypos -= 30;

$curr_page->printnl( "Job Description", $f1, $fontSmall, 42, $ypos );
$ypos -= 20;

$r{'content'} =~ s/\n/ /gs;

my @jobContents = split( /\b/, $r{'content'} );
my $tmpStr = '';
foreach (@jobContents) {
	$tmpStr .= $_;

	if ( length($tmpStr) > 80 ) {

		$curr_page->printnl( $tmpStr, $f1, $fontSmall, 42, $ypos );
		$ypos -= 20;
		$tmpStr = '';
	}
}
$curr_page->printnl( $tmpStr, $f1, $fontSmall, 42, $ypos );
$ypos -= 20;

$curr_page->printnl( "Job Items", $f1, $fontSmall, 42, $ypos );
$ypos -= 20;

my @itemContents = split( /\b/, $r{'content'} );
$tmpStr = '';
foreach (@itemContents) {
	$tmpStr .= $_;

	if ( length($tmpStr) > 50 ) {

		$curr_page->printnl( $tmpStr, $f1, $fontSmall, 42, $ypos );
		$ypos -= 20;
		$tmpStr = '';
	}
}
$curr_page->printnl( $tmpStr, $f1, $fontSmall, 42, $ypos );
$ypos -= 20;

$curr_page->printnl(
	'Unit Price: ' . currency_format( 'GBP', $r{'price'}, FMT_SYMBOL ),
	$f1, $fontSmall, 400, $ypos );
$ypos -= 20;

if ( $ypos < 200 ) {
	&footer();
	$curr_page = &newPage();
	$ypos      = 790;

	$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
		$owner{'co_nick'} . " Invoice No: $r{'invoicesid'}" );

	$ypos -= 60;

}
$total = $r{'price'};

my $vat_rate     = Athena::getVAT_Rate( $r{'invincept'} );
my $vat_rateText = Athena::getVatText($vat_rate);

my $vat = $total * $vat_rate;

#

$ypos -= 30;
if ( $ypos < 200 ) {
	&footer();
	$curr_page = &newPage();
	$ypos      = 790;
	$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
		$owner{'co_name'} . " Invoice No: $r{'invoicesid'}" );
	$ypos -= 60;
}

$totalprice = $total + $vat;

$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
	"Total (ex VAT) " . currency_format( 'GBP', $total, FMT_SYMBOL ) );
$ypos -= 30;

$curr_page->stringr( $f1, 14, 522, $ypos,
	"VAT \@ $vat_rateText " . currency_format( 'GBP', $vat, FMT_SYMBOL ) );
$ypos -= 30;
$curr_page->stringr( $f1, 14, 522, $ypos,
	"Amount Due " . currency_format( 'GBP', $totalprice, FMT_SYMBOL ) );
$ypos -= 30;

# Build Footer
&footer();

# Close the file and write the PDF
$pdf->close;

exit;

sub newPage() {

	# Add a page which inherits its attributes from $a4
	my $page = $a4->new_page;
	return $page;
}

sub footer() {

	# Build Footer
	$ypos = 70;

	$curr_page->stringc( $f1, 9, 300, $ypos,
		    $owner{'co_name'} . ','
		  . $owner{'add1'} . ','
		  . $owner{'add2'} . ','
		  . $owner{'city'} . ','
		  . $owner{'postcode'}
		  . ',' );
	$ypos -= 12;
	$curr_page->stringc( $f1, 9, 300, $ypos,
		'Tel: ' . $owner{'tel'} . ' Fax: ' . $owner{'fax'} );
	$ypos -= 12;
	$curr_page->stringc( $f1, 9, 300, $ypos,
		'Company No: ' . $owner{'co_no'} . '  VAT No: ' . $owner{'vat_no'} );
	$ypos -= 12;
	$curr_page->stringc( $f1, 9, 300, $ypos,
		'Email: ' . $owner{'email'} . '    Website: http://' . $owner{'web'} );
	$ypos -= 20;

}

sub pdf_header {

	# Get Header Image
	my $p1        = '';
	my $imgHeader = "$dataDir/site/pdf.header.jpg";
	if ( -e $imgHeader ) {
		$p1 = $pdf->image("$dataDir/site/pdf.header.jpg");
		$curr_page->image(
			'image'  => $p1,
			'xpos'   => 0,
			'ypos'   => $ypos,
			'xscale' => 0.8,
			'yscale' => 0.8
		);
		$ypos -= 45;

	}
	else {

		# Write out address
		$curr_page->stringr( $f1, 18, 522, $ypos, "$owner{'co_name'}" );
		$ypos -= 20;
		if ( $owner{'add1'} ne '' ) {
			$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
				"$owner{'add1'}" );
			$ypos -= 20;
		}
		if ( $owner{'add2'} ne '' ) {
			$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
				"$owner{'add2'}" );
			$ypos -= 20;
		}
		if ( $owner{'add3'} ne '' ) {
			$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
				"$owner{'add3'}" );
			$ypos -= 20;
		}
		if ( $owner{'city'} ne '' ) {
			$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
				"$owner{'city'}" );
			$ypos -= 20;
		}
		if ( $owner{'county'} ne '' ) {
			$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
				"$owner{'county'}" );
			$ypos -= 20;
		}
		if ( $owner{'country'} ne '' ) {
			$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
				"$owner{'country'}" );
			$ypos -= 20;
		}
		if ( $owner{'postcode'} ne '' ) {
			$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
				"$owner{'postcode'}" );
			$ypos -= 20;
		}

		if ( $owner{'co_no'} ne '' ) {
			$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
				"Company No: $owner{'co_no'}" );
			$ypos -= 20;
		}
		if ( $owner{'vat_no'} ne '' ) {
			$curr_page->stringr( $f1, $fontSmall, 522, $ypos,
				"VAT No: $owner{'vat_no'}" );
			$ypos -= 90;
		}
	}

}
