<?php // $Id$
    
    // vim: expandtab sw=4 ts=4 sts=4:
    
    if ( count( get_included_files() ) == 1 )
    {
        die( 'The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead' );
    }
    
    # ***** BEGIN LICENSE BLOCK *****
    # This file is part of EventManager.
    # Copyright (c) 2005-2007 Frederic Minne <zefredz@gmail.com>.
    # All rights reserved.
    #
    # EventManager is free software; you can redistribute it and/or modify
    # it under the terms of the GNU General Public License as published by
    # the Free Software Foundation; either version 2 of the License, or
    # (at your option) any later version.
    #
    # EventManager is distributed in the hope that it will be useful,
    # but WITHOUT ANY WARRANTY; without even the implied warranty of
    # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    # GNU General Public License for more details.
    #
    # You should have received a copy of the GNU General Public License
    # along with DotClear; if not, write to the Free Software
    # Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    #
    # ***** END LICENSE BLOCK *****
    
    /**
     * @author  Frederic Minne <zefredz@claroline.net>
     * @copyright Copyright &copy; 2006-2007, Frederic Minne
     * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
     * @version 1.0
     * @package HTML
     */

    /**
     * Sanitize HTML body content
     */
    class HTML_Sanitizer
    {
        protected $_allowedTags;
        protected $_allowJavascriptEvents;
        protected $_allowJavascriptInUrls;
        protected $_allowObjects;
        protected $_allowScript;
        protected $_allowStyle;
        protected $_additionalTags;
        
        /**
         * Constructor
         * @param void
         */
        function __construct()
        {
            $this->resetAll();
        }
        
        /**
         * (re)set all options to default value
         */
        function resetAll()
        {
            $this->_allowDOMEvents = false;
            $this->_allowJavascriptInUrls = false;
            $this->_allowStyle = false;
            $this->_allowScript = false;
            $this->_allowObjects = false;
            $this->_allowStyle = false;

            $this->_allowedTags = '<a><br><b><h1><h2><h3><h4><h5><h6>'
                . '<img><li><ol><p><strong><table><tr><td><th><u><ul><thead>'
                . '<tbody><tfoot><em><dd><dt><dl><span><div><del><add><i><hr>'
                . '<pre><br><blockquote><address><code><caption><abbr><acronym>'
                . '<cite><dfn><q><ins><sup><sub><kbd><samp><protected><tt><small><big>'
                ;
                
            $this->_additionalTags = '';
        }
        
        /**
         * Add additional tags to allowed tags
         * @param string
         */
        function addAdditionalTags( $tags )
        {
            $this->_additionalTags .= $tags;
        }

        /**
         * Allow object, embed, applet and param tags in html
         */
        function allowObjects()
        {
            $this->_allowObjects = true;
        }
        
        /**
         * Allow DOM event on DOM elements
         */
        function allowDOMEvents()
        {
            $this->_allowDOMEvents = true;
        }
        
        /**
         * Allow script tags
         */
        function allowScript()
        {
            $this->_allowScript = true;
        }
        
        /**
         * Allow the use of javascript: in urls
         */
        function allowJavascriptInUrls()
        {
            $this->_allowJavascriptInUrls = true;
        }
        
        /**
         * Allow style tags and attributes
         */
        function allowStyle()
        {
            $this->_allowStyle = true;
        }
        
        /**
         * Helper to allow all javascript related tags and attributes
         */
        function allowAllJavascript()
        {
            $this->allowDOMEvents();
            $this->allowScript();
            $this->allowJavascriptInUrls();
        }
        
        /**
         * Allow all tags and attributes
         */
        function allowAll()
        {
            $this->allowAllJavascript();
            $this->allowObjects();
            $this->allowStyle();
        }
        
        /**
         * Filter URLs to avoid HTTP response splitting attacks
         * @access  public
         * @param   string url
         * @return  string filtered url
         */
        function filterHTTPResponseSplitting( $url )
        {
            $dangerousCharactersPattern = '~(\r\n|\r|\n|%0a|%0d|%0D|%0A)~';
            return preg_replace( $dangerousCharactersPattern, '', $url );
        }
        
        /**
         * Remove potential javascript in urls
         * @access  public
         * @param   string url
         * @return  string filtered url
         */
        function removeJavascriptURL( $str )
        {
            $HTML_Sanitizer_stripJavascriptURL = 'javascript:[^"]+';

            $str = preg_replace("/$HTML_Sanitizer_stripJavascriptURL/i"
                , ''
                , $str );

            return $str;
        }
        
        /**
         * Remove potential flaws in urls
         * @access  private
         * @param   string url
         * @return  string filtered url
         */
        function sanitizeURL( $url )
        {
            if ( ! $this->_allowJavascriptInUrls )
            {
                $url = $this->removeJavascriptURL( $url );
            }
            
            $url = $this->filterHTTPResponseSplitting( $url );

            return $url;
        }
        
        function _sanitizeURLCallback( $matches )
        {
            return 'href="'.$this->sanitizeURL( $matches[1] ).'"';
        }
        
        /**
         * Remove potential flaws in href attributes
         * @access  private
         * @param   string html tag
         * @return  string filtered html tag
         */
        function sanitizeHref( $str )
        {
            $HTML_Sanitizer_URL = 'href="([^"]+)"';

            /* return preg_replace("/$HTML_Sanitizer_URL/ie"
                , "'href=\"'.\$this->sanitizeURL('\\1').'\"'"
                , $str ); */
            return preg_replace_callback("/$HTML_Sanitizer_URL/i"
                , array( $this, '_sanitizeURLCallback' )
                , $str );
        }
        
        /**
         * Remove dangerous attributes from html tags
         * @access  private
         * @param   string html tag
         * @return  string filtered html tag
         */
        function removeEvilAttributes( $str )
        {
            if ( ! $this->_allowDOMEvents )
            {
                $str = preg_replace_callback('/<(.*?)>/i'
                    // , "'<'.\$this->removeDOMEvents('\\1').'>'"
                    , array( $this, '_removeDOMEventsCallback' )
                    , $str );
            }
            
            if ( ! $this->_allowStyle )
            {
                $str = preg_replace_callback('/<(.*?)>/i'
                    // , "'<'.\$this->removeStyle('\\1').'>'"
                    , array( $this, '_removeStyleCallback' )
                    , $str );
            }
                
            return $str;
        }
        
        function removeDOMEvents( $str )
        {
            $str = preg_replace ( '/\s*=\s*/', '=', $str );

            $HTML_Sanitizer_stripAttrib = '(onclick|ondblclick|onmousedown|'
                . 'onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|'
                . 'onkeyup|onfocus|onblur|onabort|onerror|onload)'
                ;

            $str = stripslashes( preg_replace("/$HTML_Sanitizer_stripAttrib/i"
                , 'forbidden'
                , $str ) );

            return $str;
        }
        
        function _removeDOMEventsCallback( $matches )
        {
            return '<' . $this->removeDOMEvents( $matches[1] ) . '>';
        }
        
        function removeStyle( $str )
        {
            $str = preg_replace ( '/\s*=\s*/', '=', $str );

            $HTML_Sanitizer_stripAttrib = '(style)'
                ;

            $str = stripslashes( preg_replace("/$HTML_Sanitizer_stripAttrib/i"
                , 'forbidden'
                , $str ) );

            return $str;
        }
        
        function _removeStyleCallback( $matches )
        {
            return '<' . $this->removeStyle( $matches[1] ) . '>';
        }
        
        /**
         * Remove dangerous HTML tags
         * @access  private
         * @param   string html code
         * @return  string filtered url
         */
        function removeEvilTags( $str )
        {
            $allowedTags = $this->_allowedTags;
            
            if ( $this->_allowScript )
            {
                $allowedTags .= '<script>';
            }
            
            if ( $this->_allowStyle )
            {
                $allowedTags .= '<style>';
            }
            
            if ( $this->_allowObjects )
            {
                $allowedTags .= '<object><embed><applet><param>';
            }
            
            $allowedTags .= $this->_additionalTags;
            
            $str = strip_tags($str, $allowedTags );

            return $str;
        }
        
        /**
         * Sanitize HTML
         *  remove dangerous tags and attributes
         *  clean urls
         * @access  public
         * @param   string html code
         * @return  string sanitized html code
         */
        function sanitize( $html )
        {
            $html = $this->removeEvilTags( $html );
            
            $html = $this->removeEvilAttributes( $html );
            
            $html = $this->sanitizeHref( $html );
            
            return $html;
        }
    }
    
    /* $test = '<p>Hello</p><script type="text/javascript">alert("plop !")</script>'
        . '<a href="javascript:alert(\'plop !\')">click me!</a>'
        . '<a href="" onclick="alert(\'plop !\');return false;">click me too !</a>'
        ;
        
    $san = new HTML_Sanitizer;
    echo $san->sanitize( $test );
    
    echo "\n\n";
    $san->allowScript();
    $san->allowDOMEvents();
    echo $san->sanitize( $test ); */
