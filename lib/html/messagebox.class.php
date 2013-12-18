<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    /**
     * @author  Frederic Minne <zefredz@claroline.net>
     * @copyright Copyright &copy; 2006-2007, Frederic Minne
     * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
     * @version 1.0
     * @package HTML
     */
    
    class HTML_MessageBox
    {
        static function FatalError( $message )
        {
            return self::Message( $message, 'fatalError' );
        }
        
        static function Error( $message )
        {
            return self::Message( $message, 'error' );
        }
        
        static function Warning( $message )
        {
            return self::Message( $message, 'warning' );
        }
        
        static function Notice( $message )
        {
            return self::Message( $message, 'notice' );
        }
        
        static function Info( $message )
        {
            return self::Message( $message, 'info' );
        }
        
        static function Success( $message )
        {
            return self::Message( $message, 'success' );
        }
        
        static function Question( $message )
        {
            return self::Message( $message, 'question' );
        }
        
        static function Message( $message, $messageClass = null )
        {
            if ( !empty( $messageClass ) )
            {
                $class = ' class="' . $messageClass . '"';
            }
            else
            {
                $class = '';
            }
            
            $output = '<div' . $class . '>' . "\n"
                . $message
                . '</div>'
                . "\n"
                ;
                
            return self::Display( $output );
        }
        
        static function Display( $message )
        {
            return '<div class="messageBox">' . "\n"
                . $message . "\n"
                . '</div>'
                . "\n"
                ;
        }
    }

