<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997 - 2003 The PHP Group                              |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author:  Klaus Guenther <klaus@capitalfocus.org>                     |
// +----------------------------------------------------------------------+
//
// $Id$



require_once 'HTML/CSS.php';

// generate a new class
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