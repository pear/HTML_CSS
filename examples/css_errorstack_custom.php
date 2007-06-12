<?php
/**
 * Customize error renderer with PEAR_ErrorStack.
 *
 * @category   HTML
 * @package    HTML_CSS
 * @subpackage Examples
 * @author     Klaus Guenther <klaus@capitalfocus.org>
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @copyright  2005-2007 Klaus Guenther, Laurent Laville
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTML_CSS
 * @since      File available since Release 1.0.0RC1
 */

require_once 'HTML/CSS.php';
require_once 'HTML/CSS/Error.php';
require_once 'PEAR/ErrorStack.php';

class HTML_CSS_ErrorStack
{
    function HTML_CSS_ErrorStack()
    {
        $s = &PEAR_ErrorStack::singleton('HTML_CSS');
        $t = HTML_CSS_Error::_getErrorMessage();
        $s->setErrorMessageTemplate($t);
        $s->setContextCallback(array(&$this,'getBacktrace'));
        $logger = array(&$this,'log');
        $s->setLogger($logger);
        $s->pushCallback(array(&$this,'errorHandler'));
    }

    function push($code, $level, $params)
    {
        $s = &PEAR_ErrorStack::singleton('HTML_CSS');
        return $s->push($code, $level, $params);
    }

    function getBacktrace()
    {
        if (function_exists('debug_backtrace')) {
            $backtrace = debug_backtrace();
            $backtrace = $backtrace[count($backtrace)-1];
        } else {
            $backtrace = false;
        }
        return $backtrace;
    }

    function log($err)
    {
        global $prefs;

        $lineFormat = '<b>%1$s:</b> %2$s<br/>[%3$s]<hr/>'."<br/>\n";
        $contextFormat = 'in <b>%1$s</b> on line <b>%2$s</b>';

        if (isset($prefs['handler']['display']['lineFormat'])) {
            $lineFormat = $prefs['handler']['display']['lineFormat'];
        }
        if (isset($prefs['handler']['display']['contextFormat'])) {
            $contextFormat = $prefs['handler']['display']['contextFormat'];
        }

        $context = $err['context'];

        if ($context) {
            $file  = $context['file'];
            $line  = $context['line'];

            $contextExec = sprintf($contextFormat, $file, $line);
        } else {
            $contextExec = '';
        }

        printf($lineFormat,
               ucfirst(get_class($this)) . ' ' . $err['level'],
               $err['message'],
               $contextExec);
    }

    function errorHandler($err)
    {
        global $halt_onException;

        if ($halt_onException) {
            if ($err['level'] == 'exception') {
                return PEAR_ERRORSTACK_DIE;
            }
        }
    }
}

// set it to on if you want to halt script on any exception
$halt_onException = false;


// Example A. ---------------------------------------------

$stack =& new HTML_CSS_ErrorStack();

$attribs = array();
$prefs = array('error_handler' => array(&$stack, 'push'));

// A1. Error
$css1 = new HTML_CSS($attribs, $prefs);

$group1 = $css1->createGroup('body, html', 'grp1');
$group2 = $css1->createGroup('p, html', 'grp1');


// Example B. ---------------------------------------------

$displayConfig = array(
    'lineFormat' => '<b>%1$s</b>: %2$s<br/>%3$s<hr/>',
    'contextFormat' =>   '<b>File:</b> %1$s <br/>'
                       . '<b>Line:</b> %2$s '
);
$attribs = array();
$prefs = array(
    'error_handler' => array(&$stack, 'push'),
    'handler' => array('display' => $displayConfig)
);

$css2 = new HTML_CSS($attribs, $prefs);

// B1. Error
$css2->getStyle('h1', 'class');

// B2. Exception
$css2->setXhtmlCompliance('true');


print 'still alive !';

?>