<?php

/**
 * API Unit tests for HTML_CSS package.
 * 
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 */

class CSS_Api_TestCase extends PHPUnit_TestCase
{
    /**
     * A CSS object
     * @var        object
     */
    var $stylesheet;

    function CSS_Api_TestCase($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        error_reporting(E_ALL);
        $this->errorOccured = false;
        set_error_handler(array(&$this, 'errorHandler'));

        $this->stylesheet = new HTML_CSS();
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

    function errorHandler($errno, $errstr, $errfile, $errline) {
        //die("$errstr in $errfile at line $errline: $errstr");
        $this->errorOccured = true;
        $this->assertTrue(false, "$errstr at line $errline");
    }

   /**
    * Tests a createGroup method 
    *
    * - fail1: wrong group id
    */  
    function test_createGroup_fail1()
    {
        $group = $this->css_grpcnt + 1;
        $id = $this->stylesheet->createGroup('table tr td, table tr th');
        if ($id <= $group) {
            $this->assertTrue(false, "cannot create new group ($id)");
        }
        $this->assertTrue(true);
    }

    function test_createGroup_fail2()
    {
        $group = $this->css_grpcnt;
        $this->stylesheet->createGroup('table tr td, table tr th', $group);
        $this->assertTrue(true);
    }

    function test_createGroup()
    {
        $group = 2;
        $this->stylesheet->createGroup('table tr td, table tr th', $group);
        $this->assertTrue(true);
    }

   /**
    * Tests a unsetGroup method 
    *
    * - fail1: wrong group id
    */  
    function test_unsetGroup_fail1()
    {
        $group = $this->css_grpcnt + 1;
        $this->stylesheet->unsetGroup($group);
        if (isset($this->stylesheet->_groups[$group])) {
            $this->assertTrue(false, "group {$group} is not unset");
        }
        $this->assertTrue(true);
    }

   /**
    * Tests a setGroupStyle method 
    *
    * - fail1: wrong group id
    */  
    function test_setGroupStyle_fail1()
    {
        $group1 = $this->css_grpcnt + 1;
        $this->stylesheet->setGroupStyle($group1, 'color', '#ffffff');
        $group2 = $this->stylesheet->createGroup('p, div');
        if ($group2 == $group1) {
            $this->assertTrue(false, "warning previous group2 properties are overrided");
        }
        $this->assertTrue(true);
    }

    function test_setGroupStyle()
    {
        $group1 = $this->css_grpcnt;
        $this->stylesheet->setGroupStyle($group1, 'color', '#ffffff');
        $group2 = $this->stylesheet->createGroup('p, div');
        if ($group2 == $group1) {
            $this->assertTrue(false, "warning previous group2 properties are overrided");
        }
        $this->assertTrue(true);
    }

   /**
    * Tests a getGroupStyle method 
    *
    * - fail1: wrong group id
    */  
    function test_getGroupStyle_fail1()
    {
        $group1 = $this->css_grpcnt + 1;
        $property = 'color';
        $val = $this->stylesheet->getGroupStyle($group1, $property);
        if (is_null($val)) {
            $this->assertTrue(false, "group $group1 or property '$property' are undefined");
        }
        $this->assertTrue(true);
    }

    function test_getGroupStyle()
    {
        $group1 = $this->css_grpcnt;
        $property = 'color';
        $val = $this->stylesheet->getGroupStyle($group1, $property);
        if (is_null($val)) {
            $this->assertTrue(false, "group $group1 or property '$property' are undefined");
        }
        $this->assertTrue(true);
    }

   /**
    * Tests a removeGroupSelector method 
    *
    * - fail1: wrong group id
    */  
    function test_removeGroupSelector_fail1()
    {
        $group1 = $this->css_grpcnt + 1;
        $val = $this->stylesheet->removeGroupSelector($group1, 'html');
        if (isset($this->stylesheet->_groups[$group1]['selectors'])) {
            $this->assertTrue(false, "group {$group} is not unset");
        }
        $this->assertTrue(true, $this->stylesheet->toString() );
    }

    function test_removeGroupSelector()
    {
        $group1 = $this->css_grpcnt;
        $selector = 'html';
        $val = $this->stylesheet->removeGroupSelector($group1, $selector);
        if (isset($this->stylesheet->_groups[$group1]['selectors'][$selector])) {
            $this->assertTrue(false, "group {$group1} selector '$selector' is not unset");
        }
        $this->assertTrue(true, $this->stylesheet->toString() );
    }

   /**
    * Tests a getStyle method 
    *
    * - fail1: wrong element
    */  
    function test_getStyle_fail1()
    {
        $elm = 'h1';
        $val = $this->stylesheet->getStyle($elm, 'color');
        $this->assertTrue(true, $this->stylesheet->toString() );
    }

    function test_getStyle()
    {
        $elm = 'h2';
        $val = $this->stylesheet->getStyle($elm, 'color');
        $this->assertTrue(true, $this->stylesheet->toString() );
    }

   /**
    * Tests a setSameStyle method 
    *
    * - fail1: wrong selector
    */  
    function test_setSameStyle_fail1()
    {
        $selector = 'body';
        $val = $this->stylesheet->setSameStyle('.myclass', $selector);
        $this->assertTrue(true, $this->stylesheet->toString() );
    }

    function test_setSameStyle()
    {
        $selector = 'h2';
        $val = $this->stylesheet->setSameStyle('.myclass', $selector);
        $this->assertTrue(true, $this->stylesheet->toString() );
    }
}

?>
