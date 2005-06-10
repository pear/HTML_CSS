<?php
/**
 * Customize error renderer with default PEAR_Error object.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   HTML
 * @package    HTML_CSS
 * @subpackage Examples
 * @author     Klaus Guenther <klaus@capitalfocus.org>
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTML_CSS
 * @since      File available since Release 1.0.0RC1
 */

require_once 'HTML/CSS.php';
require_once 'PEAR.php';

function myErrorCallback($pb_error)
{
    $keys = array('error_message_prefix', 'mode', 'level', 'code', 'message');

    foreach ($keys as $i => $k) {
        printf("%s = %s <br/>\n", $k, $pb_error->$k);
    }
    echo '<hr/>';
}

function myErrorHandler($code, $level)
{
    if ($level == 'exception') {
        return PEAR_ERROR_PRINT;  // rather than PEAR_ERROR_DIE
    } else {
        return PEAR_ERROR_CALLBACK;
    }
}

/**
 * be sure that we will print and log error details.
 * @see HTML_CSS_Error::log()
 */
ini_set('display_errors',1);
ini_set('log_errors',1);


// Example A. ---------------------------------------------

PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, 'myErrorCallback');

$css1 = new HTML_CSS();

// A1. Error
$group1 = $css1->createGroup('body, html', 'grp1');
$group2 = $css1->createGroup('p, html', 'grp1');


// Example B. ---------------------------------------------

$displayConfig = array(
    'lineFormat' => '<b>%1$s</b>: %2$s<br/>%3$s<hr/>',
    'contextFormat' =>   '<b>File:</b> %1$s <br />'
                       . '<b>Line:</b> %2$s <br />'
                       . '<b>Function:</b> %3$s '
);

$prefs = array(
    'push_callback' => 'myErrorHandler',
    'handler' => array('display' => $displayConfig)
);

$css2 = new HTML_CSS(null, $prefs);

// B1. Error
$css2->getStyle('h1', 'class');

// B2. Exception
$css2->setXhtmlCompliance('true');

print 'still alive !';

?>