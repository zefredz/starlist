<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    if ( count( get_included_files() ) == 1 ) die( '---' );
    
    require_once dirname(__FILE__) . '/../helper.class.php';
    
    /**
     * Popup helper class
     * 
     * @see popup.js
     * @author  Frederic Minne <zefredz@claroline.net>
     * @copyright Copyright &copy; 2006-2007, Frederic Minne
     * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
     * @version 1.0
     * @package Javascript::Popup
     */
    class Javascript_Popup_Helper
    {        
        /**
         * Create popup call html code
         * @return  string
         */
        static function popup( $url, $title = '', $width = 300, $height = 300 )
        {
            $popup = "popup( '" . $url . "'" . ", '" . $title 
                    . "', " . $width . "," . $height . ");"
                    ;
                    
            return $popup;
        }
        
        static function popupLink( $url, $text, $title = '', $width = 300, $height = 300, $class = '', $img = '' )
        {
            $class = empty( $class ) ? '' : ' class="' . $class . '"';
            $img = empty( $img ) ? '' : '<img src="'.$img.'" alt="" style="border:0px" />&nbsp;';
            return '<a href="'.$url.'" target="_blank" onclick="'
                . Javascript_Popup_Helper::popup( $url, $title, $width, $height )
                . 'return false;"' . $class
                . '>'
                . $img
                . $text
                . '</a>'
                ;
        }
        
        /**
         * Display help link
         * @param   string about help subject
         * @return  string help link
         */
        static function helpLink( $about )
        {
            $callback = $_SERVER['PHP_SELF'] . '?page=help&amp;inPopup=true&amp;about='
                . rawurlencode( $about )
                ;

            return PopupHelper::popupLink(
                $callback,
                'Help',
                rawurlencode( $about ),
                400,
                600,
                'helpLnk',
                $GLOBALS['imageRepositoryWeb'].'/icons/help.gif' );
        }
    }

