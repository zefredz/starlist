<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
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
    * Generic event generator for test purpose
    * @access public
    */
    class Event_Generator
    {
        /**
        * constructor
        * @access public
        */
        function __construct()
        {
        }

        /**
        * notify the event manager for an event occurence
        * @access public
        * @param Event event the event that occurs; an instance of the event class
        */
        function sendEvent( $event )
        {
            Event_Manager::notify( $event );
        }

        /**
        * public function to notify manager that an event occured,
        * using this fucntion instead of sendEvent allow to let the class create
        * the Event instance for you
        *
        * @param string eventType the type of the event
        * @param array args an array containing any parameters needed
        *   to describe the event occurence
        */

        function notifyEvent( $eventType, $args )
        {
            $event = new Event( $eventType, $args );
            $this->sendEvent( $event );
        }
    }

