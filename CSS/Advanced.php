<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997 - 2003 The PHP Group                              |
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
// +----------------------------------------------------------------------+
//
// $Id$

require_once 'PEAR.php';
require_once 'HTML/Common.php';
require_once 'HTML/CSS.php';

/**
 * Class for advanced CSS definitions
 *
 * This class handles the details for creating properly constructed CSS declarations.
 *
 *
 * @author     Klaus Guenther <klaus@capitalfocus.org>
 * @package    HTML_CSS
 * @version    0.3.0
 * @access     public
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 */
class HTML_CSS_Advanced extends HTML_CSS {
    
    /**
     * Class constructor
     *
     * @access  public
     */
    function HTML_CSS_Advanced()
    {
        $commonVersion = 1.7;
        if (HTML_Common::apiVersion() < $commonVersion) {
            return PEAR::raiseError("HTML_CSS version " . $this->apiVersion() . " requires " .
            "HTML_Common version 1.2 or greater.", 0, PEAR_ERROR_TRIGGER);
        }
    }
    
    /**
     * Returns the current API version
     *
     * @access   public
     * @returns  double
     */
    function apiVersion()
    {
        return 0.1;
    } // end func apiVersion
    
    /**
     * Sets or adds a CSS definition for a CSS definition group
     *
     * @param    int     $group     CSS definition group identifier
     * @param    string  $property  Property defined
     * @param    string  $value     Value assigned
     * @access   public
     */
    function setGroupStyle($group, $property, $value)
    {
        $grp = $this->_checkGroup($group, 'setGroupStyle');
        if (PEAR::isError($grp)) {
            return $grp;
        }
        $this->_groups[$group]['properties'][][$property]= $value;
    } // end func setGroupStyle

    /**
     * Check if a group is valid (exists)
     *
     * @param    int     $group       CSS definition group identifier
     * @param    sring   $method      comes from
     * @returns  bool                 TRUE if group exists, PEAR error otherwise
     * @access   private
     */
    function _checkGroup($group, $method)
    {
        if ($group < 0 || $group > $this->_groupCount) {
            return PEAR::raiseError("HTML_CSS::$method() error: group $group does not exist.",
                                        0, PEAR_ERROR_TRIGGER);
        }
        return true;
    }
    
    /**
     * Adds a CSS definition
     *
     * @param    string  $element   Element (or class) to be defined
     * @param    string  $property  Property defined
     * @param    string  $value     Value assigned
     * @access   public
     */
    function setStyle ($element, $property, $value)
    {
        $this->_css[$element][][$property]= $value;
    } // end func setStyle
    
    /**
     * Sets or adds a CSS definition
     *
     * @param    string  $old    Selector that is already defined
     * @param    string  $new    New selector(s) that should share the same definitions, separated by commas
     * @access   public
     */
    function setSameStyle ($new, $old)
    {
        $elm = $this->_checkElement($old, 'setSameStyle');
        if (PEAR::isError($elm)) {
            return $elm;
        }
        $others =  explode(',', $new);
        foreach ($others as $other) {
            $other = trim($other);
            $this->_css[$other] = $this->_css[$old];
        }
    } // end func setSameStyle
    
    /**
     * Check if an element is valid (exists)
     *
     * @param    string  $element     Element already defined
     * @param    sring   $method      comes from
     * @returns  bool                 TRUE if group exists, PEAR error otherwise
     * @access   private
     */
    function _checkElement($element, $method)
    {
        if (!isset($this->_css[$element])) {
            return PEAR::raiseError("HTML_CSS::$method() error: element $element does not exist.",
                                        0, PEAR_ERROR_TRIGGER);
        }
        return true;
    }

    /**
     * Generates and returns the array of CSS properties
     *
     * @return  array
     * @access  public
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

                foreach ($group['properties'] as $num => $property) {
                    foreach ($property as $key => $value) {
                        $newCssArray[$alibis][][$key] = $value;
                    }
                }
                unset($alibis);

            }
        }

        // Iterate through the array and process each element
        foreach ($this->_css as $element => $property) {
            foreach ($property as $num => $actual) {
                foreach ($actual as $key => $value) {
                    $newCssArray[$element][][$key] = $value;
                }
            }

        }
        return $newCssArray;
    } // end func toArray
    
    /**
     * Generates and returns the CSS properties of an element or class as a string for inline use.
     *
     * @param   string  $element    Element or class for which inline CSS should be generated
     * @return  string
     * @access  public
     */
    function toInline($element)
    {
        $strCss = '';
        $newCssArray = '';
        
        // Iterate through the array of properties for the supplied element
        // This allows for grouped elements definitions to work
        if (isset($this->_alibis[$element])) {
            foreach ($this->_alibis[$element] as $group => $status) {
                foreach ($this->_groups[$group]['properties'] as $num => $property) {
                    foreach ($property as $key => $value) {
                        $newCssArray[][$key] = $value;
                    }
                }
            }
        }
        
        // The reason this comes second is because if something is defined twice,
        // the value specifically assigned to this element should override
        // values inherited from other element definitions
        if ($this->_css[$element]) {
            foreach ($this->_css[$element] as $num => $property) {
                foreach ($property as $key => $value) {
                    $newCssArray[$key][] = $value;
                }
            }
        }
        
        foreach ($newCssArray as $num => $property) {
            foreach ($property as $key => $value) {
                $strCss .= $key . ':' . $value . ";";
            }
        }
        
        // Let's roll!
        return $strCss;
    } // end func toInline
    
    /**
     * Generates and returns the complete CSS as a string.
     *
     * @return string
     * @access public
     */
    function toString()
    {
        // get line endings
        $lnEnd = $this->_getLineEnd();
        $tabs = $this->_getTabs();
        $tab = $this->_getTab();
        
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
                $strCss .= $tabs . $alibis . ' {' . $lnEnd;
                unset($alibis);
                
                // Add CSS definitions
                foreach ($group['properties'] as $num => $property) {
                    foreach ($property as $key => $value){
                        $strCss .= $tabs . $tab . $key . ': ' . $value . ';' . $lnEnd;
                    }
                }
                $strCss .= $tabs . '}' . $lnEnd;
            }
        }
        
        // Iterate through the array and process each element
        foreach ($this->_css as $element => $properties) {
            
            $strCss .= $lnEnd;

            //start CSS element definition
            $strCss .= $tabs . $element . ' {' . $lnEnd;
            
            foreach ($properties as $num => $property) {
                foreach ($property as $key => $value) {
                    $strCss .= $tabs . $tab . $key . ': ' . $value . ';' . $lnEnd;
                }
            }
            
            // end CSS element definition
            $strCss .= $tabs . '}' . $lnEnd;
        }
        
        // Let's roll!
        return $strCss;
    } // end func toString
    
}
?>
