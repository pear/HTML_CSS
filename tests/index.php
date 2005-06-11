<?php
/**
 * HTML output for PHPUnit suite tests.
 *
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 */

require_once 'TestUnit.php';
require_once 'HTML_TestListener.php';
require_once 'HTML/CSS.php';

$title = 'PhpUnit test run, HTML_CSS class';
?>
<html>
<head>
<title><?php echo $title; ?></title>
<link rel="stylesheet" href="./stylesheet.css" type="text/css" />
</head>
<body>
<h1><?php echo $title; ?></h1>
      <p>
    This page runs all the phpUnit self-tests, and produces nice HTML output.
      </p>
      <p>
    Unlike typical test run, <strong>expect many test cases to
      fail</strong>.  Exactly those with <code>pass</code> in their name
    should succeed.
      </p>
      <p>
      For each test we display both the test result -- <span
      class="Pass">ok</span>, <span class="Failure">FAIL</span>, or
      <span class="Error">ERROR</span> -- and also a meta-result --
      <span class="Expected">as expected</span>, <span
      class="Unexpected">UNEXPECTED</span>, or <span
      class="Unknown">unknown</span> -- that indicates whether the
      expected test result occurred.  Although many test results will
      be 'FAIL' here, all meta-results should be 'as expected', except
      for a few 'unknown' meta-results (because of errors) when running
      in PHP3.
      </p>

<h2>Tests</h2>
    <?php
    $testcases = array(
            'HTML_CSS_TestCase_setSingleLineOutput',
            'HTML_CSS_TestCase_setXhtmlCompliance',
            'HTML_CSS_TestCase_createGroup',
            'HTML_CSS_TestCase_unsetGroup',
            'HTML_CSS_TestCase_setGroupStyle',
            'HTML_CSS_TestCase_getGroupStyle',
            'HTML_CSS_TestCase_setStyle',
            'HTML_CSS_TestCase_getStyle',
            'HTML_CSS_TestCase_setSameStyle',
            'HTML_CSS_TestCase_setCache',
            'HTML_CSS_TestCase_setCharset',
            'HTML_CSS_TestCase_parseString',
            'HTML_CSS_TestCase_parseFile',
            'HTML_CSS_TestCase_parseSelectors',
            'HTML_CSS_TestCase_addGroupSelector',
            'HTML_CSS_TestCase_removeGroupSelector',
            'HTML_CSS_TestCase_toInline',
            'HTML_CSS_TestCase_toFile',
            'HTML_CSS_TestCase_bug725',
            'HTML_CSS_TestCase_bug998',
            'HTML_CSS_TestCase_bug1066',
            'HTML_CSS_TestCase_bug1072',
            'HTML_CSS_TestCase_bug1084'
    );

    $suite = new PHPUnit_TestSuite();

    foreach ($testcases as $testcase) {
            include_once $testcase . '.php';
            $suite->addTestSuite($testcase);
    }

    $listener = new HTML_TestListener();
        $result = TestUnit::run($suite, $listener);
    $result->removeListener($listener);
    $result->report();
    ?>
</body>
</html>