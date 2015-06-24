
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
			<a class="navbar-brand" href="/" title="Home"><?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1165
Released: Wed Jun 24 17:09:29 2015 GMT
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

*/ echo $owner->co_name;?></a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
              <?php
            
            $navs = array(
                'Quotes',
                'Invoices','Contacts','System'
            );
            $navLinks = array(
                '/quotes',
                '/invoices',
                'Contacts',
                'System'
            );
            $cnt = 0;
            
            foreach ($navs as $nav) {
                
                if ($nav == 'Contacts') {
                    ?>      
          <li
					class="dropdown<?php  if ($nav == $navtitle){echo ' active';}?>"><a
					href="/" class="dropdown-toggle" data-toggle="dropdown"
					role="button" aria-expanded="false" title="Contacts Menu">Contacts<span class="caret"></span>
				</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="/staff/">Staff</a></li>
						<li><a href="/customers/">Customers</a></li>
						<li><a href="/suppliers/">Suppliers</a></li>
						<li><a href="/contacts/">Contacts</a></li>
					</ul></li>
                                  
                                  <?php
                } elseif ($nav == 'System') {
                    ?>
          <li
					class="dropdown<?php  if ($nav == $navtitle){echo ' active';}?>"><a
					href="#" class="dropdown-toggle" data-toggle="dropdown"
					role="button" aria-expanded="false" title="System Menu">System<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="/owner.php">Company Details</a></li>
						<li><a href="/reports/">Reports</a></li>
						<li><a href="/pass.php?pg=logout">Log Out</a></li>
					</ul></li>
                         
                                  <?php
                } else 
                    
                    if ($nav == $navtitle) {
                        echo "<li class=active><a href=\"$navLinks[$cnt]\" title=\"$nav\">$nav</a></li>\n";
                    } else {
                        echo "<li><a href=\"$navLinks[$cnt]\" title=\"$nav\">$nav</a></li>\n";
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
