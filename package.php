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
 * @copyright 2006-2009 Laurent Laville
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/HTML_CSS
 * @since     File available since Release 1.0.1
 * @ignore
 */

require_once 'PEAR/PackageFileManager2.php';

PEAR::setErrorHandling(PEAR_ERROR_DIE);

$packagefile = dirname(__FILE__) . '/package2.xml';

$options = array('filelistgenerator' => 'git',
    'packagefile' => 'package.xml',
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
$p2->setReleaseVersion('1.5.5');
$p2->setAPIVersion('1.5.0');
$p2->setReleaseStability('stable');
$p2->setAPIStability('stable');
$p2->setNotes('
PHPUnit 3.6 compatibility
');
$p2->addMaintainer('lead', 'farell',
                   'Laurent Laville', 'pear@laurent-laville.org', 'no');

//$p2->setLicense('BSD', 'http://www.opensource.org/licenses/bsd-license.php');
//$p2->setPhpDep('4.3.0');
//$p2->setPearinstallerDep('1.5.4');
//$p2->addPackageDepWithChannel('optional', 'Services_W3C_CSSValidator', 'pear.php.net', '0.1.0');
//$p2->addPackageDepWithChannel('optional', 'PHPUnit', 'pear.phpunit.de', '3.2.0');

if (isset($_GET['make'])
    || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    $p2->writePackageFile();
} else {
    $p2->debugPackageFile();
}