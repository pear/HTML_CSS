<?php
/**
 * BUG #10103 regression test for HTML_CSS class.
 *
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 * @link       http://pear.php.net/bugs/bug.php?id=10103
 * @ignore
 */

require_once 'PEAR.php';

/**
 * @ignore
 */
class HTML_CSS_TestCase_bug10103 extends PHPUnit_TestCase
{
    /**
     * A CSS object
     * @var        object
     */
    var $stylesheet;

    function HTML_CSS_TestCase_bug10103($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        $attrs = array('xhtml' => false,
            'cache' => false,
            'oneline' => true,
            'groupsfirst' => false,
            'allowduplicates' => true
        );
        $prefs= array('push_callback' => array(&$this, '_handleError'));
        $this->stylesheet = new HTML_CSS($attrs, $prefs);
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
     * BUG#10103 typo error on handling "oneline" attribute in class constructor
     */
    function test_bug10103()
    {
        $this->assertTrue($this->stylesheet->_singleLine === true);
    }

    /**
     * setUp() try to set these values that are opposite side of default values
     *
     *   $attrs = array('xhtml' => false,
     *       'cache' => false,
     *       'oneline' => true,
     *       'groupsfirst' => false,
     *       'allowduplicates' => true
     *   );
     *
     * option            interval variable  default
     * ---------------------------------------------
     * xhtml             _xhtmlCompliant    true
     * cache             _cache             true
     * online            _singleLine        false
     * groupsfirst       _groupsFirst       true
     * allowduplicates   _allowDuplicates   false
     */
    function test_construct_xhtml()
    {
        $this->assertTrue($this->stylesheet->_xhtmlCompliant === false);
    }

    function test_construct_cache()
    {
        $this->assertTrue($this->stylesheet->_cache === false);
    }

    function test_construct_oneline()
    {
        $this->assertTrue($this->stylesheet->_singleLine === true);
    }

    function test_construct_groupsfirst()
    {
        $this->assertTrue($this->stylesheet->_groupsFirst === false);
    }

    function test_construct_allowduplicates()
    {
        $this->assertTrue($this->stylesheet->_allowDuplicates === true);
    }
}
?>