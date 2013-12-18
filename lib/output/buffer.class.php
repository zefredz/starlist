<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    if ( count( get_included_files() ) == 1 )
    {
        die( 'The file ' . __FILE__ . ' cannot be accessed directly, use include instead' );
    }

    class Output_Buffer
    {
        protected $contents = '';
        
        function send()
        {
            echo $this->contents;
        }
        
        function clean()
        {
            $this->contents = '';
        }
        
        function flush()
        {
            $this->send();
            // force I/O flush
            flush();
            $this->clean();
        }
        
        function append( $str )
        {
            $this->contents .= $str;
        }
        
        function replace( $str )
        {
            $this->contents = $str;
        }
        
        function getContents()
        {
            return $this->contents;
        }
    }

