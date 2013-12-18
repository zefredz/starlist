<?php 

    class StarList_SearchEngine
    {
        function __construct()
        {
            $this->condList = array();
        }
        
        function addCondition( $cond )
        {
            $this->condList[] = $cond;
        }
        
        function search( $starlist )
        {
            $ret = array();
            
            foreach ( $starlist->toArray() as $id => $star )
            {
                $nbrCond = count( $this->condList );
                $tmp = array();
                $match = null;
                
                for ( $c = 0; $c < $nbrCond; $c++ )
                {
                    $cond = $this->condList[ $c ];
                    $condRes = $this->executeCondition( $cond, $star );
                    
                    if ( is_null( $match ) )
                    {
                        $match = $condRes;
                    }
                    else
                    {
                        $match = ( $cond['operator'] === 'AND' )
                            ? ( $match && $condRes )
                            : ( $match || $condRes )
                            ;
                    }
                }
                
                if ( $match && ! array_key_exists( $id, $ret ) )
                {
                    $ret[] = $star;
                }
            }
            
            $tmp = new StarList;
            $tmp->loadArray( $ret );

            return $tmp;
        }
        
        function executeCondition( $cond, $star )
        {
            if ( $cond['type'] === 'DIST' )
            {
                $condRes = $this->matchDistance( (float)$star['dist'], $cond['criterium']['dist'], $cond['criterium']['operator'] );
            }
            else
            {
                if ( $cond['type'] === 'CRITERIUM' )
                {
                    $condRes = $this->matchCriterium( $cond, $star );
                }
                elseif ( $cond['type'] === 'REGEXP' )
                {
                    $condRes = $this->matchCriteriumRegexp( $cond, $star );
                }
                elseif ( $cond['type'] === 'CALLBACK' )
                {
                    $condRes = $this->matchCallback( $cond, $star );
                }
            }
            
            return $condRes;
        }
        
        function matchCriterium( $cond, $star )
        {
            $match = false;
            $firstTime = true;
            
            if ( ! is_array( $cond['key'] ) )
            {
                $keys = array( $cond['key'] );
            }
            else
            {
                $keys = $cond['key'];
            }
            
            $needle = $cond['criterium'];
            
            foreach ( $keys as $key )
            {
                $condRes = (stripos( $star[$key], $needle ) !== false);
                
                if ( $firstTime )
                {
                    $match = $condRes;
                    $firstTime = false;
                }
                
                $match = $cond['operator'] === 'AND'
                    ? $match && $condRes
                    : $match || $condRes
                    ;
            }
            
            return $match;
        }
        
        function matchCriteriumRegexp( $cond, $star )
        {
            $match = false;
            $firstTime = true;
            
            if ( ! is_array( $cond['key'] ) )
            {
                $keys = array( $cond['key'] );
            }
            else
            {
                $keys = $cond['key'];
            }
            
            $needle = $cond['criterium'];
            
            foreach ( $keys as $key )
            {
                $condRes = preg_match( $needle, $star[$key] );
                
                if ( $firstTime )
                {
                    $match = $condRes;
                    $firstTime = false;
                }
                
                $match = $cond['operator'] === 'AND'
                    ? $match && $condRes
                    : $match || $condRes
                    ;
            }
            
            return $match;
        }
        
        function matchDistance( $distA, $distB,  $operator = '=' )
        {
            switch ( $operator )
            {
                case '<': return $distA < $distB;
                case '>': return $distA > $distB;
                case '<=': return $distA <= $distB;
                case '>=': return $distA >= $distB;
                case '=': return $distA == $distB;
                default: {
                    trigger_error( "Invalid operator $operator", E_USER_WARNING );
                    return false;
                }
            }
        }
        
        function matchCallback( $cond, $star )
        {
            $callback = $cond['criterium'];
            return call_user_func( $callback, $star );
        }
    }

