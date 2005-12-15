<?php
/**
 * Make package.xml and GNU TAR archive files for HTML_CSS class
 *
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 */

require_once 'PEAR/Packager.php';
require_once 'PEAR/PackageFileManager.php';

PEAR::setErrorHandling(PEAR_ERROR_DIE);

// Full description of the package
$description = <<<DESCR
HTML_CSS provides a simple interface for generating a stylesheet declaration.
It is completely standards compliant, and has some great features:
* Simple OO interface to CSS definitions
* Can parse existing CSS (string or file)
* Output to
    - Inline stylesheet declarations
    - Document internal stylesheet declarations
    - Standalone stylesheet declarations
    - Array of definitions
    - File

In addition, it shares the following with HTML_Common based classes:
* Indent style support
* Line ending style
DESCR;

// Summary of description of the package
$summary = 'HTML_CSS is a class for generating CSS declarations.';

// New version and state of the package
$version = '1.0.0RC2';
$state   = 'beta';

// Notes about this new release
$notes = <<<NOTE
New features:
- parseData() : Ability to parse multiple data sources (filename, string) at once
- isError() : Tell whether a value return by HTML_CSS is an error.

See new script:  examples/CSS_parseData.php
NOTE;

// Configuration of PEAR::PackageFileManager
$options = array(
    'package'           => 'HTML_CSS',
    'summary'           => $summary,
    'description'       => $description,
    'license'           => 'PHP License 3.0',
    'baseinstalldir'    => 'HTML',
    'version'           => $version,
    'packagedirectory'  => '.',
    'state'             => $state,
    'filelistgenerator' => 'cvs',
    'changelogoldtonew' => false,
    'simpleoutput'      => false,
    'notes'             => $notes,
    'ignore'            => array('package.xml', 'package.php', 'Thumbs.db',
                                 'Advanced.php', 'CSS_Advanced.php',
                                 ),
    'cleardependencies' => true
);

$pkg = new PEAR_PackageFileManager();
$pkg->setOptions( $options );

// Replaces version number only in necessary files
$phpfiles = array(
    'CSS.php',
    'CSS/Error.php'
);
foreach ($phpfiles as $file) {
    $pkg->addReplacement($file, 'package-info', '@package_version@', 'version');
}

// Maintainers List
$pkg->addMaintainer( 'thesaur', 'lead', 'Klaus Guenther', 'klaus@capitalfocus.org' );
$pkg->addMaintainer( 'farell',  'lead', 'Laurent Laville', 'pear@laurent-laville.org' );

// Dependencies List
$pkg->addDependency('PEAR', false, 'has');
$pkg->addDependency('HTML_Common', '1.2', 'ge', 'pkg', false);

// Writes the new version of package.xml
if (isset($_GET['make'])) {
    @$pkg->writePackageFile();
} else {
    @$pkg->debugPackageFile();
}

// Build the new binary package
if (!isset($_GET['make'])) {
    echo '<a href="' . $_SERVER['PHP_SELF'] . '?make">Make this XML file</a>';
} else {
    $options = $pkg->getOptions();
    $pkgfile = $options['packagedirectory'] . DIRECTORY_SEPARATOR . $options['packagefile'];

    $pkgbin = new PEAR_Packager();
    $pkgbin->package($pkgfile);
}
?>