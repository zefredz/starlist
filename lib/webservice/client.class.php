<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

    abstract class WebService_Client
    {
        abstract function retrieveUrl( $url );
        
        static function getInstance()
        {
            if ( (int) ini_get( 'allow_url_fopen' ) )
            {
                $tmp = new WebService_Client_Fopen;
                return $tmp;
            }
            elseif ( extension_loaded( 'curl' ) )
            {
                $tmp = new WebService_Client_Curl;
                return $tmp;
            }
            else
            {
                return Error::raise( ERRNO_EXTENSION_NOT_FOUND
                    , 'You need to activate Curl or url_allow_fopen' );
            }
        }
    }
