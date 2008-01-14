<?php
/**
 * HTML_CSS Package Script Generator
 *
 * Generate a new fresh version of package xml 2.0
 * built with PEAR_PackageFileManager 1.6.0+
 *
 * PHP versions 4 and 5
 *
 * @category  HTML
 * @package   HTML_CSS
 * @author    Laurent Laville <pear@laurent-laville.org>
 * @copyright 2006-2008 Laurent Laville
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/HTML_CSS
 * @since     File available since Release 1.0.1
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
    'ignore' => array(__FILE__)
    );

$p2 = &PEAR_PackageFileManager2::importOptions($packagefile, $options);
$p2->setPackageType('php');
$p2->addRelease();
$p2->generateContents();
$p2->setReleaseVersion('1.5.0');
$p2->setAPIVersion('1.5.0');
$p2->setReleaseStability('stable');
$p2->setAPIStability('stable');
$p2->setNotes('* changes
- copyright year bump

* news
- implement request #12194 : support of At-Rules
- add new function getOptions() to retrieve easily all configuration options at once
- add new function validate() to check data source with W3C CSS validator service.
  Require PHP5. See file examples/CSS_validate.php

* QA
- User Guide (HTML version) included in previous versions was removed, since
its now part of PEAR manual
');
//$p2->setLicense('BSD', 'http://www.opensource.org/licenses/bsd-license.php');
//$p2->setPhpDep('4.3.0');
//$p2->setPearinstallerDep('1.5.4');
$p2->addPackageDepWithChannel('optional', 'Services_W3C_CSSValidator', 'pear.php.net', '0.1.0');

if (isset($_GET['make'])
    || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    $p2->writePackageFile();
} else {
    $p2->debugPackageFile();
}
?>