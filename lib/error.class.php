<?php // $Id$
    
    // vim: expandtab sw=4 ts=4 sts=4:
    
    require_once dirname(__FILE__) . '/error/stack.class.php';
    
    // Conventional error codes
    // Source
    define ( 'UNKNOWN_SOURCE_ERROR'         , 0x000 );
    define ( 'SYSTEM_ERROR'                 , 0x100 );
    define ( 'DATABASE_ERROR'               , 0x200 );
    define ( 'SERVER_ERROR'                 , 0x300 );
    define ( 'FILE_ERROR'                   , 0x400 );
    define ( 'RUNTIME_ERROR'				, 0x500 );
    // Subject
    define ( 'CLASS_ERROR'                  , 0x010 );
    define ( 'LIB_ERROR'                    , 0x020 );
    define ( 'VENDOR_ERROR'                 , 0x030 );
    define ( 'DRIVER_ERROR'                 , 0x040 );
    define ( 'PHP_EXT_ERROR'                , 0x050 );
    define ( 'METHOD_ERROR'					, 0x060 );
    define ( 'ARGUMENT_ERROR'               , 0x070 );
    define ( 'PARSE_ERROR'                  , 0x080 );
    define ( 'INSTANCE_ERROR'               , 0x090 );
    // Type
    define ( 'UNKNOWN_ERROR'                , 0x001 );
    define ( 'NOT_FOUND_ERROR'              , 0x002 );
    define ( 'IO_ERROR'                     , 0x003 );
    define ( 'BOUNDARY_ERROR'				, 0x004 );
    define ( 'MISSING_ERROR'                , 0x005 );
    define ( 'WRONG_VALUE_ERROR'            , 0x006 );
    // Combined error codes
    define ( 'ERRNO_UNKNOWN'                , UNKNOWN_SOURCE_ERROR | UNKNOWN_ERROR );
    define ( 'ERRNO_NOT_FOUND'              , SYSTEM_ERROR | NOT_FOUND_ERROR );
    define ( 'ERRNO_CLASS_NOT_FOUND'        , SYSTEM_ERROR | NOT_FOUND_ERROR | CLASS_ERROR );
    define ( 'ERRNO_SINGLETON'              , SYSTEM_ERROR | NOT_FOUND_ERROR | CLASS_ERROR );
    define ( 'ERRNO_VENDOR_LIB_NOT_FOUND'   , SYSTEM_ERROR | NOT_FOUND_ERROR | VENDOR_ERROR );
    define ( 'ERRNO_DRIVER_NOT_FOUND'       , SYSTEM_ERROR | NOT_FOUND_ERROR | DRIVER_ERROR );
    define ( 'ERRNO_LIB_NOT_FOUND'          , SYSTEM_ERROR | NOT_FOUND_ERROR | LIB_ERROR );
    define ( 'ERRNO_EXTENSION_NOT_FOUND'    , SYSTEM_ERROR | NOT_FOUND_ERROR | PHP_EXT_ERROR );
    define ( 'ERRNO_FILE_ERROR'             , FILE_ERROR | UNKNOWN_ERROR );
    define ( 'ERRNO_FILE_NOT_FOUND'         , FILE_ERROR | NOT_FOUND_ERROR );
    define ( 'ERRNO_FILE_NOT_WRITEABLE'     , FILE_ERROR | IO_ERROR );
    define ( 'ERRNO_DATABASE_ERROR'         , DATABASE_ERROR | UNKNOWN_ERROR );
    define ( 'ERRNO_BOUNDARY_VIOLATION'		, RUNTIME_ERROR | METHOD_ERROR | BOUNDARY_ERROR );
    define ( 'ERRNO_ARGUMENT_MISSING'		, RUNTIME_ERROR | ARGUMENT_ERROR | MISSING_ERROR );
    define ( 'ERRNO_WRONG_ARGUMENT' 		, RUNTIME_ERROR | ARGUMENT_ERROR | WRONG_VALUE_ERROR );
    define ( 'ERRNO_FILE_PARSE_ERROR'       , FILE_ERROR | PARSE_ERROR );
    
    class Error extends Error_Stack
    {
        function __construct( $errmsg = 'Uknown Error'
            , $errno = ERRNO_UNKNOWN
            , $file = null
            , $line = null )
        {
            $this->errmsg = $errmsg;
            $this->errno = $errno;
            $this->file = ( !empty( $file ) ) ? $file : null;
            $this->line = ( !empty( $line ) ) ? $line : null;
            $this->trace = debug_backtrace();
        }
        
        function getSummary()
        {
            return $this->errno . ' - ' . $this->errmsg;
        }
        
        function getDetails( $trace = false )
        {
            $err = '';
            $err .= ( !empty( $this->file ) ) ? " in " . $this->file : '';
            $err .= ( !empty( $this->line ) ) ? " at line " . $this->line : '';
            
            if ( true === $trace )
            {
                $tmp = array();
                
                foreach( $this->getTrace() as $item )
                {
                    $tmp[] = $this->_formatTraceLine( $item );
                }
                
                $err .= "\n" . implode ("\n", $tmp );
            }
            
            return $err;
        }
        
        function _formatTraceLine( $item )
        {
            $tmp = array();
            
            foreach ( $item as $key => $value )
            {
                if ( $key == 'args' ) $value = implode ( ',', $value );
                $tmp[] = "$key : $value";
            }
            
            return implode ( ' - ', $tmp );
        }
        
        function getTrace()
        {
            return $this->trace;
        }
        
        function format( $debug = false )
        {
            if ( defined ( 'DEBUG_MODE' ) && DEBUG_MODE )
            {
                $debug = true;
            }
            
            $err = $this->getSummary();
            
            $err .= $this->getDetails( $debug );
            
            $err .= "\n";
            
            return $err;
        }
    }

