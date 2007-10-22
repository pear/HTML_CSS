<?php
/**
 * InHeader example
 *
 * @category   HTML
 * @package    HTML_CSS
 * @subpackage Examples
 * @author     Klaus Guenther <klaus@capitalfocus.org>
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @copyright  2003-2007 Klaus Guenther, Laurent Laville
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTML_CSS
 */

require_once 'HTML/Page2.php';
require_once 'HTML/CSS.php';


// generate an instance
$css = new HTML_CSS();

// define the various settings for <body>
$css->setStyle('body', 'background-color', '#0c0c0c');
$css->setStyle('body', 'color', '#ffffff');
// define <h1> element
$css->setStyle('h1', 'text-align', 'center');
$css->setStyle('h1', 'font', '16pt helvetica, arial, sans-serif');
// define <p> element
$css->setStyle('p', 'font', '12pt helvetica, arial, sans-serif');

// create a new HTML page2 instance
$p = new HTML_Page2();
$p->setTitle("My page");
// it can be added as an object
$p->addStyleDeclaration($css, 'text/css');
$p->setMetaData('author', 'My Name');
$p->addBodyContent('<h1>headline</h1>');
$p->addBodyContent('<p>some text</p>');
$p->addBodyContent('<p>some more text</p>');
$p->addBodyContent('<p>yet even more text</p>');
// output the finished product to the browser
$p->display();
// or output the finished product to a file
//$p->toFile('example.html');
?>