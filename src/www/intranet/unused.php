<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1172
Released: Wed Jun 24 17:40:52 2015 GMT
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
    $functions = array();
    $path = "/src/athenace/lib";
    define_dir($path, $functions);
    reference_dir($path, $functions);
    echo
    	"<table>" .
    		"<tr>" .
    			"<th>Name</th>" .
    			"<th>Defined</th>" .
    			"<th>Referenced</th>" .
    		"</tr>";
    foreach ($functions as $name => $value) {
    	echo
    		"<tr>" . 
    			"<td>" . htmlentities($name) . "</td>" .
    			"<td>" . (isset($value[0]) ? count($value[0]) : "-") . "</td>" .
    			"<td>" . (isset($value[1]) ? count($value[1]) : "-") . "</td>" .
    		"</tr>";
    }
    echo "</table>";
    function define_dir($path, &$functions) {
    	if ($dir = opendir($path)) {
    		while (($file = readdir($dir)) !== false) {
    			if (substr($file, 0, 1) == ".") continue;
    			if (is_dir($path . "/" . $file)) {
    				define_dir($path . "/" . $file, $functions);
    			} else {
    				if (substr($file, - 4, 4) != ".php") continue;
    				define_file($path . "/" . $file, $functions);
    			}
    		}
    	}		
    }
    function define_file($path, &$functions) {
    	$tokens = token_get_all(file_get_contents($path));
    	for ($i = 0; $i < count($tokens); $i++) {
    		$token = $tokens[$i];
    		if (is_array($token)) {
    			if ($token[0] != T_FUNCTION) continue;
    			$i++;
    			$token = $tokens[$i];
    			if ($token[0] != T_WHITESPACE) die("T_WHITESPACE");
    			$i++;
    			$token = $tokens[$i];
    			if ($token[0] != T_STRING) die("T_STRING");
    			$functions[$token[1]][0][] = array($path, $token[2]);
    		}
    	}
    }
    function reference_dir($path, &$functions) {
    	if ($dir = opendir($path)) {
    		while (($file = readdir($dir)) !== false) {
    			if (substr($file, 0, 1) == ".") continue;
    			if (is_dir($path . "/" . $file)) {
    				reference_dir($path . "/" . $file, $functions);
    			} else {
    				if (substr($file, - 4, 4) != ".php") continue;
    				reference_file($path . "/" . $file, $functions);
    			}
    		}
    	}		
    }
    function reference_file($path, &$functions) {
    	$tokens = token_get_all(file_get_contents($path));
    	for ($i = 0; $i < count($tokens); $i++) {
    		$token = $tokens[$i];
    		if (is_array($token)) {
    			if ($token[0] != T_STRING) continue;
    			if ($tokens[$i + 1] != "(") continue;
    			$functions[$token[1]][1][] = array($path, $token[2]);
    		}
    	}
    }
?>