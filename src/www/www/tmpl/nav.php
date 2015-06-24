
<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed"
				data-toggle="collapse" data-target="#navbar" aria-expanded="false"
				aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span> <span
					class="icon-bar"></span> <span class="icon-bar"></span> <span
					class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Athena Systems - Athena CE</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
              <?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1163
Released: Wed Jun 24 17:07:27 2015 GMT
The MIT License (MIT)

Copyright (c) 2015 Web Modules Ltd. UK

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*/
            $navs = array(
                'Home',
                'Documentation',
                'Help',
                'Login'
            );
            $navLinks = array(
                '/',
                '/docs',
                '/help',
                '/login'
            );
            $cnt = 0;
            foreach ($navs as $nav) {
               
                if ($nav == 'Help') {
                    ?>
                    <li class="dropdown<?php  if ($nav == $navtitle){echo ' active';}?>"><a href="#"
					class="dropdown-toggle" data-toggle="dropdown" role="button"
					aria-expanded="false">Help <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="/help/users">Users</a></li>
						<li><a href="/help/coders">Coders</a></li>
						<li><a href="/help/managers">Managers</a></li>
					</ul></li>

                    <?php
                } elseif ($nav == 'Documentation') {
                    ?>
                                    

				<li class="dropdown<?php  if ($nav == $navtitle){echo ' active';}?>"><a href="#" class="dropdown-toggle"
					data-toggle="dropdown" role="button" aria-expanded="false">Documentation
						<span class="caret"></span>
				</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="/help/install">Installation</a></li>
						<li><a href="/help/modules">Modules</a></li>
					</ul></li>
                                    <?php
                } 

                elseif ($nav == $navtitle) {
                    echo "<li class=active><a href=\"$navLinks[$cnt]\">$nav</a></li>\n";
                } else {
                    echo "<li><a href=\"$navLinks[$cnt]\">$nav</a></li>\n";
                }
                $cnt ++;
            }
            ?>
          
          





			</ul>

		</div>
		<!--/.nav-collapse -->
	</div>
	<!--/.container-fluid -->
</nav>
