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
    * listen to a particular event
    * @access public
    */
    class Event_Listener
    {
        // protected fields
        protected $_callback;

        /**
        * constructor
        * @access public
        * @param callback to call when the observed event occurs
        */
        function __construct( $callback )
        {
            $this->_callback = $callback;
        }

        /**
        * notification of event occurence
        * @access package private
        * @param Event event the event to handle
        */
        function handle( $event )
        {
            call_user_func( $this->_callback, $event );
        }
    }

