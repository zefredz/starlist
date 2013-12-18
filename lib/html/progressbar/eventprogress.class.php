<?php

    class HTML_ProgressBar_EventProgress extends Event
    {
        function __construct( $percent, $text = 'loading...' )
        {
            $type = HTML_ProgressBar_Listener::progressEvent();
            $args = array(
                'progress' => $percent,
                'text' => $text
            );
            parent::__construct( $type, $args );
        }
    }