<?php
/**
 * API Unit tests for HTML_CSS package.
 * 
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 */

class HTML_CSS_TestCase_bug1084 extends PHPUnit_TestCase
{
    /**
     * A CSS object
     * @var        object
     */
    var $stylesheet;

    function HTML_CSS_TestCase_bug1084($name)
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
     * BUG#1084 parseSelectors incorrectly assumes selector structure
     *
     */  
    function test_bug1084()
    {
        if (!$this->_methodExists('parseSelectors')) {
            return;
        }

        $sa = '#heading .shortname';
        $a = $this->stylesheet->parseSelectors($sa);
        if ($a != $sa) {
            $this->raiseError(1084,'error',
                              array('selector' => $sa),
                              'parseSelectors incorrectly assumes selector structure "%selector%"');

        }
        $this->_getResult();

        $sb = '#heading .icon';
        $b = $this->stylesheet->parseSelectors($sb);
        if ($b != $sb) {
            $this->raiseError(1084,'error',
                              array('selector' => $sb),
                              'parseSelectors incorrectly assumes selector structure "%selector%"');

        }
        $this->_getResult();

        $sc = '#heading .icon img';
        $c = $this->stylesheet->parseSelectors($sc);
        if ($c != $sc) {
            $this->raiseError(1084,'error',
                              array('selector' => $sc),
                              'parseSelectors incorrectly assumes selector structure "%selector%"');

        }
        $this->_getResult();

        $sd = 'a#heading.icon:active';
        $d = $this->stylesheet->parseSelectors($sd);
        if ($d != $sd) {
            $this->raiseError(1084,'error',
                              array('selector' => $sd),
                              'parseSelectors does not correctly parse selector structure "%selector%"');

        }
        $this->_getResult();

        $se = '#heading';
        $e = $this->stylesheet->parseSelectors($se);
        if ($e != $se) {
            $this->raiseError(1084,'error',
                              array('selector' => $se),
                              'parseSelectors does not correctly parse selector structure "%selector%"');

        }
        $this->_getResult();
    }
}
?>
