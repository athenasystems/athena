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

#
# An example test script. Useful for checking things for debugging purposes
#
use strict;

use Cwd 'realpath';
use lib do {
	my ($dir) = __FILE__ =~ m{^(.*)/};     # $dir = path of current file
	realpath("$dir/../../../lib/perl");    # path of '../lib' relative to $dir
};

use Install;
use Athena;

use DBI;

my %config    = &Athena::athenaConfig;
my $athDir    = $config{'athenaDir'};
my $athDb     = $config{'db'};
my $athDbPwd  = $config{'dbpw'};
my $athDbUser = $config{'dbuser'};

## user hostname : This should be "localhost" but it can be diffrent too
my $host = "localhost";
## SQL query
my $query = "show tables";
my $dbh = DBI->connect( "DBI:mysql:$athDb:$host", $athDbUser, $athDbPwd );
my $sqlQuery = $dbh->prepare($query)
  or die "Can't prepare $query: $dbh->errstr\n";

my $rv = $sqlQuery->execute
  or die "can't execute the query: $sqlQuery->errstr";

while ( my @row = $sqlQuery->fetchrow_array() ) {
	my $tables = $row[0];
	print "$tables\n";
}

my $rc = $sqlQuery->finish;

exit(0);
