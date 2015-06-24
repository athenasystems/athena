#!/usr/bin/perl

# Web Modules Ltd. Athena Community Edition Software 2015
# https://github.com/athenasystems/athenace The Athena Systems GitHub project
# Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
# Version: 1.1163
# Released: Wed Jun 24 17:07:27 2015 GMT
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

#############################################
#### Defaults
my $default_add        = 'live-athena.com';
my $default_remote_dir = '/srv';
my $default_user       = 'root';
#############################################

use Expect;
use Term::ReadKey;
use lib ('../../lib/perl');
use Athena;
use Install;

my $mkLive = shift;
my $zip    = shift;

my %config = &Athena::athenaConfig;
my $athDir = $config{'athenaDir'};

system("clear");

print "Usage:\n";
print "./mklive.pl\t\tdo a test run (doesnt actually move any files)\n";
print "./mklive.pl go\t\tBack up the files and copy to the remote server\n";
print
  "./mklive.pl go nozip\tCopy the files to the remote server with no back up\n";

# Get the web domain
&Install::section( "Enter the IP address or Domain name of the live server ,",
	"or hit 'Enter' to accept the default (live-athena.com): ", 0 );
my $destAddress = <STDIN>;
chomp $destAddress;
if ( $destAddress eq "" ) { $destAddress = $default_add; }

# Get the remote folder
&Install::section(
	"Enter the directory on the live server where the files are stored,",
	"or hit 'Enter' to accept the default (/srv): ", 0 );
my $destDir = <STDIN>;
chomp $destDir;
if ( $destDir eq "" ) { $destDir = $default_remote_dir; }

# Get the username
&Install::section( "Enter the Username used to log in to the live server,",
	"or hit 'Enter' to accept the default (root): ", 0 );
my $usr = <STDIN>;
chomp $usr;
if ( $usr eq "" ) { $usr = $default_user; }

# Get MySQL Root password
&Install::section( "Password", 'Enter the password for your live server: ', 0 );
ReadMode('noecho');
my $liveServerPwd = <STDIN>;
ReadMode('normal');
print "\n";
chomp $liveServerPwd;

if ( ( !defined($zip) ) || ( $zip ne 'nozip' ) ) {
	&Install::section( "Backup", "Making a Back in $athDir/var/backup", 0 );
	&Athena::backUpLocal($athDir);
}
my @parms = ();
if ( ( defined($mkLive) ) && ( $mkLive eq 'go' ) ) {
	push( @parms, "-rltDvh" );
}
else {
	push( @parms, "-rltDvnh" );
}
push( @parms, "-e ssh" );
push( @parms, "--stats" );
push( @parms, "--include-from=include" );
push( @parms, "--exclude-from=exclude" );
push( @parms, "--delete" );
push( @parms, $athDir );
push( @parms, $usr . '@' . $destAddress . ":$destDir/" );
my $command = "rsync";
my $prms = join " ", @parms;

print "\nDoing:\n$command $prms\n";
my $exp = Expect->spawn( $command, @parms ) or die "Cannot spawn ssh: $!\n";

$exp->expect(
	undef,
	[
		qr/'s password:/ =>
		  sub { my $exp = shift; $exp->send("$liveServerPwd\n"); exp_continue; }
	],
	[
		qr/want to continue connecting/ =>
		  sub { my $exp = shift; $exp->send("yes\n"); exp_continue; }
	],
);

exit;
