<?php
/**
 * Simply ignores html_css errors that occurs.
 * 
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
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