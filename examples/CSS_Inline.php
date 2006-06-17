<?php
/**
 * InLine example
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
 * @copyright  2003-2006 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTML_CSS
 */

require_once 'HTML/CSS.php';

// generate an instance
$css = new HTML_CSS();

// let's set some styles for <body>
$css->setStyle('body', 'background-color', '#0c0c0c');
$css->setStyle('body', 'color', '#ffffff');

// now for <h1>
$css->setStyle('h1', 'text-align', 'center');
$css->setStyle('h1', 'font', '16pt helvetica, arial, sans-serif');

// and finally for <p>
$css->setStyle('p', 'font', '12pt helvetica, arial, sans-serif');

// let's make <body> inherit from <p>
$css->setSameStyle('body', 'p');

// and let's put this into a tag:
echo '<body style="' . $css->toInline('body') . '">';
// will output:
// <body style="font:12pt helvetica, arial, sans-serif;background-color:#0c0c0c;color:#ffffff;">

// ideas for inline use:
//    * use in conjunction with HTML_Table to assign styles for cells
//    * integrates easily into existing classes

?>