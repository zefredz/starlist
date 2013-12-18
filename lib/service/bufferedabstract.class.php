<?php

    abstract class Service_BufferedAbstract extends Service_Abstract
    {
        protected $output;
        
        function __construct()
        {
            $this->output = new Output_Buffer();
        }
        
        /**
         * Set service execution output
         * @access  protected
         * @param   output string service output string
         */
        function setOutput( $output )
        {
            $this->output->replace( $output );
        }
        
        /**
         * Get service execution output
         * @param   string service output string
         */
        function getOutput()
        {
            return $this->output->getContents();
        }
        
        function getBuffer()
        {
            return $this->output;
        }
    }