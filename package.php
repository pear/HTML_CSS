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
 * @copyright 2006-2007 Laurent Laville
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
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
$p2->setReleaseVersion('1.4.0');
$p2->setAPIVersion('1.4.0');
$p2->setReleaseStability('stable');
$p2->setAPIStability('stable');
$p2->setNotes('* changes
- removed old class (private) properties related to options ($_xhtmlCompliant,
  $_cache, $_singleLine, $_charset, $_contentDisposition, $_groupsFirst,
  $_allowDuplicates), now group by in a private array $options.
- added class constructor (ZE2) for PHP5.
- Error handler allow now to use PEAR_ERROR_CALLBACK to customize action
  (log yes/no, print yes/no) when an error/exception is raised.
- remove trailing EOL in toString() output (with oneline option set to true)

* news
- API 1.4.0 allow now a setter/getter PHP5 facility compatible
  (magic function __set, __get) for read/write CSS options.

* QA
- Coding Standard fixes (recommandation by PHP_CodeSniffer)
- tests suite migrated from PHPUnit 1.x to 3.x
- User Guide 1.4.0 included in this release cover all versions
1.x.x, 1.1.x, 1.2.x, 1.3.x, 1.4.x
');
$p2->setLicense('BSD', 'http://www.opensource.org/licenses/bsd-license.php');

if (isset($_GET['make'])
    || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    $p2->writePackageFile();
} else {
    $p2->debugPackageFile();
}
?>