<?php
/**
 * BUG #1084 regression test for HTML_CSS class.
 *
 * @version    $Id$
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_CSS
 * @link       http://pear.php.net/bugs/bug.php?id=1084
 */

require_once 'PEAR.php';

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

        $attrs = array();
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
     * BUG#1084 parseSelectors incorrectly assumes selector structure
     *
     */
    function test_bug1084()
    {
        if (!$this->_methodExists('_parseSelectors')) {
            return;
        }

        $sa = '#heading .shortname';
        $a = $this->stylesheet->_parseSelectors($sa);
        if ($a != $sa) {
            $a = PEAR::raiseError('parseSelectors incorrectly assumes selector structure "'
                                  . $sa . '"',
                                  1084);
        }
        $this->_getResult($a);

        $sb = '#heading .icon';
        $b = $this->stylesheet->_parseSelectors($sb);
        if ($b != $sb) {
            $b = PEAR::raiseError('parseSelectors incorrectly assumes selector structure "'
                                  . $sb . '"',
                                  1084);
        }
        $this->_getResult($b);

        $sc = '#heading .icon img';
        $c = $this->stylesheet->_parseSelectors($sc);
        if ($c != $sc) {
            $c = PEAR::raiseError('parseSelectors incorrectly assumes selector structure "'
                                  . $sc . '"',
                                  1084);
        }
        $this->_getResult($c);

        $sd = 'a#heading.icon:active';
        $d = $this->stylesheet->_parseSelectors($sd);
        if ($d != $sd) {
            $d = PEAR::raiseError('parseSelectors does not correctly parse selector structure "'
                                  . $sd . '"',
                                  1084);
        }
        $this->_getResult($d);

        $se = '#heading';
        $e = $this->stylesheet->_parseSelectors($se);
        if ($e != $se) {
            $e = PEAR::raiseError('parseSelectors does not correctly parse selector structure "'
                                  . $se . '"',
                                  1084);
        }
        $this->_getResult($e);
    }
}
?>