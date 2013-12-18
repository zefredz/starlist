<?php
    
    class WebService_Client_Fopen extends WebService_Client
    {
        function retrieveUrl( $url )
        {
            $contents = @file_get_contents( $url );
            
            if ( ! $contents )
            {
                return Error::raise( RUNTIME_ERROR | IO_ERROR
                    , 'Cannot retrieve url ' . $url );
            }
            else
            {
                return $contents;
            }
        }
    }
    