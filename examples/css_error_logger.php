<?php
/**
 * Customize error renderer with default PEAR_Error object
 * and PEAR::Log (db handler, mysql driver).
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   HTML
 * @package    HTML_CSS
 * @subpackage Examples
 * @author     Klaus Guenther <klaus@capitalfocus.org>
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @copyright  2005-2006 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTML_CSS
 * @since      File available since Release 1.0.0RC1
 */

require_once 'HTML/CSS.php';
require_once 'PEAR.php';
require_once 'Log.php';

function myErrorCallback($css_error)
{
    $display_errors = ini_get('display_errors');
    $log_errors = ini_get('log_errors');

    if ($display_errors) {
        printf('<b>HTML_CSS error :</b> %s<br/>', $css_error->getMessage());
    }

    if ($log_errors) {
        $userinfo = $css_error->getUserInfo();

        $lineFormat = '%1$s %2$s';
        $contextFormat = '(Function="%3$s" File="%1$s" Line="%2$s")';

        $options =& $userinfo['log']['sql'];
        $db_table =& $options['name'];
        $ident =& $options['ident'];
        $conf =& $options['conf'];

        if (isset($conf['lineFormat'])) {
            $lineFormat = $conf['lineFormat'];
        }
        if (isset($conf['contextFormat'])) {
            $contextFormat = $conf['contextFormat'];
        }

        $logger = &Log::singleton('sql', $db_table, $ident, $conf);

        $msg = $css_error->getMessage();
        $ctx = $css_error->sprintContextExec($contextFormat);
        $message = sprintf($lineFormat, $msg, $ctx);

        switch ($userinfo['level']) {
         case 'exception':
             $logger->alert($message);
             break;
         case 'error':
             $logger->err($message);
             break;
         case 'warning':
             $logger->warning($message);
             break;
         default:
             $logger->notice($message);
        }
    }
}

function myErrorHandler()
{
    // always returns error; do not halt script on exception
    return null;
}

ini_set('display_errors',1);
ini_set('log_errors',1);


// Example A. ---------------------------------------------

PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, 'myErrorCallback');

$dbms     = 'mysql';     // your database management system
$db_user  = 'root';      // your database user account
$db_pass  = '****';      // your database user-password account
$db_name  = 'test';      // your database name
$db_table = 'log_table'; // your database log table

/**
 * CREATE TABLE log_table (
 *  id          INT NOT NULL,
 *  logtime     TIMESTAMP NOT NULL,
 *  ident       CHAR(16) NOT NULL,
 *  priority    INT NOT NULL,
 *  message     VARCHAR(255),
 *  PRIMARY KEY (id)
 * );
 */

$options = array(
    'dsn' => "$dbms://$db_user:$db_pass@/$db_name",
    'contextFormat' => '[File="%1$s" Line="%2$s"]'
);
$sql_handler = array('name' => $db_table,
                     'ident' => 'HTML_CSS',
                     'conf' => $options
                     );
$logConfig = array('sql' => $sql_handler);

$prefs = array(
    'push_callback' => 'myErrorHandler',
    'handler' => array('log' => $logConfig)
);
$attribs = array();

$css1 = new HTML_CSS($attribs, $prefs);

// A1. Error
$group1 = $css1->createGroup('body, html', 'grp1');
$group2 = $css1->createGroup('p, html', 'grp1');

// A2. Error
$css1->getStyle('h1', 'class');

// A3. Exception
$css1->setXhtmlCompliance('true');

$msg  = "<br/><hr/>";
$msg .= "Previous errors has been recorded in database '$db_name', table '$db_table'";
echo "$msg <br/><br/>";

print 'still alive !';

?>