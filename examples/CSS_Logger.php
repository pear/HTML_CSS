<?php

require_once 'HTML/CSS.php';

$attrs = array();

//$logger['display_errors'] = 'off';
//$logger['log_errors'] = 'off';
$logger['handler']['file'] = array(
    'name' => 'htmlcss.log',
    'ident' => $_SERVER['REMOTE_ADDR']
);

$css = new HTML_CSS($attrs, $logger);

$group1 = $css->createGroup('body, html', 'grp1');
$group2 = $css->createGroup('p, html', 'grp1');
?>
