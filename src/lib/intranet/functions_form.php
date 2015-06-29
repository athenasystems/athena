<?php
/*
Web Modules Ltd. Athena Community Edition Software 2015
https://github.com/athenasystems/athenace The Athena Systems GitHub project
Author: Peter Lock - Disfit - for Web Modules Ltd.<coders@athena.systems>
Version: 1.1179
Released: Mon Jun 29 09:29:29 2015 GMT
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

function perpage_select($display, $name, $selected, $q = '', $qcustid = '', $qsuppid = '', $qcontactsid = '')
{
    $extra_search = '';
    if ($q != '') {
        $extra_search .= '&' . 'q=' . $q;
    }
    if ($qcustid != '') {
        $extra_search .= '&' . 'custid=' . $qcustid;
    }
    if ($qsuppid != '') {
        $extra_search .= '&' . 'suppid=' . $qsuppid;
    }

    $onChange = "location='" . $_SERVER['PHP_SELF'] . '?' . "from=0&perpage=' + document.getElementById('perpage').options[document.getElementById('perpage').selectedIndex].value + '" . $extra_search . "';";

    $html = "<div class=\"form-group\"><select id=$name name=$name onchange=\"$onChange\">";

    for ($counter = 1; $counter <= 300; $counter = $counter * 2) {

        if ($selected == $counter) {
            $sel = ' selected="selected"';
        } else {
            $sel = '';
        }

        $html .= <<<EOT
<option value="$counter" $sel>$counter per page</option>
EOT;
    }

    $html .= "</select></div>";

    print $html;
}



?>