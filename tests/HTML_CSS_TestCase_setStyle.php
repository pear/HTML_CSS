<?php
/**
 * API setStyle Unit tests for HTML_CSS class.
 * 
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 */

require_once 'PEAR.php';

class HTML_CSS_TestCase_setStyle extends PHPUnit_TestCase
{
    /**
     * A CSS object
     * @var        object
     */
    var $stylesheet;

    function HTML_CSS_TestCase_setStyle($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        $attrs = array();
        $prefs= array('push_callback' => array(&$this, '_handleError'));
        $this->stylesheet = new HTML_CSS($attrs, $prefs);

        $this->css_group1 = $this->stylesheet->createGroup('body, html');
        $this->stylesheet->setGroupStyle($this->css_group1, 'color', '#ffffff');
        $this->stylesheet->setStyle('h2', 'color', '#ff0000');
        $this->css_grpcnt = $this->css_group1;
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
     * Tests a setStyle method 
     *
     */  
    function test_setStyle_fail_element_no_string()
    {
        $element = 2;
        $property = 'width';
        $value = '1px';
        $val = $this->stylesheet->setStyle($element, $property, $value);
        $this->_getResult($val);
    }

    function test_setStyle_fail_property_no_string()
    {
        $element = 'h2';
        $property = true;
        $value = '1px';
        $val = $this->stylesheet->setStyle($element, $property, $value);
        $this->_getResult($val);
    }

    function test_setStyle_fail_value_no_string()
    {
        $element = 'h2';
        $property = 'width';
        $value = 1;
        $val = $this->stylesheet->setStyle($element, $property, $value);
        $this->_getResult($val);
    }

    function test_setStyle()
    {
        $element = 'h2';
        $property = 'color';
        $value = '#FFFFFF';
        $val = $this->stylesheet->setStyle($element, $property, $value);
        $this->_getResult($val);
    }
}
?>