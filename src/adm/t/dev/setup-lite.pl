#!/usr/bin/perl

# Web Modules Ltd. Athena Community Edition Software 2015
# https://github.com/athenasystems/athenace The Athena Systems GitHub project
# Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
# Version: 1.1172
# Released: Wed Jun 24 17:40:52 2015 GMT
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
use DBI;
use Cwd 'realpath';
use lib do {
	my ($dir) = __FILE__ =~ m{^(.*)/};     # $dir = path of current file
	realpath("$dir/../../../lib/perl");    # path of '../lib' relative to $dir
};
use Install;
use Athena;

my $full     = ( defined( $ARGV[0] ) ) ? $ARGV[0] : '';
my $dbrootpw = ( defined( $ARGV[1] ) ) ? $ARGV[1] : '';
my %config   = &Athena::athenaConfig;
my $athDir   = $config{'athenaDir'};
my $domain   = $config{'domain'};
my $webmasterEmail = $config{'webmasterEmail'};
my $athenaDBpwd    = $config{'dbpw'};
my $athenarootpwd  = &Install::mkPwd();

# Get MySQL Root password

if ( $dbrootpw eq '' ) {
	&Install::section( "MySQL Password", 'Enter the MySQL Root Password: ', 0 );
	ReadMode('noecho');
	$dbrootpw = <STDIN>;
	chomp $dbrootpw;
	ReadMode('normal');
}

# Hash the password
my $secFile = '/tmp/Athena.sec.php';
&Install::make_tmp_sec_file($secFile);
my $pwhash = '';
if ( -e $secFile ) {

	# Hash the password
	$pwhash = `php $secFile pw='$athenarootpwd'`;
	chomp($pwhash);
}
else {
	print "Cant get sec file";
	exit;
}

chomp($pwhash);
if ( $full eq 'full' ) {
	&Install::section(
		"set up the Athena Configuration",
		"Setting up Athena Configuration"
	);
	&Install::makeAthenaConf( $athenaDBpwd, $domain, $webmasterEmail, $athDir );

	# Set up Apache2
	&Install::section( "set up the Apache Web Server",
		"Setting up Apache2 Configuration" );
	&Install::setupApache( $athDir, $domain );
}

&Install::section( "set up the MySQL Database",
	"Creating the SQL script to make the DB on this computer" );
&Install::setupDatabase( $athDir, $athenarootpwd, $pwhash, $athenaDBpwd,
	$dbrootpw );


open( FH, ">/etc/athenace/root" );
print FH $athenarootpwd;
close(FH);

open( FH, ">/etc/athenace/pwd" );
print FH "Sys Admin\t$athenarootpwd\n";
close(FH);

print
"\n\nYou can now log in at https://www.$domain \n\nAs user: root\n   pass: $athenarootpwd \n\n";

exit;

