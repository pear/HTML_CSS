<?php
/**
 * BUG #998 regression test for HTML_CSS class.
 *
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 * @link       http://pear.php.net/bugs/bug.php?id=998
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

        $attrs = array();
        $prefs= array('push_callback' => array(&$this, '_handleError'));
        $this->stylesheet = new HTML_CSS($attrs, $prefs);

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
        if (!$this->_methodExists('getStyle')) {
            return;
        }
        $e = $css = $this->stylesheet->getStyle('.sec:before', 'content');
        if ($css != '":"') {
            $e = PEAR::raiseError('parseString incorrectly reads attribute values with colons in',
                                  998);
        }
        $this->_getResult($e);
    }
}
?>