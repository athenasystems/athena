#!/usr/bin/perl

# Web Modules Ltd. Athena Community Edition Software 2015
# https://github.com/athenasystems/athenace The Athena Systems GitHub project
# Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
# Version: 1.1161
# Released: Wed Jun 24 17:03:46 2015 GMT
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

my %config = &athenaConfig;
my $now = time();
my $pwd    = $config{'dbpw'};
my $athenaDir    = $config{'athenaDir'};
my $dbuser    = $config{'dbuser'};
my $db     = 'athenace';

my $tmp_file = '/tmp/Athena.db' . time() . '.sql';

&section("Athena Database Backup");
# MySQL Dump to temp file

my $tmpFile = '/tmp/Athena.MyAcc' . time();

	open( FHOUT, ">$tmpFile" );
	print FHOUT "[mysqldump]\nuser=$dbuser\npassword=" . $pwd;
	close(FHOUT);

my $cmd = "mysqldump --defaults-extra-file=$tmpFile --databases $db > $tmp_file";
system($cmd);

# Tar and zip the temp sql file
$cmd = "tar -czf $athenaDir/var/backup/Athena.DB.$now.tgz $tmp_file";
system($cmd);

# Remove temp sql file
system("rm $tmpFile");

exit;

sub section(){
	my $msg = shift;
	print "\n-------------------------------------------------\n$msg";
	print "\n-------------------------------------------------\n";
}

sub athenaConfig {
	my %config;
	open(FH,"</etc/athenace/athena.conf");
	while (<FH>) {
		chomp;
		my @cnfs = split( /=/, $_ );
		$config{ $cnfs[0] } = $cnfs[1];
	}
	close(FH);
	return %config;
}

