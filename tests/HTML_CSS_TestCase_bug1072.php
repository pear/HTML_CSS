<?php
/**
 * BUG #1072 regression test for HTML_CSS class.
 * 
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 * @link       http://pear.php.net/bugs/bug.php?id=1072
 */

require_once 'PEAR.php';

class HTML_CSS_TestCase_bug1072 extends PHPUnit_TestCase
{
    /**
     * A CSS object
     * @var        object
     */
    var $stylesheet;

    function HTML_CSS_TestCase_bug1072($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        $attrs = array();
        $prefs= array('push_callback' => array(&$this, '_handleError'));
        $this->stylesheet = new HTML_CSS($attrs, $prefs);

        $strcss = '
p { font-family: Arial; }
p { font-family: Courier; }
p, td { font-family: Times; }
td p { font-family: Comic; }
';
        $this->stylesheet->parseString($strcss);
    }

    function tearDown()
    {
        unset($this->stylesheet);
    }

    function _methodExists($name) 
    {
        if (substr(PHP_VERSION,0,1) < '5') {
            $n = strtolower($name);
        } else {
            $n = $name;
        }
        if (in_array($n, get_class_methods($this->stylesheet))) {
            return true;
        }
        $this->assertTrue(false, 'method '. $name . ' not implemented in ' . get_class($this->stylesheet));
        return false;
    }

    function _handleError($code, $level)
    {
        // don't die if the error is an exception (as default callback)
        return PEAR_ERROR_RETURN;
    }

    function _getResult($res)
    {
        if (PEAR::isError($res)) {
            $msg = $res->getMessage() . '&nbsp;&gt;&gt;';
            $this->assertTrue(false, $msg);
        } else {
            $this->assertTrue(true);
	}
    }

    /**
     * BUG#1072 HTML_CSS Not cascading properties
     *
     */  
    function test_bug1072()
    {
        if (!$this->_methodExists('getStyle')) {
            return;
        }
        $e = $font = $this->stylesheet->getStyle('p', 'font-family');
        if ($font != 'Times') {
            $e = PEAR::raiseError('HTML_CSS is not cascading style when a "selector" is part of a group',
                                  1072);
        }
        $this->_getResult($e);
    }
}
?>