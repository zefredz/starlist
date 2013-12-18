<?php

    class ClassLoader
    {
        public function load( $className )
        {
            $classPath = __DIR__ .'/'. str_replace('_', DIRECTORY_SEPARATOR, strtolower($className)).'.class.php';

            if ( file_exists($classPath) )
            {
                require_once $classPath;
            }
        }

        public function register()
        {
            spl_autoload_register(array($this,'load'));
        }
    }