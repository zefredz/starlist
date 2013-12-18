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
    * Compute size of arrays containing object reference with possible
    * recursion
    *
    * @param array arry
    * @return int size of the array, -1 if $arry is not an array
    * @access global
    */
    function array_size( $arry )
    {
        if ( !is_array( $arry ) )
        {
            return -1;
        }
        else
        {
            $size = 0;
            
            foreach( $arry as $value )
            {
                $size++;
            }
            
            return $size;
        }
    }

