<?php
/**
 * API Unit tests for HTML_CSS package.
 * 
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 */

require_once 'PEAR.php';

class HTML_CSS_TestCase_addGroupSelector extends PHPUnit_TestCase
{
    /**
     * A CSS object
     * @var        object
     */
    var $stylesheet;

    function HTML_CSS_TestCase_addGroupSelector($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        $logger['pushCallback'] = array(&$this, '_pushCallback'); // don't die when an exception is thrown
        $attrs = array();
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

    function _pushCallback($code, $level)
    {
        // don't die if the error is an exception (as default callback)
        return true;
    }

    function _getResult($res)
    {
        if (PEAR::isError($res)) {
            $this->assertTrue(false, $res->getMessage());
        } else {
            $this->assertTrue(true);
	}
    }


    /**
     * Tests a addGroupSelector method 
     *
     * - fail1: wrong group id
     */  
    function test_addGroupSelector_fail1()
    {
        $group = $this->css_grpcnt + 1;
        $val = $this->stylesheet->addGroupSelector($group, 'html');
        $this->_getResult($val);
    }

    function test_addGroupSelector()
    {
        $group = $this->css_grpcnt;
        $val = $this->stylesheet->addGroupSelector($group, 'html');
        $this->_getResult($val);
    }
}
?>