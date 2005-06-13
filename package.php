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

function handleError($e) {

    if (PEAR::isError($e)) {
        die($e->getMessage());
    }
}

// Full description of the package
$description = <<<DESCR
HTML_CSS provides a simple interface for generating
a stylesheet declaration. It is completely standards compliant, and
has some great features:
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
$version = '1.0.0RC1';
$state   = 'beta';

// Notes about this new release
$notes = <<<NOTE
New features:
- PEAR_ErrorStack was replaced by a simple way to plug in any error handling system
you might want (default used PEAR_Error object)

Bug fixes
- Allows to fix a HTML_Progress problem (bug #2784)
- Inappropriate style rule reordering (bug #3920)

Changes
- Removes PEAR_ErrorStack and Log packages dependencies
- All unitTests are now fully PEAR_Error compatible
- apiVersion() returns now a string rather than a float; compatible with php.version_compare()
- createGroup() always returns a value now
- parseSelectors() status goes from public to protected
- collapseInternalSpaces() status goes from public to protected
- setSameStyle() is now optimized and single old reference is removed from CSS declarations

Quality Assurance
- Updates headers comment block on all files
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
    'dir_roles'         => array('examples' => 'doc',
                                 'tests' => 'test',
                                ),
    'state'             => $state,
    'filelistgenerator' => 'cvs',
    'changelogoldtonew' => false,
    'simpleoutput'      => true,
    'notes'             => $notes,
    'ignore'            => array('package.xml', 'package.php', 'Thumbs.db',
                                 'Advanced.php', 'CSS_Advanced.php',
                                 ),
    'cleardependencies' => true
);

$pkg = new PEAR_PackageFileManager();

$e = $pkg->setOptions( $options );
handleError($e);

// Replaces version number only in necessary files
$phpfiles = array(
    'CSS.php',
    'CSS/Error.php'
);
foreach ($phpfiles as $file) {
    $e = $pkg->addReplacement($file, 'package-info', '@package_version@', 'version');
    handleError($e);
}

// Maintainers List
$e = $pkg->addMaintainer( 'thesaur', 'lead', 'Klaus Guenther', 'klaus@capitalfocus.org' );
handleError($e);
$e = $pkg->addMaintainer( 'farell', 'lead', 'Laurent Laville', 'pear@laurent-laville.org' );
handleError($e);

// Dependencies List
$e = $pkg->addDependency('PEAR', false, 'has');
handleError($e);
$e = $pkg->addDependency('HTML_Common', '1.2', 'ge', 'pkg', false);
handleError($e);

// Writes the new version of package.xml
if (isset($_GET['make'])) {
    $e = $pkg->writePackageFile();
} else {
    $e = $pkg->debugPackageFile();
}
handleError($e);

// Build the new binary package
if (!isset($_GET['make'])) {
    echo '<a href="' . $_SERVER['PHP_SELF'] . '?make=1">Make this XML file</a>';
} else {
    $options = $pkg->getOptions();
    $pkgfile = $options['packagedirectory'] . DIRECTORY_SEPARATOR . $options['packagefile'];

    $pkgbin = new PEAR_Packager();

    $e = $pkgbin->package($pkgfile);
    handleError($e);
}
?>