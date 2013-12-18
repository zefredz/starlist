<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

    class Profiler
    {
        private $startTime;
        private $running;
        private $endTime;
        private $log;

        function __construct()
        {
            $this->log = array();
            $this->running = false;
            $this->startTime = null;
            $this->endTime = null;
        }

        function start( $restart = false )
        {
            if ( $this->running
                && ! $restart )
            {
                return;
            }

            $this->startTime = $this->_getCurrentTime();
            $this->running = true;

            $this->log[] = sprintf('[%f seconds] start of profiling', 0);
        }

        function restart()
        {
            $this->start( true );
        }

        function stop()
        {
            if ( ! $this->running )
            {
                $this->start();
            }

            $this->endTime = $this->_getCurrentTime();
            $this->running = false;

            $elapsed = $this->endTime - $this->startTime;
            $this->log[] = sprintf('[%f seconds] end of profiling', $elapsed);
        }

        function mark( $msg )
        {
            if ( ! $this->running )
            {
                $this->start();
            }

            $timestamp = $this->_getCurrentTime();

            $elapsed = $timestamp - $this->startTime;
            $elapsed = sprintf( '%f seconds', $elapsed );

            $mark = "[$elapsed] $msg";

            $this->log[] = $mark;
        }

        function report( $htmlReport = true )
        {
            if ( $this->running )
            {
                $this->stop();
            }

            if ( $htmlReport )
            {
                return $this->_htmlReport();
            }
            else
            {
                return $this->_plainReport();
            }
        }

        function getElapsedTime()
        {
            if ( is_null( $this->startTime ) && is_null( $this->endTime ))
            {
                return 0;
            }
            
            // still running
            if ( $this->running )
            {
                $currentTime = $this->_getCurrentTime();
            }
            else
            {
                $currentTime = $this->endTime;
            }
            
            return $currentTime - $this->startTime;
        }

        function _htmlReport()
        {
            $report = '<pre>' . "\n";

            $report .= $this->_plainReport();

            $report .= '</pre>' . "\n";

            return $report;
        }

        function _plainReport()
        {
            $report = "--- Profiler Report ---\n";
            $report .= "\n-- Summary --\n\n";
            $report .= "\tProfiler started at " . $this->startTime . "\n";
            $report .= "\tProfiler stopped at " . $this->endTime . "\n";

            $strTime = "\tTotal elapsed time : %f seconds\n";
            $elapsedTime = $this->getElapsedTime();

            $report .= sprintf( $strTime, $elapsedTime );

            $report .= "\n-- Details --\n\n";

            foreach ( $this->log as $mark )
            {
                $report .= "\t$mark\n";
            }

            $report .= "\n--- End of Profiler Report ---\n";

            return $report;
        }

        function _getCurrentTime()
        {
            //Get current time
            $mtime = microtime();
            //Split seconds and microseconds
            $mtime = explode(" ",$mtime);
            //Create one value for start time
            $mtime = $mtime[1] + $mtime[0];

            return $mtime;
        }
    }

