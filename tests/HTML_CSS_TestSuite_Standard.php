<?php
/**
 * Test suite for the HTML_CSS class
 *
 * PHP version 5
 *
 * @category HTML
 * @package  HTML_CSS
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/HTML_CSS
 * @since    File available since Release 1.4.0
 */
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "HTML_CSSTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'HTML/CSS.php';
require_once 'PEAR.php';

/**
 * Test suite class to test standard HTML_CSS API.
 *
 * @category HTML
 * @package  HTML_CSS
 * @author   Laurent Laville <pear@laurent-laville.org>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @version  Release: $id$
 * @link     http://pear.php.net/package/HTML_CSS
 * @since    File available since Release 1.4.0
 */
class HTML_CSS_TestSuite_Standard extends PHPUnit_Framework_TestCase
{
    /**
     * A CSS object
     * @var  object
     */
    protected $css;

    /**
     * Runs the test methods of this class.
     *
     * @static
     * @return void
     */
    public static function main()
    {
        include_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite('HTML_CSS Standard Tests');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $attrs = array();
        $prefs = array('push_callback'  => array($this, 'handleError'),
                       'error_callback' => array($this, 'handleErrorOutput'));

        $this->css = new HTML_CSS($attrs, $prefs);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->css);
    }

    /**
     * Don't die if the error is an exception (as default callback)
     *
     * @param int    $code  a numeric error code.
     *                      Valid are HTML_CSS_ERROR_* constants
     * @param string $level error level ('exception', 'error', 'warning', ...)
     *
     * @return int PEAR_ERROR_CALLBACK
     */
    public function handleError($code, $level)
    {
        return PEAR_ERROR_CALLBACK;
    }

    /**
     * Do nothing (no display, no log) when an error is raised
     *
     * @param object $css_error instance of HTML_CSS_Error
     *
     * @return void
     */
    public function handleErrorOutput($css_error)
    {
    }

    /**
     * Tests setting options all at once.
     *
     * @return void
     */
    public function testSetOptions()
    {
        $tab = '  ';
        $eol = strtolower(substr(PHP_OS, 0, 3)) == 'win' ? "\r\n" : "\n";

        $options = array('xhtml' => true, 'tab' => $tab, 'cache' => true,
            'oneline' => false, 'charset' => 'iso-8859-1',
            'contentDisposition' => false, 'lineEnd' => $eol,
            'groupsfirst' => true, 'allowduplicates' => false);

        foreach ($options as $opt => $val) {
            $this->css->__set($opt, $val);
            $this->assertSame($this->css->__get($opt), $val,
                "option '$opt' was not set");
        }
    }

    /**
     * Tests setting the 'xhtml' option.
     *
     * @return void
     */
    public function testSetXhtmlCompliance()
    {
        $arg = true;
        $e   = $this->css->setXhtmlCompliance($arg);
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);
        $this->assertSame($this->css->__get('xhtml'), $arg, $msg);
    }

    /**
     * Tests setting the 'tab' option.
     *
     * @return void
     */
    public function testSetTab()
    {
        $arg = "\t";
        $e   = $this->css->setTab($arg);
        $this->assertSame($this->css->__get('tab'), $arg,
            "'tab' option does not match");
    }

    /**
     * Tests setting the 'cache' option.
     *
     * @return void
     */
    public function testSetCache()
    {
        $arg = false;
        $e   = $this->css->setCache($arg);
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);
        $this->assertSame($this->css->__get('cache'), $arg, $msg);
    }

    /**
     * Tests setting the 'oneline' option.
     *
     * @return void
     */
    public function testSetSingleLineOutput()
    {
        $arg = true;
        $e   = $this->css->setSingleLineOutput($arg);
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);
        $this->assertSame($this->css->__get('oneline'), $arg, $msg);
    }

    /**
     * Tests setting the 'charset' option.
     *
     * @return void
     */
    public function testSetCharset()
    {
        $arg = 'UTF-8';
        $e   = $this->css->setCharset($arg);
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);
        $this->assertSame($this->css->__get('charset'), $arg, $msg);
    }

    /**
     * Tests setting the 'contentDisposition' option.
     *
     * @return void
     */
    public function testSetContentDisposition()
    {
        $enable   = true;
        $filename = 'myFile.css';
        $e        = $this->css->setContentDisposition($enable, $filename);
        $msg      = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);
        $this->assertSame($this->css->__get('contentDisposition'), $filename,
            $msg);
    }

    /**
     * Tests setting the 'lineEnd' option.
     *
     * @return void
     */
    public function testSetLineEnd()
    {
        $arg = "\n";
        $e   = $this->css->setLineEnd($arg);
        $this->assertSame($this->css->__get('lineEnd'), $arg,
            "'lineEnd' option does not match");
    }

    /**
     * Tests setting the 'groupsfirst' option.
     *
     * @return void
     */
    public function testSetOutputGroupsFirst()
    {
        $arg = false;
        $e   = $this->css->setOutputGroupsFirst($arg);
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);
        $this->assertSame($this->css->__get('groupsfirst'), $arg, $msg);
    }

    /**
     * Tests setting the 'allowduplicates' option.
     *
     * @return void
     */
    public function testSetAllowDuplicates()
    {
        $arg = true;
        $e   = $this->css->__set('allowduplicates', $arg);
        $this->assertSame($this->css->__get('allowduplicates'), $arg,
            "'groupsfirst' option does not match");
    }

    /**
     * Tests handling selector and property values
     *
     * @return void
     */
    public function testStyle()
    {
        $element  = 'h2';
        $property = 'color';
        $value    = '#FFFFFF';
        $e        = $this->css->setStyle($element, $property, $value);
        $msg      = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), 'wrong set arguments');

        $e   = $this->css->getStyle($element, $property);
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), 'wrong get arguments');
        $this->assertSame($value, $e, 'property value does not match');

        $e = $this->css->setSameStyle('.myclass', 'h2');

        $gs  = array('h2, .myclass' => array('color' => $value));
        $def = $this->css->toArray();
        $this->assertSame($gs, $def, 'invalid same style group selector result');
    }

    /**
     * Tests building/removing CSS definition group
     *
     * @return void
     */
    public function testGroup()
    {
        $g   = 1;
        $e   = $this->css->createGroup('body, html');
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);
        $this->assertSame($g, $e, 'impossible to create CSS def group');

        $e   = $this->css->unsetGroup($g);
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);
    }

    /**
     * Tests setting/getting styles for a CSS definition group
     *
     * @return void
     */
    public function testGroupStyle()
    {
        $p   = '#ffffff';
        $g   = $this->css->createGroup('body, html');
        $e   = $this->css->setGroupStyle($g, 'color', $p);
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);

        $e   = $this->css->getGroupStyle($g, 'color');
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);
        $this->assertSame($p, $e,
            "color property of group #$g does not match");

        $gs  = array('body, html' => array('color' => $p));
        $def = $this->css->toArray();
        $this->assertSame($gs, $def, 'invalid group selector result');
    }

    /**
     * Tests add/remove selector to a CSS definition group
     *
     * @return void
     */
    public function testGroupSelector()
    {
        $g = $this->css->createGroup('body, html');
        $this->css->setGroupStyle($g, 'margin', '2px');
        $this->css->setGroupStyle($g, 'padding', '0');
        $this->css->setGroupStyle($g, 'border', '0');

        $old_gs  = array('body, html' =>
                       array('margin' => '2px',
                             'padding' => '0',
                             'border' => '0'));
        $cur_def = $this->css->toArray();
        $this->assertSame($old_gs, $cur_def,
            'invalid source group selector result');

        $e   = $this->css->removeGroupSelector($g, 'body');
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);

        $e   = $this->css->addGroupSelector($g, '.large');
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);

        $new_gs  = array('html, .large' =>
                       array('margin' => '2px',
                             'padding' => '0',
                             'border' => '0'));
        $cur_def = $this->css->toArray();
        $this->assertSame($new_gs, $cur_def,
            'invalid target group selector result');
    }

    /**
     * Tests parsing a simple string that contains CSS information.
     *
     * @return void
     */
    public function testParseString()
    {
        $strcss = '
html, body {
 margin: 2px;
 padding: 0px;
 border: 0px;
}

p, body {
 margin: 4px;
}
';

        $e   = $this->css->parseString($strcss);
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);

        $gs  = array('html, body' =>
                  array('margin' => '2px',
                        'padding' => '0px',
                        'border' => '0px'),
                    'p, body' =>
                  array('margin' => '4px'));
        $def = $this->css->toArray();
        $this->assertSame($gs, $def, 'string parses does not match');
    }

    /**
     * Tests parsing a file that contains CSS information.
     *
     * @return void
     */
    public function testParseFile()
    {
        // parsing a file contents
        $e   = $this->css->parseFile('stylesheet.css');
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);

        $gs  = array('body' =>
                   array('font' => 'normal 68% verdana,arial,helvetica',
                     'color' => '#000000'),
                   'table tr td, table tr th' =>
                   array('font-size' => '68%'),
                   'table.details tr th' =>
                   array('font-weight' => 'bold',
                     'text-align' => 'left',
                     'background' => '#a6caf0'),
                   'table.details tr' =>
                   array('background' => '#eeeee0'),
                   'p' =>
                   array('line-height' => '1.5em',
                     'margin-top' => '0.5em',
                     'margin-bottom' => '1.0em'),
                   'h1' =>
                   array('margin' => '0px 0px 5px',
                     'font' => '165% verdana,arial,helvetica'),
                   'h2' =>
                   array('margin-top' => '1em',
                     'margin-bottom' => '0.5em',
                     'font' => 'bold 125% verdana,arial,helvetica'),
                   'h3' =>
                   array('margin-bottom' => '0.5em',
                     'font' => 'bold 115% verdana,arial,helvetica'),
                   'h4' =>
                   array('margin-bottom' => '0.5em',
                     'font' => 'bold 100% verdana,arial,helvetica'),
                   'h5' =>
                   array('margin-bottom' => '0.5em',
                     'font' => 'bold 100% verdana,arial,helvetica'),
                   'h6' =>
                   array('margin-bottom' => '0.5em',
                     'font' => 'bold 100% verdana,arial,helvetica'),
                   '.Error' =>
                   array('font-weight' => 'bold',
                     'color' => 'red'),
                   '.Failure, .Unexpected' =>
                   array('background' => '#ff0000',
                     'font-weight' => 'bold',
                     'color' => 'black'),
                   '.Unknown' =>
                   array('background' => '#ffff00',
                     'font-weight' => 'bold',
                     'color' => 'black'),
                   '.Pass, .Expected' =>
                   array('background' => '#00ff00',
                     'font-weight' => 'bold',
                     'color' => 'black'),
                   '.Properties' =>
                   array('text-align' => 'right'),
                   'code.expected' =>
                   array('color' => 'green',
                     'background' => 'none',
                     'font-weight' => 'normal'),
                   'code.actual' =>
                   array('color' => 'red',
                     'background' => 'none',
                     'font-weight' => 'normal'),
                   '.typeinfo' =>
                   array('color' => 'gray'));
        $def = $this->css->toArray();
        $this->assertSame($gs, $def, 'css file parses does not match');
    }

    /**
     * Tests parsing multiple data sources (a simple string and a file),
     * that contains CSS information, at once.
     *
     * @return void
     */
    public function testParseData()
    {
        $strcss   = '
body, p { background-color: white; font: 1.2em Arial; }
p, div#black { color: black; }
div{ color: green; }
p { margin-left: 3em; }
';
        $css_data = array('stylesheet.css', $strcss);

        $e   = $this->css->parseData($css_data);
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);

        $gs  = array('body' =>
                   array('font' => 'normal 68% verdana,arial,helvetica',
                     'color' => '#000000'),
                   'table tr td, table tr th' =>
                   array('font-size' => '68%'),
                   'table.details tr th' =>
                   array('font-weight' => 'bold',
                     'text-align' => 'left',
                     'background' => '#a6caf0'),
                   'table.details tr' =>
                   array('background' => '#eeeee0'),
                   'p' =>
                   array('line-height' => '1.5em',
                     'margin-top' => '0.5em',
                     'margin-bottom' => '1.0em',
                     'margin-left' => '3em'),
                   'h1' =>
                   array('margin' => '0px 0px 5px',
                     'font' => '165% verdana,arial,helvetica'),
                   'h2' =>
                   array('margin-top' => '1em',
                     'margin-bottom' => '0.5em',
                     'font' => 'bold 125% verdana,arial,helvetica'),
                   'h3' =>
                   array('margin-bottom' => '0.5em',
                     'font' => 'bold 115% verdana,arial,helvetica'),
                   'h4' =>
                   array('margin-bottom' => '0.5em',
                     'font' => 'bold 100% verdana,arial,helvetica'),
                   'h5' =>
                   array('margin-bottom' => '0.5em',
                     'font' => 'bold 100% verdana,arial,helvetica'),
                   'h6' =>
                   array('margin-bottom' => '0.5em',
                     'font' => 'bold 100% verdana,arial,helvetica'),
                   '.Error' =>
                   array('font-weight' => 'bold',
                     'color' => 'red'),
                   '.Failure, .Unexpected' =>
                   array('background' => '#ff0000',
                     'font-weight' => 'bold',
                     'color' => 'black'),
                   '.Unknown' =>
                   array('background' => '#ffff00',
                     'font-weight' => 'bold',
                     'color' => 'black'),
                   '.Pass, .Expected' =>
                   array('background' => '#00ff00',
                     'font-weight' => 'bold',
                     'color' => 'black'),
                   '.Properties' =>
                   array('text-align' => 'right'),
                   'code.expected' =>
                   array('color' => 'green',
                     'background' => 'none',
                     'font-weight' => 'normal'),
                   'code.actual' =>
                   array('color' => 'red',
                     'background' => 'none',
                     'font-weight' => 'normal'),
                   '.typeinfo' =>
                   array('color' => 'gray'),
                   'body, p' =>
                   array('background-color' => 'white',
                     'font' => '1.2em Arial'),
                   'p, div#black' =>
                   array('color' => 'black'),
                   'div' =>
                   array('color' => 'green'));
        $def = $this->css->toArray();
        $this->assertSame($gs, $def, 'css data sources parses does not match');
    }

    /**
     * Tests parsing data source with allow duplicates option activated.
     *
     * Internet Explorer <= 6 does not handle box model in same way as others
     * browsers that are better W3C compliant. For this reason, we need to fix
     * boxes size with a hack such as this one you can find in example that follow.
     * You can notice the duplicate 'voice-family' and 'height' properties.
     *
     * @return void
     */
    public function testAllowDuplicates()
    {
        $strcss = '
#header {
  background-color: ivory;
  font-family: "Times New Roman", Times, serif;
  font-size: 5mm;
  text-align: center;
  /* IE 5.5 */
  height:81px;
  border-top:1px solid #000;
  border-right:1px solid #000;
  border-left:1px solid #000;
  voice-family: "\"}\"";
  voice-family: inherit;
  /* IE 6 */
  height: 99px;
}
';
        // set local 'allowDuplicates' option
        $e   = $this->css->parseString($strcss, true);
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);

        $gs  = array('#header' =>
                   array(1 => array('background-color' => 'ivory'),
                     2 => array('font-family' => '"Times New Roman", Times, serif'),
                     3 => array('font-size' => '5mm'),
                     4 => array('text-align' => 'center'),
                     5 => array('height' => '81px'),
                     6 => array('border-top' => '1px solid #000'),
                     7 => array('border-right' => '1px solid #000'),
                     8 => array('border-left' => '1px solid #000'),
                     9 => array('voice-family' => '"\\"}\\""'),
                     10 => array('voice-family' => 'inherit'),
                     11 => array('height' => '99px')));
        $def = $this->css->toArray();
        $this->assertSame($gs, $def, 'css source parses does not match');
    }

    /**
     * Tests render to inline html code, array, string or file.
     *
     * @return void
     */
    public function testOutput()
    {
        /**
         * Depending of platform (windows, unix, ...) be sure to compare
         * same end of line marker.
         */
        $this->css->setlineEnd("\n");

        $strcss  = '{eol}';
        $strcss .= 'ul, body {{eol}';
        $strcss .= ' padding: 1em 2em;{eol}';
        $strcss .= ' color: red;{eol}';
        $strcss .= '}{eol}';
        $strcss  = str_replace('{eol}', $this->css->lineEnd, $strcss);
        $this->css->parseString($strcss);

        // to inline
        $expected = 'padding:1em 2em;color:red;';
        $e        = $this->css->toInline('body');
        $msg      = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);
        $this->assertSame($e, $expected, 'inline output does not match');

        // to array
        $gs  = array('ul, body' =>
                   array('padding' => '1em 2em',
                     'color' => 'red'));
        $def = $this->css->toArray();
        $this->assertSame($gs, $def, 'array output does not match');

        // to string, multi lines
        $this->css->oneline
                   = false;  // PHP5 signature, see __set() for PHP4
        $expNline  = '{eol}';
        $expNline .= 'ul, body {{eol}';
        $expNline .= '{tab}padding: 1em 2em;{eol}';
        $expNline .= '{tab}color: red;{eol}';
        $expNline .= '}{eol}';
        $expNline  = str_replace(array('{tab}','{eol}'),
                         array($this->css->tab, $this->css->lineEnd), $expNline);
        $str       = $this->css->toString();
        $this->assertSame($str, $expNline, 'normal string output does not match');

        // to string, one line
        $this->css->oneline
                  = true;   // PHP5 signature, see __set() for PHP4
        $exp1line = 'ul, body { padding: 1em 2em; color: red; }';
        $str      = $this->css->toString();
        $this->assertSame($str, $exp1line, 'online string output does not match');

        $tmpFile = tempnam(dirname(__FILE__), 'CSS');
        // to file, multi lines
        $this->css->oneline
             = false;   // PHP5 signature, see __set() for PHP4
        $e   = $this->css->toFile($tmpFile);
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);

        $str = file_get_contents($tmpFile);
        $this->assertSame($str, $expNline, 'normal file output does not match');

        // to file, one line
        $this->css->oneline
             = true;    // PHP5 signature, see __set() for PHP4
        $e   = $this->css->toFile($tmpFile);
        $msg = PEAR::isError($e) ? $e->getMessage() : null;
        $this->assertFalse(PEAR::isError($e), $msg);

        $str = file_get_contents($tmpFile);
        $this->assertSame($str, $exp1line, 'oneline file output does not match');

        unlink($tmpFile);
    }
}

// Call HTML_CSSTest::main() if file is executed directly.
if (PHPUnit_MAIN_METHOD == "HTML_CSS_TestSuite_Standard::main") {
    HTML_CSS_TestSuite_Standard::main();
}
?>