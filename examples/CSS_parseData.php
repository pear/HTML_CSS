<?php
/**
 * Tell whether a value return by HTML_CSS is an error.
 * Solution to use HTML_CSS::isError() method.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   HTML
 * @package    HTML_CSS
 * @subpackage Examples
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @copyright  2005-2007 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTML_CSS
 * @since      File available since Release 1.0.0RC2
 */

require_once 'HTML/CSS.php';

function myErrorHandler($code, $level)
{
    return PEAR_ERROR_PRINT;  // always print all error messages
}

/*
body { font-size: 1em; }

---- print.css ----
*{
margin: 4px; padding: 0px;
}

body{
font-family: Tahoma, Verdana, Helvetica, Arial, sans-serif;
text-align:center;
background-color:#fff;
}

---- default.css ----
*{
margin: 0px; padding: 0px;
}

body{
font-family: Lucida Grande, Tahoma, Verdana, Arial, sans-serif;
text-align:center;
background-color:#fff;
}
*/
$styles = array(
    "body { font-size: 1em; }",
    "print.css",
    "default.css"
);

$prefs = array(
    'push_callback' => 'myErrorHandler',
);
$attribs = null;

$css = new HTML_CSS($attribs, $prefs);

$res = $css->parseData($styles);
if ($css->isError($res)) {
    $line = __LINE__ - 1;
    $style = 'background-color:red; color:yellow; font-weight:bold; padding:0.4em;';
    echo '<p style="'.$style.'">Error message detected by isError() at line ' . $line . '</p>';
}
print 'Still alive !';
?>