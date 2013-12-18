<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

    class WebApplication
    {
        public $entryUrl;
        public $rootWeb;
        public $siteName;

        function __construct( $siteName = '' )
        {
            if ( defined( 'WebApplication_singleton_lock' ) )
            {
                $this->setError( 'Cannot Instanciate a Singleton twice', ERRNO_SINGLETON );
                return null;
            }
            define( 'WebApplication_singleton_lock', true );

            if ( !defined( 'WebApplication_getInstance_lock' ) )
            {
                $this->setError( 'Cannot Instanciate a Singleton, use getInstance', ERRNO_SINGLETON );
                return null;
            }
            
            $this->entryUrl = $_SERVER['PHP_SELF'];
            $this->rootWeb = dirname($_SERVER['PHP_SELF']);
            $this->siteName = $siteName;
        }
        
        function set( $name, $value )
        {
            $this->$name = $value;
        }
        
        static function getInstance( $siteName = '' )
        {
            static $instance = array();

            if ( !defined( 'WebApplication_getInstance_lock' ) )
            {
                define( 'WebApplication_getInstance_lock', true );
            }

            if ( ! count( $instance ) )
            {
                $instance[0] = new self( $siteName );
            }

            return $instance[0];
        }
    }

