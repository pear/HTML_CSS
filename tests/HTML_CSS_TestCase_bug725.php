<?php
/**
 * API Unit tests for HTML_CSS package.
 * 
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 */

require_once 'PEAR.php';

class HTML_CSS_TestCase_bug725 extends PHPUnit_TestCase
{
    /**
     * A CSS object
     * @var        object
     */
    var $stylesheet;

    function HTML_CSS_TestCase_bug725($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        $logger['pushCallback'] = array(&$this, '_pushCallback'); // don't die when an exception is thrown
        $attrs = array();
        $this->stylesheet = new HTML_CSS($attrs, $logger);

        $strcss = "  body   td  { /* 3 spaces between body and td */
	margin: 20px;
	padding: 20px;
	border: 0;
	color: #444;
}";
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
     * BUG#725 differs hierarchy elements with difference in spaces between 
     *
     *       - setStyle should change the "body td" not add an other one
     */  
    function test_bug725()
    {
        if (!$this->_methodExists('setStyle')) {
            return;
        }
        if (!$this->_methodExists('toArray')) {
            return;
        }

        $style = 'body td  ';
        $e = $this->stylesheet->setStyle($style,'margin',0);
        $css = $this->stylesheet->toArray();
        if (count($css) > 1) {
            $e = PEAR::raiseError('setStyle should change the "' . $style 
                                  . '" not add an other one', 
                                  725);
        }
        $this->_getResult($e);
    }
}
?>