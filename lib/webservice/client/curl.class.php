<?php

    class WebService_Client_Curl extends WebService_Client
    {
        function retrieveUrl( $url )
        {
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);

            $contents curl_exec($ch);
            
            curl_close($ch);

            if ( FALSE === $contents )
            {
                return Error::raise( RUNTIME_ERROR | IO_ERROR
                    , curl_errno() . ' - ' . curl_error() );
            }
            else
            {
                return $contents;
            }
        }
    }
    