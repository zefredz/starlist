<?php

    class HTML_ProgressBar_EventFinished extends Event
    {
        function __construct( $hide = false, $text = 'finished!' )
        {
            $type = HTML_ProgressBar_Listener::finishedEvent();
            $args = array(
                'hide' => $hide,
                'text' => $text
            );
            parent::__construct( $type, $args );
        }
    }
