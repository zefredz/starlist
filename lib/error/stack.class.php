<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

    class Error_Stack
    {
        // Error stack operations

        static function initStack()
        {
            $GLOBALS['__ERROR_STACK'] = array();
        }

        static function raise( $errmsg = '', $errno = ERRNO_UNKNOWN, $file = null, $line = null )
        {
            if ( ! isset( $GLOBALS['__ERROR_STACK'] ) )
            {
                Error_Stack::initStack();
            }

            $error = new Error( $errmsg, $errno, $file, $line );

            $GLOBALS['__ERROR_STACK'][] = $error;

            return false;
        }

        static function raiseError( $error )
        {
            if ( ! isset( $GLOBALS['__ERROR_STACK'] ) )
            {
                Error_Stack::initStack();
            }

            $GLOBALS['__ERROR_STACK'][] = $error;

            return false;
        }

        static function hasError()
        {
            return (isset($GLOBALS['__ERROR_STACK'])
                && count($GLOBALS['__ERROR_STACK']) > 0);
        }

        static function discardLast()
        {
            if ( isset($GLOBALS['__ERROR_STACK'])
                && count($GLOBALS['__ERROR_STACK']) > 0 )
            {
                return array_pop( $GLOBALS['__ERROR_STACK'] );
            }
            else
            {
                return null;
            }
        }

        static function getLast()
        {
            if ( isset($GLOBALS['__ERROR_STACK'])
                && count($GLOBALS['__ERROR_STACK']) > 0 )
            {
                return $GLOBALS['__ERROR_STACK'][count($GLOBALS['__ERROR_STACK']) - 1];
            }
            else
            {
                return null;
            }
        }

        static function getStack()
        {
            if ( isset($GLOBALS['__ERROR_STACK']) )
            {
                return $GLOBALS['__ERROR_STACK'];
            }
            else
            {
                return array();
            }
        }

        static function trace( $debug = false )
        {
            if ( defined ( 'DEBUG_MODE' ) && DEBUG_MODE )
            {
                $debug = true;
            }

            $stack = Error_Stack::getStack();

            $ret = '';

            foreach ( $stack as $error )
            {
                $ret .= $error->format( $error, $debug ) . "\n";
            }

            return $ret;
        }
    }

