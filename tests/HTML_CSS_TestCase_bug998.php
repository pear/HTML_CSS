<?php
/**
 * API Unit tests for HTML_CSS package.
 * 
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 */

require_once 'PEAR.php';

class HTML_CSS_TestCase_bug998 extends PHPUnit_TestCase
{
    /**
     * A CSS object
     * @var        object
     */
    var $stylesheet;

    function HTML_CSS_TestCase_bug998($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        $logger['pushCallback'] = array(&$this, '_pushCallback'); // don't die when an exception is thrown
        $attrs = array();
        $this->stylesheet = new HTML_CSS($attrs, $logger);

        $strcss = '
.sec { display: none; }
.month:before { content: "-"; }
.year:before { content: "-"; }
.min:before { content: ":"; }
.sec:before { content: ":"; }
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
     * BUG#998 parseString incorrectly reads attribute values with colons in
     *
     * When parsing in some CSS like:
     *
     * .sec { display: none; }
     * .month:before { content: "-"; }
     * .year:before { content: "-"; }
     * .min:before { content: ":"; }
     * .sec:before { content: ":"; }
     *
     * the resulting array should be:
     *
     * [.sec] => Array ( [display] =>  none )
     * [.month:before] => Array ( [content] =>  "-" )
     * [.year:before] => Array ( [content] =>  "-" )
     * [.min:before] => Array ( [content] =>  ":" )
     * [.sec:before] => Array ( [content] =>  ":" )
     */  
    function test_bug998()
    {
        if (!$this->_methodExists('toArray')) {
            return;
        }
        $e = $css = $this->stylesheet->toArray();
        if ($css['.min:before']['content'] != '":"') {
            $e = PEAR::raiseError('parseString incorrectly reads attribute values with colons in',
                                  998);
        }
        $this->_getResult($e);
    }
}
?>