<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997 - 2005 The PHP Group                              |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author:  Klaus Guenther <klaus@capitalfocus.org>                     |
// |          Laurent Laville <pear@laurent-laville.org>                  |
// +----------------------------------------------------------------------+
//
// $Id$

/**
 * Base class for CSS definitions
 *
 * This class handles the details for creating properly constructed CSS declarations.
 *
 * Example for direct output of stylesheet:
 * <code>
 * require_once 'HTML/CSS.php';
 * 
 * $css = new HTML_CSS();
 * 
 * // define styles
 * $css->setStyle('body', 'background-color', '#0c0c0c');
 * $css->setStyle('body', 'color', '#ffffff');
 * $css->setStyle('h1', 'text-align', 'center');
 * $css->setStyle('h1', 'font', '16pt helvetica, arial, sans-serif');
 * $css->setStyle('p', 'font', '12pt helvetica, arial, sans-serif');
 *
 * // output the stylesheet directly to browser
 * $css->display();
 * </code>
 *
 * Example of group usage:
 * <code>
 * require_once 'HTML/CSS.php';
 *
 * $css = new HTML_CSS();
 *
 * // create new group
 * $group1 = $css->createGroup('body, html');
 * $group2 = $css->createGroup('p, div');
 *
 * // define styles
 * $css->setGroupStyle($group1, 'background-color', '#0c0c0c');
 * $css->setGroupStyle($group1, 'color', '#ffffff');
 * $css->setGroupStyle($group2, 'text-align', 'left');
 * $css->setGroupStyle($group2, 'background-color', '#ffffff');
 * $css->setGroupStyle($group2, 'color', '#0c0c0c');
 * $css->setStyle('h1', 'text-align', 'center');
 * $css->setStyle('h1', 'font', '16pt helvetica, arial, sans-serif');
 * $css->setStyle('p', 'font', '12pt helvetica, arial, sans-serif');
 *
 * // output the stylesheet directly to browser
 * $css->display();
 * </code>
 *
 * Example in combination with HTML_Page:
 * <code>
 * require_once 'HTML/Page.php';
 * require_once 'HTML/CSS.php';
 * 
 * $css = new HTML_CSS();
 * $css->setStyle('body', 'background-color', '#0c0c0c');
 * $css->setStyle('body', 'color', '#ffffff');
 * $css->setStyle('h1', 'text-align', 'center');
 * $css->setStyle('h1', 'font', '16pt helvetica, arial, sans-serif');
 * $css->setStyle('p', 'font', '12pt helvetica, arial, sans-serif');
 *
 * $p = new HTML_Page();
 *
 * $p->setTitle("My page");
 * // it can be added as an object
 * $p->addStyleDeclaration($css, 'text/css');
 * $p->setMetaData("author", "My Name");
 * $p->addBodyContent("<h1>headline</h1>");
 * $p->addBodyContent("<p>some text</p>");
 * $p->addBodyContent("<p>some more text</p>");
 * $p->addBodyContent("<p>yet even more text</p>");
 * $p->display();
 * </code>
 * 
 * Example for generating inline code:
 * <code>
 * require_once 'HTML/CSS.php';
 * 
 * $css = new HTML_CSS();
 * 
 * $css->setStyle('body', 'background-color', '#0c0c0c');
 * $css->setStyle('body', 'color', '#ffffff');
 * $css->setStyle('h1', 'text-align', 'center');
 * $css->setStyle('h1', 'font', '16pt helvetica, arial, sans-serif');
 * $css->setStyle('p', 'font', '12pt helvetica, arial, sans-serif');
 * $css->setSameStyle('body', 'p');
 * 
 * echo '<body style="' . $css->toInline('body') . '">';
 * // will output:
 * // <body style="font:12pt helvetica, arial, sans-serif;background-color:#0c0c0c;color:#ffffff;">
 * </code>
 *
 * @author     Klaus Guenther <klaus@capitalfocus.org>
 * @package    HTML_CSS
 * @version    @package_version@
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 */

require_once 'HTML/Common.php';

/**#@+
 * Basic error codes
 *
 * @var        integer
 * @since      0.3.3
 */
define ('HTML_CSS_ERROR_UNKNOWN',                 -1);
define ('HTML_CSS_ERROR_INVALID_INPUT',         -100);
define ('HTML_CSS_ERROR_INVALID_GROUP',         -101);
define ('HTML_CSS_ERROR_NO_GROUP',              -102);
define ('HTML_CSS_ERROR_NO_ELEMENT',            -103);
define ('HTML_CSS_ERROR_NO_ELEMENT_PROPERTY',   -104);
define ('HTML_CSS_ERROR_NO_FILE',               -105);
define ('HTML_CSS_ERROR_WRITE_FILE',            -106);
/**#@-*/

/**
 * Base class for CSS definitions
 * 
 * This class handles the details for creating properly constructed CSS declarations.
 * 
 * @author     Klaus Guenther <klaus@capitalfocus.org>
 * @package    HTML_CSS
 * @version    @package_version@
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 */

class HTML_CSS extends HTML_Common {
    
    /**
     * Contains the CSS definitions.
     *
     * @var        array
     * @since      0.2.0
     * @access     private
     */
    var $_css = array();
    
    /**
     * Contains "alibis" (other elements that share a definition) of an element defined in CSS
     *
     * @var        array
     * @since      0.2.0
     * @access     private
     */
    var $_alibis = array();
    
    /**
     * Controls caching of the page
     *
     * @var        bool
     * @since      0.2.0
     * @access     private
     * @see        setCache()
     */
    var $_cache = true;
    
    /**
     * Contains the character encoding string
     *
     * @var        string
     * @since      0.2.0
     * @access     private
     * @see        setCharset()
     */
    var $_charset = 'iso-8859-1';

    /**
     * Contains grouped styles
     *
     * @var        array
     * @since      0.3.0
     * @access     private
     */
    var $_groups = array();
    
    /**
     * Number of CSS definition groups
     *
     * @var        int
     * @since      0.3.0
     * @access     private
     */
    var $_groupCount = 0;

    /**
     * Defines whether to output all properties on one line
     *
     * @var        bool
     * @since      0.3.3
     * @access     private
     * @see        setSingleLineOutput()
     */
    var $_singleLine = false;

    /**
     * Defines whether element selectors should be automatically lowercased.
     * Determines how parseSelectors treats the data.
     *
     * @var        bool
     * @since      0.3.2
     * @access     private
     * @see        setXhtmlCompliance()
     */
    var $_xhtmlCompliant = true;

    /**
     * Error message callback.
     * This will be used to generate the error message
     * from the error code.
     * 
     * @var        false|string|array
     * @since      1.0.0
     * @access     private
     * @see        _initErrorStack()
     */
    var $_callback_message = false;

    /**
     * Error context callback.
     * This will be used to generate the error context for an error.
     * 
     * @var        false|string|array
     * @since      1.0.0
     * @access     private
     * @see        _initErrorStack()
     */
    var $_callback_context = false;

    /**
     * Error push callback.
     * The return value will be used to determine whether to allow 
     * an error to be pushed or logged.
     * 
     * @var        false|string|array
     * @since      1.0.0
     * @access     private
     * @see        _initErrorStack()
     */
    var $_callback_push = false;

    /**
     * Error handler callback.
     * This will handle any errors raised by this package.
     * 
     * @var        false|string|array
     * @since      1.0.0
     * @access     private
     * @see        _initErrorStack()
     */
    var $_callback_errorhandler = false;

    /**
     * Associative array of key-value pairs 
     * that are used to specify any handler-specific settings.
     *
     * @var        array
     * @since      1.0.0
     * @access     private
     * @see        _initErrorStack()
     */
    var $_errorhandler_options = array();


    /**
     * Class constructor
     *
     * @param      array     $attributes    (optional) Pass options to the constructor.
     *                                       Valid options are :
     *                                       xhtml (sets xhtml compliance), 
     *                                       tab (sets indent string),
     *                                       filename (name of file to be parsed),
     *                                       cache (determines whether the nocache headers are sent),
     *                                       oneline (whether to output each definition on one line)
     * @param      array     $prefs         (optional) has to configure error handler
     *                   
     * @since      0.2.0
     * @access     public
     */
    function HTML_CSS($attributes = array(), $errorPrefs = array())
    {
        $this->_initErrorStack($errorPrefs);

        if ($attributes) {
            $attributes = $this->_parseAttributes($attributes);
        }
        
        if (isset($attributes['xhtml'])) {
            $this->setXhtmlCompliance($attributes['xhtml']);
        }
        
        if (isset($attributes['tab'])) {
            $this->setTab($attributes['tab']);
        }
        
        if (isset($attributes['filename'])) {
            $this->parseFile($attributes['filename']);
        }
        
        if (isset($attributes['cache'])) {
            $this->setCache($attributes['cache']);
        }
        
        if (isset($attributes['oneline'])) {
            $this->setSingleLineOutput(true);
        }
    } // end constructor HTML_CSS
    
    /**
     * Returns the current API version
     * Since 1.0.0 a string is returned rather than a float (for previous versions).
     *
     * @return     string                   compatible with php.version_compare()
     * @since      0.2.0
     * @access     public
     */
    function apiVersion()
    {
        return @package_version@;
    } // end func apiVersion
    
    /**
     * Determines whether definitions are output single line or multiline
     *
     * @param      bool      $value
     *
     * @return     void|PEAR_Error
     * @since      0.3.3
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT
     */
    function setSingleLineOutput($value)
    {
        if (!is_bool($value)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$value',
                      'was' => gettype($value),
                      'expected' => 'boolean',
                      'paramnum' => 1));
        }
        $this->_singleLine = $value;
    } // end func setSingleLineOutput
    
    /**
     * Parses a string containing selector(s).
     * It processes it and returns an array or string containing
     * modified selectors (depends on XHTML compliance setting;
     * defaults to ensure lowercase element names)
     *
     * @param      string    $selectors     Selector string
     * @param      int       $outputMode    0 = string; 1 = array; 3 = deep array
     *
     * @return     mixed|PEAR_Error
     * @since      0.3.2
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT
     */
    function parseSelectors($selectors, $outputMode = 0)
    {
        if (!is_string($selectors)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$selectors',
                      'was' => gettype($selectors),
                      'expected' => 'string',
                      'paramnum' => 1));

        } elseif (!is_int($outputMode)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$outputMode',
                      'was' => gettype($outputMode),
                      'expected' => 'integer',
                      'paramnum' => 2));

        } elseif ($outputMode < 0 || $outputMode > 3) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'error',
                array('var' => '$outputMode',
                      'was' => $outputMode,
                      'expected' => '0 | 1 | 2 | 3',
                      'paramnum' => 2));
        }

        $selectors_array =  explode(',', $selectors);
        $i = 0;
        foreach ($selectors_array as $selector) {
            // trim to remove possible whitespace
            $selector = trim($this->collapseInternalSpaces($selector));
            if (strpos($selector, ' ')) {
                $sel_a = array();
                foreach(explode(' ', $selector) as $sub_selector) {
                    $sel_a[] = $this->parseSelectors($sub_selector, $outputMode);
                }
                if ($outputMode === 0) {
                        $array[$i] = implode(' ', $sel_a);
                } else {
                    $sel_a2 = array();
                    foreach ($sel_a as $sel_a_temp) {
                        $sel_a2 = array_merge($sel_a2, $sel_a_temp);
                    }
                    if ($outputMode == 2) {
                        $array[$i]['inheritance'] = $sel_a2;
                    } else {
                        $array[$i] = implode(' ', $sel_a2);
                    }
                }
                $i++;
            } else {
                // initialize variables
                $element = '';
                $id      = '';
                $class   = '';
                $pseudo  = '';
                
                if (strpos($selector, ':') !== false) {
                    $pseudo   = strstr($selector, ':');
                    $selector = substr($selector, 0 , strpos($selector, ':'));
                }
                if (strpos($selector, '.') !== false){
                    $class    = strstr($selector, '.');
                    $selector = substr($selector, 0 , strpos($selector, '.'));
                }
                if (strpos($selector, '#') !== false) {
                    $id       = strstr($selector, '#');
                    $selector = substr($selector, 0 , strpos($selector, '#'));
                }
                if ($selector != '') {
                    $element  = $selector;
                }
                if ($this->_xhtmlCompliant){
                    $element  = strtolower($element);
                    $pseudo   = strtolower($pseudo);
                }
                if ($outputMode == 2) {
                    $array[$i]['element'] = $element;
                    $array[$i]['id']      = $id;
                    $array[$i]['class']   = $class;
                    $array[$i]['pseudo']  = $pseudo;
                } else {
                    $array[$i] = $element.$id.$class.$pseudo;
                }
                $i++;
            }
        }
        if ($outputMode == 0) {
            $output = implode(', ', $array);
            return $output;
        } else {
            return $array;
        }
    } // end func parseSelectors
    
    /**
     * Strips excess spaces in string.
     *
     * @return     string
     * @since      0.3.2
     * @access     public
     */
    function collapseInternalSpaces($subject){
        $string = preg_replace('/\s+/', ' ', $subject);
        return $string;
    } // end func collapseInternalSpaces
    
    /**
     * Sets XHTML compliance
     *
     * @param      bool      $value         Boolean value
     *
     * @return     void|PEAR_Error
     * @since      0.3.2
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT
     */
    function setXhtmlCompliance($value)
    {
        if (!is_bool($value)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$value',
                      'was' => gettype($value),
                      'expected' => 'boolean',
                      'paramnum' => 1));
        }
        $this->_xhtmlCompliant = $value;
    } // end func setXhtmlCompliance

    /**
     * Creates a new CSS definition group. Returns an integer identifying the group.
     *
     * @param      string    $selectors     Selector(s) to be defined, comma delimited.
     * @param      mixed     $identifier    Group identifier. If not passed, will return an automatically assigned integer.
     *
     * @return     mixed|PEAR_Error
     * @since      0.3.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT, HTML_CSS_ERROR_INVALID_GROUP
     * @see        unsetGroup()
     */
    function createGroup($selectors, $identifier = null)
    {
        if (!is_string($selectors)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$selectors',
                      'was' => gettype($selectors),
                      'expected' => 'string',
                      'paramnum' => 1));
        }

        if ($identifier === null) {
            $this->_groupCount++;
            $group = $this->_groupCount;
        } else {
            if (isset($this->_groups[$identifier])){
                return $this->raiseError(HTML_CSS_ERROR_INVALID_GROUP, 'error',
                    array('identifier' => $identifier));
            }
            $group = $identifier;
        }
        $selectors = $this->parseSelectors($selectors, 1);
        foreach ($selectors as $selector) {
            $this->_groups[$group]['selectors'][] = $selector;
            $this->_alibis[$selector][$group] = true;
        }
        if ($identifier == null){
            return $group;
        }
    } // end func createGroup

    /**
     * Sets or adds a CSS definition for a CSS definition group
     *
     * @param      int       $group         CSS definition group identifier
     *
     * @return     void|PEAR_Error
     * @since      0.3.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT, HTML_CSS_ERROR_NO_GROUP
     * @see        createGroup()
     */
    function unsetGroup($group)
    {
        if (!is_int($group)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$group',
                      'was' => gettype($group),
                      'expected' => 'integer',
                      'paramnum' => 1));
        }

        if ($group < 0 || $group > $this->_groupCount) {
            return $this->raiseError(HTML_CSS_ERROR_NO_GROUP, 'error',
                array('identifier' => $group));
        }
        
        foreach ($this->_groups[$group]['selectors'] as $selector) {
            unset ($this->_alibis[$selector][$group]);
            if (count($this->_alibis[$selector]) == 0) {
                unset($this->_alibis[$selector]);
            }
        }
        unset($this->_groups[$group]);
    } // end func unsetGroup

    /**
     * Sets or adds a CSS definition for a CSS definition group
     *
     * @param      int       $group         CSS definition group identifier
     * @param      string    $property      Property defined
     * @param      string    $value         Value assigned
     *
     * @return     void|PEAR_Error
     * @since      0.3.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT, HTML_CSS_ERROR_NO_GROUP
     * @see        getGroupStyle()
     */
    function setGroupStyle($group, $property, $value)
    {
        if (!is_int($group)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$group',
                      'was' => gettype($group),
                      'expected' => 'integer',
                      'paramnum' => 1));

        } elseif (!is_string($property)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$property',
                      'was' => gettype($property),
                      'expected' => 'string',
                      'paramnum' => 2));

        } elseif (!is_string($value)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$value',
                      'was' => gettype($value),
                      'expected' => 'string',
                      'paramnum' => 3));
        }

        if ($group < 0 || $group > $this->_groupCount) {
            return $this->raiseError(HTML_CSS_ERROR_NO_GROUP, 'error',
                array('identifier' => $group));
        }
        $this->_groups[$group]['properties'][$property]= $value;
    } // end func setGroupStyle

    /**
     * Returns a CSS definition for a CSS definition group
     *
     * @param      int       $group         CSS definition group identifier
     * @param      string    $property      Property defined
     *
     * @return     string|PEAR_Error
     * @since      0.3.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT, HTML_CSS_ERROR_NO_GROUP
     * @see        setGroupStyle()
     */
    function getGroupStyle($group, $property)
    {
        if (!is_int($group)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$group',
                      'was' => gettype($group),
                      'expected' => 'integer',
                      'paramnum' => 1));

        } elseif (!is_string($property)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$property',
                      'was' => gettype($property),
                      'expected' => 'string',
                      'paramnum' => 2));
        }
        
        if ($group < 0 || $group > $this->_groupCount) {
            return $this->raiseError(HTML_CSS_ERROR_NO_GROUP, 'error',
                array('identifier' => $group));
        }
        return $this->_groups[$group]['properties'][$property];
    } // end func getGroupStyle

    /**
     * Adds a selector to a CSS definition group.
     *
     * @param      int       $group         CSS definition group identifier
     * @param      string    $selectors     Selector(s) to be defined, comma delimited.
     *
     * @return     void|PEAR_Error
     * @since      0.3.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT, HTML_CSS_ERROR_NO_GROUP
     * @see        removeGroupSelector()
     */
    function addGroupSelector($group, $selectors)
    {
        if (!is_int($group)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$group',
                      'was' => gettype($group),
                      'expected' => 'integer',
                      'paramnum' => 1));

        } elseif (!is_string($selectors)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$selectors',
                      'was' => gettype($selectors),
                      'expected' => 'string',
                      'paramnum' => 2));
        }

        if ($group < 0 || $group > $this->_groupCount) {
            return $this->raiseError(HTML_CSS_ERROR_NO_GROUP, 'error',
                array('identifier' => $group));
        }
        $selectors = $this->parseSelectors($selectors, 1);
        foreach ($selectors as $selector) {
            $this->_groups[$group]['selectors'][] = $selector;
            $this->_alibis[$selector][$group] = true;
        }
    } // end func addGroupSelector

    /**
     * Removes a selector from a group.
     *
     * @param      int       $group         CSS definition group identifier
     * @param      string    $selectors     Selector(s) to be removed, comma delimited.
     *
     * @return     void|PEAR_Error
     * @since      0.3.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT, HTML_CSS_ERROR_NO_GROUP
     * @see        addGroupSelector()
     */
    function removeGroupSelector($group, $selectors)
    {
        if (!is_int($group)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$group',
                      'was' => gettype($group),
                      'expected' => 'integer',
                      'paramnum' => 1));

        } elseif (!is_string($selectors)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$selectors',
                      'was' => gettype($selectors),
                      'expected' => 'string',
                      'paramnum' => 2));
        }

        if ($group < 0 || $group > $this->_groupCount) {
            return $this->raiseError(HTML_CSS_ERROR_NO_GROUP, 'error',
                array('identifier' => $group));
        }
        $selectors =  $this->parseSelectors($selectors, 1);
        foreach ($selectors as $selector) {
            foreach ($this->_groups[$group]['selectors'] as $key => $value) {
                if ($value == $selector) {
                    unset($this->_groups[$group]['selectors'][$key]);
                }
            }
            unset($this->_alibis[$selector][$group]);
        }
    } // end func removeGroupSelector
   
    /**
     * Sets or adds a CSS definition
     *
     * @param      string    $element       Element (or class) to be defined
     * @param      string    $property      Property defined
     * @param      string    $value         Value assigned
     *
     * @return     void|PEAR_Error
     * @since      0.2.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT
     * @see        getStyle()
     */
    function setStyle ($element, $property, $value)
    {
        if (!is_string($element)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$element',
                      'was' => gettype($element),
                      'expected' => 'string',
                      'paramnum' => 1));

        } elseif (!is_string($property)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$property',
                      'was' => gettype($property),
                      'expected' => 'string',
                      'paramnum' => 2));

        } elseif (!is_string($value)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$value',
                      'was' => gettype($value),
                      'expected' => 'string',
                      'paramnum' => 3));
        }

        $element = $this->parseSelectors($element);
        $this->_css[$element][$property]= $value;
    } // end func setStyle
    
    /**
     * Retrieves the value of a CSS property
     *
     * @param      string    $element       Element (or class) to be defined
     * @param      string    $property      Property defined
     *
     * @return     string|PEAR_Error
     * @since      0.3.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT, 
     *             HTML_CSS_ERROR_NO_ELEMENT, HTML_CSS_ERROR_NO_ELEMENT_PROPERTY
     * @see        setStyle()
     */
    function getStyle($element, $property)
    {
        if (!is_string($element)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$element',
                      'was' => gettype($element),
                      'expected' => 'string',
                      'paramnum' => 1));

        } elseif (!is_string($property)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$property',
                      'was' => gettype($property),
                      'expected' => 'string',
                      'paramnum' => 2));
        }

        $property_value = '';
        $element = $this->parseSelectors($element);
        if (!isset($this->_css[$element]) && !isset($this->_alibis[$element])) {
            return $this->raiseError(HTML_CSS_ERROR_NO_ELEMENT, 'error',
                array('identifier' => $element));
        }
        
        if (isset($this->_alibis[$element])) {
            foreach ($this->_alibis[$element]as $group_id => $empty) {
                if (isset($this->_groups[$group_id]['properties'][$property])) {
                    $property_value = $this->_groups[$group_id]['properties'][$property];
                }
            }
        }
        if (isset($this->_css[$element])) {
            if (isset($this->_css[$element][$property])) {
                $property_value = $this->_css[$element][$property];
            }
        }
        if ($property_value == '') {
            return $this->raiseError(HTML_CSS_ERROR_NO_ELEMENT_PROPERTY, 'error',
                array('identifier' => $element, 
                      'property'   => $property));
        }
        return $property_value;
    } // end func getStyle
    
    /**
     * Sets or changes the properties of new selectors to the values of an existing selector
     *
     * @param      string    $old           Selector that is already defined
     * @param      string    $new           New selector(s) that should share the same 
     *                                      definitions, separated by commas
     * @return     void|PEAR_Error
     * @since      0.2.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT, HTML_CSS_ERROR_NO_ELEMENT
     * @see        parseSelectors()
     */
    function setSameStyle ($new, $old)
    {
        if (!is_string($new)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$new',
                      'was' => gettype($new),
                      'expected' => 'string',
                      'paramnum' => 1));

        } elseif (!is_string($old)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$old',
                      'was' => gettype($old),
                      'expected' => 'string',
                      'paramnum' => 2));
        }

        $old = $this->parseSelectors($old);
        if (!isset($this->_css[$old])) {
            return $this->raiseError(HTML_CSS_ERROR_NO_ELEMENT, 'error',
                array('identifier' => $old));
        }
        $others = $this->parseSelectors($new, 1);
        foreach ($others as $other) {
            $other = trim($other);
            foreach($this->_css[$old] as $property => $value) {
                $this->_css[$other][$property] = $value;
            }
        }
    } // end func setSameStyle
    
    /**
     * Defines if the document should be cached by the browser. Defaults to false.
     *
     * @param      bool      $cache         (optional)
     *
     * @return     void|PEAR_Error
     * @since      0.2.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT
     */
    function setCache($cache = true)
    {
        if (!is_bool($cache)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$cache',
                      'was' => gettype($cache),
                      'expected' => 'boolean',
                      'paramnum' => 1));
        }

        $this->_cache = $cache;
    } // end func setCache
    
    /**
     * Defines the charset for the file. defaults to ISO-8859-1 because of CSS1
     * compatability issue for older browsers.
     *
     * @param      string    $type          Charset encoding; defaults to ISO-8859-1.
     *
     * @return     void|PEAR_Error
     * @since      0.2.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT
     * @see        getCharset()
     */
    function setCharset($type = 'iso-8859-1')
    {
        if (!is_string($type)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$type',
                      'was' => gettype($type),
                      'expected' => 'string',
                      'paramnum' => 1));
        }

        $this->_charset = $type;
    } // end func setCharset
    
    /**
     * Returns the charset encoding string
     *
     * @return     string
     * @since      0.2.0
     * @access     public
     * @see        setCharset()
     */
    function getCharset()
    {
        return $this->_charset;
    } // end func getCharset
    
    /**
     * Parse a textstring that contains css information
     *
     * @param      string    $str           text string to parse
     *
     * @return     void|PEAR_Error
     * @since      0.3.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT
     * @see        parseSelectors(), createGroup(), setGroupStyle(), setStyle()
     */
    function parseString($str) 
    {
        if (!is_string($str)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$str',
                      'was' => gettype($str),
                      'expected' => 'string',
                      'paramnum' => 1));
        }

        // Remove comments
        $str = preg_replace("/\/\*(.*)?\*\//Usi", '', $str);
        
        // Parse each element of csscode
        $parts = explode("}",$str);
        foreach($parts as $part) {
            $part = trim($part);
            if (strlen($part) > 0) {
                
                // Parse each group of element in csscode
                list($keystr,$codestr) = explode("{",$part);
                $key_a = $this->parseSelectors($keystr, 1);
                $keystr = implode(', ', $key_a);
                // Check if there are any groups.
                if (strpos($keystr, ',')) {
                    $group = $this->createGroup($keystr);
                    
                    // Parse each property of an element
                    $codes = explode(";",trim($codestr));
                    foreach ($codes as $code) {
                        if (strlen(trim($code)) > 0) {
                            // find the property and the value
                            $property = trim(substr($code, 0 , strpos($code, ':', 0)));
                            $value    = trim(substr($code, strpos($code, ':', 0) + 1));
                            
                            // check to see if this group comes after an individual
                            // selector setting. If this is the case, the group setting
                            // overrides the individual setting
                            foreach ($key_a as $key) {
                                if (isset($this->_css[$key][$property])) {
                                    unset($this->_css[$key][$property]);
                                }
                            }
                            $this->setGroupStyle($group, $property, $value);
                        }
                    }
                } else {

                    // let's get on with regular definitions
                    $key = trim($keystr);
                    if (strlen($key) > 0) {
                        // Parse each property of an element
                        $codes = explode(";",trim($codestr));
                        foreach ($codes as $code) {
                            if (strlen(trim($code)) > 0) {
                                $property = trim(substr($code, 0 , strpos($code, ':')));
                                $value    = substr($code, strpos($code, ':') + 1);
                                $this->setStyle($key, $property, trim($value));
                            }
                        }
                    }
                }
            }
        }
    } // end func parseString
    
    /**
     * Parse a file that contains CSS information
     *
     * @param      string    $filename      file to parse
     *
     * @return     void|PEAR_Error
     * @since      0.3.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT, HTML_CSS_ERROR_NO_FILE
     * @see        parseString()
     */
    function parseFile($filename) 
    { 
        if (!is_string($filename)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$filename',
                      'was' => gettype($filename),
                      'expected' => 'string',
                      'paramnum' => 1));

        } elseif (!file_exists($filename)) {
            return $this->raiseError(HTML_CSS_ERROR_NO_FILE, 'error',
                    array('identifier' => $filename));
        }

        if (function_exists('file_get_contents')){
            $this->parseString(file_get_contents($filename));
        } else {
            $file = fopen("$filename", "rb");
            $this->parseString(fread($file, filesize($filename)));
            fclose($file);
        }
    } // func parseFile
    
    /**
     * Generates and returns the array of CSS properties
     *
     * @return     array
     * @since      0.2.0
     * @access     public
     */
    function toArray()
    {
        // initialize $alibis
        $alibis = array();

        $newCssArray = array();

        // If there are groups, iterate through the array and generate the CSS
        if (count($this->_groups) > 0) {
            foreach ($this->_groups as $group) {

                // Start group definition
                foreach ($group['selectors'] as $selector){
                    $selector = trim($selector);
                    $alibis[] = $selector;
                }
                $alibis = implode(', ',$alibis);

                foreach ($group['properties'] as $key => $value) {
                    $newCssArray[$alibis][$key] = $value;
                }
                unset($alibis);

            }
        }

        // Iterate through the array and process each element
        foreach ($this->_css as $element => $property) {

            foreach ($property as $key => $value) {
                $newCssArray[$element][$key] = $value;
            }

        }
        return $newCssArray;
    } // end func toArray
    
    /**
     * Generates and returns the CSS properties of an element or class as a string for inline use.
     *
     * @param      string    $element       Element or class for which inline CSS should be generated
     *
     * @return     string|PEAR_Error
     * @since      0.2.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT
     */
    function toInline($element)
    {
        if (!is_string($element)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$element',
                      'was' => gettype($element),
                      'expected' => 'string',
                      'paramnum' => 1));
        }
        
        $strCss = '';
        $newCssArray = '';
        
        // Iterate through the array of properties for the supplied element
        // This allows for grouped elements definitions to work
        if (isset($this->_alibis[$element])) {
            foreach ($this->_alibis[$element] as $group => $status) {
                foreach ($this->_groups[$group]['properties'] as $key => $value) {
                    $newCssArray[$key] = $value;
                }
            }
        }
        
        // The reason this comes second is because if something is defined twice,
        // the value specifically assigned to this element should override
        // values inherited from other element definitions
        if ($this->_css[$element]) {
            foreach ($this->_css[$element] as $key => $value) {
                if ($key != 'other-elements') {
                    $newCssArray[$key] = $value;
                }
            }
        }
        
        foreach ($newCssArray as $key => $value) {
            $strCss .= $key . ':' . $value . ";";
        }
        
        // Let's roll!
        return $strCss;
    } // end func toInline
    
    /**
     * Generates CSS and stores it in a file.
     *
     * @return     void|PEAR_Error
     * @since      0.3.0
     * @access     public
     * @throws     HTML_CSS_ERROR_INVALID_INPUT, HTML_CSS_ERROR_WRITE_FILE
     * @see        toString()
     */
    function toFile($filename)
    {
        if (!is_string($filename)) {
            return $this->raiseError(HTML_CSS_ERROR_INVALID_INPUT, 'exception',
                array('var' => '$filename',
                      'was' => gettype($filename),
                      'expected' => 'string',
                      'paramnum' => 1));
        }

        if (function_exists('file_put_contents')){
            file_put_contents($filename, $this->toString());
        } else {
            $file = fopen($filename,'wb');
            fwrite($file, $this->toString());
            fclose($file);
        }
        if (!file_exists($filename)){
            return $this->raiseError(HTML_CSS_ERROR_WRITE_FILE, 'error',
                    array('filename' => $filename));
        }
    } // end func toFile
    
    /**
     * Generates and returns the complete CSS as a string.
     *
     * @return     string
     * @since      0.2.0
     * @access     public
     */
    function toString()
    {
        // get line endings
        $lnEnd = $this->_getLineEnd();
        $tabs  = $this->_getTabs();
        $tab   = $this->_getTab();
        
        // initialize $alibis
        $alibis = array();
        
        $strCss = '';
        
        // Allow a CSS comment
        if ($this->_comment) {
            $strCss = $tabs . '/* ' . $this->getComment() . ' */' . $lnEnd;
        }
        
        // If there are groups, iterate through the array and generate the CSS
        if (count($this->_groups) > 0) {
            foreach ($this->_groups as $group) {
                
                // Start group definition
                foreach ($group['selectors'] as $selector){
                    $selector = trim($selector);
                    $alibis[] = $selector;
                }
                $alibis = implode(', ',$alibis);
                $definition = $alibis . ' {' . $lnEnd;
                unset($alibis);
                
                // Add CSS definitions
                foreach ($group['properties'] as $key => $value) {
                    $definition .= $tabs . $tab . $key . ': ' . $value . ';' . $lnEnd;
                }
                $definition .= $tabs . '}';
                
                if ($this->_singleLine) {
                    $definition = $this->collapseInternalSpaces($definition);
                }
                $strCss .= $lnEnd . $tabs . $definition . $lnEnd;
            }
        }
        
        // Iterate through the array and process each element
        foreach ($this->_css as $element => $property) {

            //start CSS element definition
            $definition = $element . ' {' . $lnEnd;
            
            foreach ($property as $key => $value) {
                $definition .= $tabs . $tab . $key . ': ' . $value . ';' . $lnEnd;
            }
            
            // end CSS element definition
            $definition .= $tabs . '}';
            
            // if this is to be on a single line, collapse
            if ($this->_singleLine) {
                $definition = $this->collapseInternalSpaces($definition);
            }
            
            // add to strCss
            $strCss .= $lnEnd . $tabs . $definition . $lnEnd;
        }
        
        if ($this->_singleLine) {
            $strCss = str_replace($lnEnd.$lnEnd, $lnEnd, $strCss);
        }
        
        // Let's roll!
        $strCss = preg_replace('/^(\n|\r\n|\r)/', '', $strCss);
        return $strCss;
    } // end func toString
    
    /**
     * Outputs the stylesheet to the browser.
     *
     * @return     void
     * @since      0.2.0
     * @access     public
     * @see        toString()
     */
    function display()
    {
        if(! $this->_cache) {
            header("Expires: Tue, 1 Jan 1980 12:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-cache");
            header("Pragma: no-cache");
        }
        
        // set character encoding
        header("Content-Type: text/css; charset=" . $this->_charset);
        
        $strCss = $this->toString();
        print $strCss;
    } // end func display

    /**
     * Initialize Error engine preferences
     *
     * @param      array     $prefs         hash of params to customize error generation
     * @return     void
     * @since      0.3.3
     * @access     private
     */
    function _initErrorStack($prefs = array())
    {
        // error message mapping callback
        if (isset($prefs['message_callback']) && is_callable($prefs['message_callback'])) {
            $this->_callback_message = $prefs['message_callback'];
        } else {
            $this->_callback_message = array('HTML_CSS_Error', '_msgCallback');
        }

        // error context mapping callback
        if (isset($prefs['context_callback']) && is_callable($prefs['context_callback'])) {
            $this->_callback_context = $prefs['context_callback'];
        } else {
            $this->_callback_context = array('HTML_CSS_Error', 'getBacktrace');
        }

        // determine whether to allow an error to be pushed or logged
        if (isset($prefs['push_callback']) && is_callable($prefs['push_callback'])) {
            $this->_callback_push = $prefs['push_callback'];
        } else {
            $this->_callback_push = array('HTML_CSS_Error', '_handleError');
        }

        // default error handler will use PEAR_Error
        if (isset($prefs['error_handler']) && is_callable($prefs['error_handler'])) {
            $this->_callback_errorhandler = $prefs['error_handler'];
        } else {
            $this->_callback_errorhandler = array(&$this, '_errorHandler');
        }

        // any handler-specific settings
        if (isset($prefs['handler'])) {
            $this->_errorhandler_options = $prefs['handler'];
        }
    }

    /**
     * Standard error handler that will use PEAR_Error object
     *
     * To improve performances, the PEAR.php file is included dynamically.
     * The file is so included only when an error is triggered. So, in most
     * cases, the file isn't included and perfs are much better.
     *
     * @param      integer   $code       Error code.
     * @param      string    $level      The error level of the message. 
     * @param      array     $params     Associative array of error parameters
     *
     * @return     PEAR_Error
     * @since      1.0.0
     * @access     private
     */
    function _errorHandler($code, $level, $params)
    {
        include_once 'HTML/CSS/Error.php';

        $mode = call_user_func($this->_callback_push, $code, $level);

        $message = call_user_func($this->_callback_message, $code, $params);
        $userinfo['level'] = $level;

        if (isset($this->_errorhandler_options['display'])) {
            $userinfo['display'] = $this->_errorhandler_options['display'];
        } else {
            $userinfo['display'] = array();
        }
        if (isset($this->_errorhandler_options['log'])) {
            $userinfo['log'] = $this->_errorhandler_options['log'];
        } else {
            $userinfo['log'] = array();
        }
        
        return PEAR::raiseError($message, $code, $mode, null, $userinfo, 'HTML_CSS_Error');
    }

    /**
     * A basic wrapper around the default PEAR_Error object
     *
     * @return     object                PEAR_Error when default error handler is used
     * @since      0.3.3
     * @access     public
     * @see        _errorHandler()
     */
    function raiseError()
    {
        $args = func_get_args();
        return call_user_func_array($this->_callback_errorhandler, $args);
    }
}
?>