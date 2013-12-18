<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    /**
     * Star List in LST file format management and search library
     */

    class StarList
    {
        private $starList;

        function __construct()
        {
            $this->starList = array();
        }
        
        function getStar( $starName )
        {
            if ( $this->starExists( $starName ) )
            {
                return $this->starList[$starName];
            }
            else
            {
                return null;
            }
        }
        
        function starExists( $starName )
        {
            return array_key_exists( $starName, $this->starList );
        }

        function loadArray( $arr )
        {
            $this->starList = $arr;
        }

        function loadFile( $filePath, $gzipped = false )
        {
            if ( file_exists( $filePath ) )
            {
                $contents = $gzipped ? gzfile( $filePath ) : file( $filePath );
                $keys = array( 'sysName','starName','dist','class','mass'
                    ,'constellation','notes','position');

                $ret = array();
                
                foreach ( $contents as $linenbr => $line )
                {
                    $line = trim($line);
                    $data = split('/', $line);
                    
                    if ( count( $data ) != count( $keys ) )
                    {
                        trigger_error( "Invalid line at line number $linenbr : $line"
                            , E_USER_WARNING );
                    }
                    
                    $ret[$data[1]] = array_combine( $keys, $data );
                }

                $this->loadArray( $ret );
                return true;
            }
            else
            {
                trigger_error("Star List $filePath not found", E_USER_WARNING );
                return false;
            }
        }

        function size()
        {
            return count( $this->starList );
        }

        function toArray()
        {
            return $this->starList;
        }
    }
    