<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997 - 2004 The PHP Group                              |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author:  Klaus Guenther <klaus@capitalfocus.org>                     |
// +----------------------------------------------------------------------+
//
// $Id$


require_once 'HTML/CSS/Advanced.php';

$css = new HTML_CSS_Advanced();

// define styles
$css->setStyle('p', 'text-align', 'center');
$css->setStyle('p', 'color', '#ffffff');
$css->setStyle('p', 'text-align', 'left');
$css->setStyle('p', 'font', '16pt helvetica, arial, sans-serif');
$css->setStyle('p', 'font', '12pt helvetica, arial, sans-serif');

$css->createGroup('p,a', 'myGroup');
$css->setGroupStyle('myGroup', 'font', '12pt helvetica, arial, sans-serif');

// output the stylesheet directly to browser
$css->display();

?>
