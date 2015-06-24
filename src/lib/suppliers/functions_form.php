<?php
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

*/

function perpage_select($display,$name,$selected,$q='') {
    global $errors;

    if((!isset($id))||($id == "")){
        $id = $name;
    }
    $extra_search='';
    if ($q!=''){
        $extra_search .= 'q='.$q . '&' ;
    }

    $onChange = "location='" . $_SERVER['PHP_SELF'] . '?' . $extra_search .
    "from=0&perpage=' + document.getElementById('perpage').options[document.getElementById('perpage').selectedIndex].value;";


    $html = "<div class=\"form-group\"><select id=$name name=$name onchange=\"$onChange\">";

    //	$html .= '<option value="1">1 per page</option>' . "\n";
    //	$html .= '<option value="3">3 per page</option>' . "\n";
    //	$html .= '<option value="6">6 per page</option>' . "\n";

    for ( $counter = 1; $counter <= 300; $counter = $counter * 2) {

    if($selected == $counter){
    $sel = ' selected="selected"';
    }else{
    $sel = '';
}

$html .= <<<EOT
<option value="$counter" $sel>$counter per page</option>\n
EOT;

	}

	$html .= "</select></div>";


	print $html;

}



?>