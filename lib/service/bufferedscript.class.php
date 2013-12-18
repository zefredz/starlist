<?php

    /**
     * Script Service
     * Execute a given script that uses set/getOutput methods
     * to communicate execution result to calling Service object
     */
    class Service_BufferedScript extends Service_BufferedAbstract
    {
        protected $scriptPath;
        
        /**
         * Constructor
         * @param   scriptPath string path to the script to call
         */
        function __construct( $scriptPath )
        {
            parent::__construct();
            $this->scriptPath = $scriptPath;
        }
        
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
                require_once $this->scriptPath;
                
                return true;
            }
        }
    }