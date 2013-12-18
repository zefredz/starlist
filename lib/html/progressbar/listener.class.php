<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

    if ( count( get_included_files() ) == 1 )
    {
        die( 'The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead' );
    }
    
    require_once dirname(__FILE__) . '/../progressbar.class.php';

    class HTML_ProgressBar_Listener extends HTML_ProgressBar
    {
        protected $progressBar;
        
        function __construct( $progressBar )
        {
            $this->progressBar = $progressBar;
        }
        
        function handle( $event )
        {
            $type = $event->getEventType();

            switch( $type )
            {
                case self::progressEvent():
                {
                    $this->handleProgress( $event );
                } break;
                case self::finishedEvent():
                {
                    $this->handleFinished( $event );
                } break;
                default:
                {
                    // error
                }
            }
        }
        
        function handleProgress( $event )
        {
             $args = $event->getArgs();
             $this->progressBar->setPercent( $args['progress'], $args['text'] );
        }
        
        function handleFinished( $event )
        {
             $args = $event->getArgs();
             $this->progressBar->End( $args['hide'], $args['text'] );
        }
        
        static function progressEvent()
        {
            return 'ProgressBar::Progress';
        }
        
        static function finishedEvent()
        {
            return 'ProgressBar::Finished';
        }
    }

