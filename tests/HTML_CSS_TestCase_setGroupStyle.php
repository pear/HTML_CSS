<?php
/**
 * API setGroupStyle Unit tests for HTML_CSS class.
 *
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 */

require_once 'PEAR.php';

class HTML_CSS_TestCase_setGroupStyle extends PHPUnit_TestCase
{
    /**
     * A CSS object
     * @var        object
     */
    var $stylesheet;

    function HTML_CSS_TestCase_setGroupStyle($name)
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
     * Tests a setGroupStyle method
     */
    function test_setGroupStyle_fail_property_no_string()
    {
        $group1 = $this->css_grpcnt;
        $gs = $this->stylesheet->setGroupStyle($group1, 2, '#ffffff');
        $this->_getResult($gs);
    }

    function test_setGroupStyle_fail_value_no_string()
    {
        $group1 = $this->css_grpcnt;
        $gs = $this->stylesheet->setGroupStyle($group1, 'color', true);
        $this->_getResult($gs);
    }

    function test_setGroupStyle_fail_invalid_groupid()
    {
        $group1 = $this->css_grpcnt + 1;
        $gs = $this->stylesheet->setGroupStyle($group1, 'color', '#ffffff');
        $this->_getResult($gs);
    }

    function test_setGroupStyle()
    {
        $group1 = $this->css_grpcnt;
        $gs = $this->stylesheet->setGroupStyle($group1, 'color', '#ffffff');
        $this->_getResult($gs);
    }
}
?>