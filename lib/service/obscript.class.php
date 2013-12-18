<?php

    /**
     * Output Buffering Script Service
     * Execute a given script by using ob_* functions
     * to retreive execution result from called script
     */
    class Service_ObScript extends Service_Script
    {
        function run()
        {
            if ( ! file_exists( $this->scriptPath ) )
            {
                $this->setError( "File not found "
                    . $this->scriptPath
                    , 404 );
                    
                return false;
            }
            else
            {
                ob_start();
                require_once $this->scriptPath;
                $output = ob_get_contents();
                ob_end_clean();
                
                $this->setOutput( $output );
                
                return true;
            }
        }
    }