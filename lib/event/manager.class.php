<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

    require_once __DIR__ .'/utils.lib.php';
    
    # ***** BEGIN LICENSE BLOCK *****
    # This file is part of EventManager.
    # Copyright (c) 2005-2007 Frederic Minne <zefredz@gmail.com>.
    # All rights reserved.
    #
    # EventManager is free software; you can redistribute it and/or modify
    # it under the terms of the GNU General Public License as published by
    # the Free Software Foundation; either version 2 of the License, or
    # (at your option) any later version.
    #
    # EventManager is distributed in the hope that it will be useful,
    # but WITHOUT ANY WARRANTY; without even the implied warranty of
    # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    # GNU General Public License for more details.
    #
    # You should have received a copy of the GNU General Public License
    # along with DotClear; if not, write to the Free Software
    # Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    #
    # ***** END LICENSE BLOCK *****

    /**
    * Class to manage events and dispatch them to event listeners
    * @access public
    */
    class Event_Manager
    {
        // protected fields
        protected $_registry = array();

        /**
         * Constructor
         * @access public
         */
        function __construct( $fromGetInstance = false )
        {
            if ( ! $fromGetInstance  )
            {
                trigger_error( 'Singleton class, do not call directly' );
            }
        }

        /**
         * register new event listener for a given event
         * @access public
         * @param string eventType event type
         * @param EventListener listener reference to the event listener
         * @return string event listener ID
         */
        function register( $eventType, $listener )
        {
            if ( ! isset( $this->_registry[$eventType] ) )
            {
                $this->_registry[$eventType] = array( );
            }
            
            $id = md5( serialize( $listener ) );
            $this->_registry[$eventType][$id] = $listener;
            
            return $id;
        }

        /**
         * unregister event listener
         * @access public
         * @param string eventype type of event watching by the listener
         * @param string id listener ID
         * @return bool
         */
        function unregister( $eventType, $id )
        {
            if ( array_key_exists( $eventType, $this->_registry )
                && array_key_exists( $id, $this->_registry[$eventType] ) )
            {
                unset( $this->_registry[$eventType][$id] );
                
                if ( array_size( $this->_registry[$eventType] ) == 0 )
                {
                    unset( $this->_registry[$eventType] );
                }
                
                return true;
            }
            else
            {
                return false;
            }
        }

        /**
         * notify occurence of an event to the event manager
         * @access package private
         * @param string event type of occured event
         * @return int number of listeners notified or boolean false
         */
        function eventOccurs( $event )
        {
            if ( isset( $this->_registry[$event->getEventType()] )
                && is_array( $this->_registry[$event->getEventType( )] )
                && array_size( $this->_registry[$event->getEventType( )] ) != 0 )
            {
                $cnt = 0;
                
                foreach( $this->_registry[$event->getEventType( )] as $listener )
                {
                    if ( !is_null( $listener ) )
                    {
                        $listener->handle( $event );
                        $cnt++;
                    }
                }
                
                return $cnt;
            }
            else
            {
                if ( defined( "DEBUG_MODE" ) && DEBUG_MODE )
                {
                    $errmsg = __CLASS__ . " : No listener found for EVENT["
                        . $event->getEventType( ) . "]"
                        ;
                    trigger_error( $errmsg, E_USER_NOTICE );
                }
                
                return false;
            }
        }
        
        // static
        
        /**
         * get event manager singleton instance
         * @access public
         * @return EventManager instance
         * @static
         */
        static function getInstance()
        {
            static $instance = array();
            
            if ( ! count( $instance ) )
            {
                $instance[0] = new Event_Manager( true );
            }
            
            return $instance[0];
        }
        
        /**
         * notify occurence of an event to the event manager
         * @access public
         * @param string event type of occured event
         * @static
         */
        static function notify( $event )
        {
            $mngr = Event_Manager::getInstance();
            return $mngr->eventOccurs($event);
        }
        
        /**
         * register new event listener for a given event
         * @access public
         * @static
         * @param string eventType event type
         * @param EventListener listener reference to the event listener
         * @return string event listener ID
         */
        static function addListener( $eventType, $listener )
        {
            $mngr = Event_Manager::getInstance();
            return $mngr->register($eventType, $listener);
        }
        
        /**
         * unregister event listener
         * @access public
         * @static
         * @param string eventype type of event watching by the listener
         * @param string id listener ID
         * @return boolean
         */
        static function removeListener( $eventType, $id )
        {
            $mngr = Event_Manager::getInstance();
            return $mngr->unregister($eventType, $id);
        }
        
        // debugging methods

        /**
         * list all registered events and the number of listeners for each
         * @access public
         */
        function listRegiteredEvents( )
        {
            if ( is_array( $this->_registry )
                && array_size( $this->_registry ) != 0 )
            {
                foreach ( $this->_registry as $eventType => $listeners )
                {
                    echo "$eventType( " . array_size( $listeners ) . " )\n";
                }
            }
            else
            {
                echo "none\n";
            }
        }

        /**
         * list all registered listeners and their ID
         * @access public
         */
        function listRegisteredListeners( )
        {
            if ( is_array( $this->_registry )
                && array_size( $this->_registry ) != 0 )
            {
                foreach ( $this->_registry as $eventType => $listeners )
                {
                    echo "$eventType( " . array_size( $listeners ) . " )\n";

                    foreach ( $listeners as $id => $listener )
                    {
                        echo "\tID: $id\n";
                    }
                }
            }
            else
            {
                echo "none\n";
            }
        }
    }

