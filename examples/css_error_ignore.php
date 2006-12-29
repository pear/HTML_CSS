<?php
/**
 * Simply ignores html_css errors that occurs.
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
 * @author     Klaus Guenther <klaus@capitalfocus.org>
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @copyright  2005-2007 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTML_CSS
 * @since      File available since Release 1.0.0RC1
 */

require_once 'HTML/CSS.php';

function myErrorHandler()
{
    return null;
}

// Example A. ---------------------------------------------

$attribs = array();
$prefs   = array('error_handler' => 'myErrorHandler');

$css1 = new HTML_CSS($attribs, $prefs);

// A1. Error
$group1 = $css1->createGroup('body, html', 'grp1');
$group2 = $css1->createGroup('p, html', 'grp1');

// A2. Error
$css1->getStyle('h1', 'class');

// A3. Exception
$css1->setXhtmlCompliance('true');

print 'still alive !';

?>