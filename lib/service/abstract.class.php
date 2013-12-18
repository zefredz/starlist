<?php

    /**
     * Abstract Service
     * @abstract
     */
    abstract class Service_Abstract extends Error_Handling
    {
        protected $output = '';
        
        /**
         * Set service execution output
         * @access  protected
         * @param   output string service output string
         */
        function setOutput( $output )
        {
            $this->output = $output;
        }
        
        /**
         * Get service execution output
         * @param   string service output string
         */
        function getOutput()
        {
            return $this->output;
        }
        
        /**
         * Execute service
         * @abstract
         */
        abstract function run();
    }
