<?php

/**
 * API Unit tests for HTML_CSS package.
 * 
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 */

class HTML_CSS_TestCase_getGroupStyle extends PHPUnit_TestCase
{
    /**
     * A CSS object
     * @var        object
     */
    var $stylesheet;

    function HTML_CSS_TestCase_getGroupStyle($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        error_reporting(E_ALL);
        $this->errorOccured = false;
        set_error_handler(array(&$this, 'errorHandler'));

        $attrs = array();
        $logger['display_errors'] = 'off';                      // don't use PEAR::Log display driver
        $logger['msgCallback'] = array(&$this, '_msgCallback'); // remove file&line context in error message
        $this->stylesheet = new HTML_CSS($attrs, $logger);

        $this->css_group1 = $this->stylesheet->createGroup('body, html');
        $this->stylesheet->setGroupStyle($this->css_group1, 'color', '#ffffff');
        $this->stylesheet->setStyle('h2', 'color', '#ff0000');
        $this->css_grpcnt = $this->css_group1;
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
        if (in_array(strtolower($name), get_class_methods($this->stylesheet))) {
            return true;
        }
        $this->assertTrue(false, 'method '. $name . ' not implemented in ' . get_class($this->stylesheet));
        return false;
    }

    function _msgCallback(&$stack, $err)
    {
        $message = call_user_func_array(array(&$stack, 'getErrorMessage'), array(&$stack, $err));

        if (isset($err['context']['function'])) {
            $message .= ' in ' . $err['context']['class'] . '::' . $err['context']['function'];
        }
        return $message;
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

    function errorHandler($errno, $errstr, $errfile, $errline) {
        $this->errorOccured = true;
        $this->assertTrue(false, "$errstr at line $errline");
    }

   /**
    * Tests a getGroupStyle method 
    *
    * - fail1: wrong group id
    */  
    function test_getGroupStyle_fail1()
    {
        $group = $this->css_grpcnt + 1;
        $property = 'color';
        $val = $this->stylesheet->getGroupStyle($group, $property);
        $this->_getResult();
    }

    function test_getGroupStyle()
    {
        $group = $this->css_grpcnt;
        $property = 'color';
        $val = $this->stylesheet->getGroupStyle($group, $property);
        $this->_getResult();
    }
}

?>
