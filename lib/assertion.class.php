<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

    class Assertion
    {
        private $log = array();
        
        function enable()
        {
            assert_options( ASSERT_ACTIVE, 1 );
            assert_options( ASSERT_WARNING, 0 );
            assert_options( ASSERT_CALLBACK, array( &$this, "assertionHandler" ) );
        }
        
        function disable()
        {
            assert_options( ASSERT_ACTIVE, 0 );
        }
        
        function assertionHandler( $file, $line, $message )
        {
            $out = "Assertion $message failed in "
                . basename( $file )
                . " at "
                . dirname( $file )
                . " on line "
                . $line
                ;

            $this->log[] = $out;
        }
        
        function failed()
        {
            return count( $this->log ) > 0;
        }
        
        function size()
        {
            return count( $this->log );
        }
        
        function getSummary()
        {
            return  $this->size() . ' assertion(s) failed';
        }
        
        function getDetails()
        {
            return  implode( "<br />\n", $this->log );
        }
        
        function report()
        {
            $ret = array();
            $ret['summary'] = $this->getSummary();
            $ret['details'] = $this->getDetails();
            return $ret;
        }
    }
    