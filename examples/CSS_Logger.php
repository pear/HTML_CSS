<?php
/**
 * Example of error handler 
 *
 * @version    0.3.5
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @access     public
 * @category   HTML
 * @package    HTML_CSS
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 */

require_once 'HTML/CSS.php';

class myErrorHandler
{
    var $_display;

    function myErrorHandler($display = null)
    {
        $default = array('lineFormat' => '<b>%1$s</b>: %2$s %3$s',
                         'contextFormat' => ' in <b>%3$s</b> (file <b>%1$s</b> at line <b>%2$s</b>)',
                         'eol' => "\n"
                         );

        if (is_array($display)) {
            $this->_display = array_merge($default, $display);
        } else {
            $this->_display = $default;
	}
    }

    function _handleError($code, $level)
    {
        return true;
    }

    function errorCallback($err)
    {
        $display_errors = ini_get('display_errors');
        $log_errors = ini_get('log_errors');

        $info = $err->getUserInfo();
        $level = isset($info['errorLevel']) ? $info['errorLevel'] : 'notice';
        $message = $err->getMessage();
        $backtrace = $err->getBacktrace();
        
        if ($display_errors) {
            $this->display($message, $level, $backtrace);
        }
        if ($log_errors) {
            $this->log($message, $level);
        }
    }
    
    function log($message, $level)
    {
        $log = array('eol' => "\n",
                     'lineFormat' => '%1$s %2$s [%3$s] %4$s',
                     'timeFormat' => '%b %d %H:%M:%S'
                     );

        $msg = sprintf($log['lineFormat'] . $log['eol'], 
                       strftime($log['timeFormat'], time()),
                       $_SERVER['REMOTE_ADDR'],
                       $level,
                       $message
                       );

        error_log($msg, 3, 'htmlcss.log');
    }

    function display($message, $level, $backtrace)
    {
        $backtrace = array_pop($backtrace);

        if ($backtrace) {
            $file = $backtrace['file'];
            $line = $backtrace['line'];

            if (isset($backtrace['class'])) {
                $func  = $backtrace['class'];
                $func .= $backtrace['type'];
                $func .= $backtrace['function'];
            } else {
                $func = $backtrace['function'];
            }
        }           

        $lineFormat = $this->_display['lineFormat'] . $this->_display['eol'];
        $contextFormat = $this->_display['contextFormat'];
        $contextExec = sprintf($contextFormat, $file, $line, $func);

        printf($lineFormat, ucfirst($level), $message, $contextExec);
    }
}

$myErrorHandler = new myErrorHandler();

PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, array(&$myErrorHandler, 'errorCallback'));

ini_set('display_errors', 1);  
ini_set('log_errors', 1);

$includes = get_included_files();
$display_errors = ini_get('display_errors');
$log_errors = ini_get('log_errors');

echo '<pre>';
print_r($includes);
printf("display_errors = %s\n", $display_errors);
printf("log_errors = %s\n", $log_errors);
echo '<hr />';
echo '</pre>';


/**
 * avoid script to die on HTML_CSS API exception 
 * @see HTML_CSS::setXhtmlCompliance()
 */
$prefs = array('pushCallback' => array(&$myErrorHandler, '_handleError'));

$attrs = array();
$css = new HTML_CSS($attrs, $prefs);

$group1 = $css->createGroup('body, html', 'grp1');
$group2 = $css->createGroup('p, html', 'grp1');

echo '<hr />';

$options = array('lineFormat' => '<b>%1$s :</b> %2$s <br />%3$s',
                 'contextFormat' => '<b>Function :</b> %3$s <br/><b>File :</b> %1$s <br /><b>Line :</b> %2$s <br/>'
                 );

$myErrorHandler = new myErrorHandler($options);

PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, array(&$myErrorHandler, 'errorCallback'));

$css->setXhtmlCompliance('true');  // generate an API error

print '<hr/>';
print "still alive";

?>