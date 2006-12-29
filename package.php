<?php
/**
 * HTML_CSS Package Script Generator
 *
 * Generate a new fresh version of package xml 2.0 built with PEAR_PackageFileManager 1.6.0+
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   HTML
 * @package    HTML_CSS
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @copyright  2006-2007 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTML_CSS
 * @since      File available since Release 1.0.1
 * @ignore
 */

require_once 'PEAR/PackageFileManager2.php';

PEAR::setErrorHandling(PEAR_ERROR_DIE);

$packagefile = 'c:/php/pear/HTML_CSS/package2.xml';

$options = array('filelistgenerator' => 'cvs',
    'packagefile' => 'package2.xml',
    'baseinstalldir' => 'HTML',
    'simpleoutput' => true,
    'clearcontents' => false,
    'changelogoldtonew' => false,
    'ignore' => array('CSS_Advanced.php', 'Advanced.php', 'package.php')
    );

$p2 = &PEAR_PackageFileManager2::importOptions($packagefile, $options);
$p2->setPackageType('php');
$p2->addRelease();
$p2->generateContents();
$p2->setReleaseVersion('1.1.0');
$p2->setAPIVersion('1.1.0');
$p2->setReleaseStability('stable');
$p2->setAPIStability('stable');
$p2->setNotes('* news
- add new feature : ability to search if an element/property is defined or not
- upgraded copyright notice to new year 2007

* QA
- add new example CSS_grepStyles.php for function grepStyle()
- include a new revision of User Guide (TDG).
  see http://pear.laurent-laville.org/HTML_CSS for more format to download.
');
//$p2->setLicense('PHP License 3.01', 'http://www.php.net/license/3_01.txt');

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    $p2->writePackageFile();
} else {
    $p2->debugPackageFile();
}
?>