<?php
/**
 * API Unit tests for HTML_CSS package.
 * 
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 */

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

        $logger['display_errors'] = 'off';                        // don't use PEAR::Log display driver
        $logger['msgCallback'] = array(&$this, '_msgCallback');   // remove file&line context in error message
        $logger['pushCallback'] = array(&$this, '_pushCallback'); // don't die when an exception is thrown

        $attrs = array();
        $this->stylesheet = new HTML_CSS($attrs, $logger);

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

    function _stripWhitespace($str)
    {
        return preg_replace('/\\s+/', '', $str);
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

    function _msgCallback(&$stack, $err)
    {
        $message = call_user_func_array(array(&$stack, 'getErrorMessage'), array(&$stack, $err, '%__msg%'));
        return $message;
    }

    function _pushCallback($err)
    {
        // don't die if the error is an exception (as default callback)
    }

    function _getResult()
    {
        $s = &PEAR_ErrorStack::singleton('HTML_CSS');
        if ($s->hasErrors()) {
            $err = $s->pop();
            $this->assertTrue(false, $err['message']);
        } else {
            $this->assertTrue(true);
	}
    }

    function raiseError($code, $level, $params, $msg)
    {
        $err = PEAR_ErrorStack::staticPush($this->stylesheet->_package, $code, $level, $params, $msg, false, null);
        return $err;
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
        $font = $this->stylesheet->getStyle('p', 'font-family');
        if ($font != 'Times') {
            $this->raiseError(1072,'error',
                              array(),
                              'HTML_CSS is not cascading style when a "selector" is part of a group');

        }
        $this->_getResult();
    }
}
?>
