<?php  // $Id$
    
    // vim: expandtab sw=4 ts=4 sts=4:
    
    /**
     * @author  Frederic Minne <zefredz@claroline.net>
     * @copyright Copyright &copy; 2006-2007, Frederic Minne
     * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
     * @version 1.0
     * @package HTML.Datagrid
     */
    
    class HTML_Datagrid_Table
    {
        protected $data = array();
        protected $dataFields = array();
        protected $dataUrls = array();
        protected $actionFields = array();
        protected $actionUrls = array();
        protected $footer = '';
        protected $displayIfEmpty = true;
        protected $emptyMessage = null;
        protected $title = '';
        
        function setTitle( $title )
        {
            $this->title = $title;
        }
        
        function setEmptyMessage( $str )
        {
            $this->emptyMessage = $str;
        }
        
        function setDataFields( $dataFields )
        {
            $this->dataFields = $dataFields;
        }
        
        function setData( $data )
        {
            $this->data = $data;
        }
        
        function setDataUrls( $dataUrls )
        {
            $this->dataUrls = $dataUrls;
        }
        
        function setActionFields( $actionFields )
        {
            $this->actionFields = $actionFields;
        }
        
        function setActionUrls( $actionUrls )
        {
            $this->actionUrls = $actionUrls;
        }
        
        function setFooter( $footer )
        {
            $this->footer = $footer;
        }
        
        function disableDisplayIfEmpty()
        {
            $this->displayIfEmpty = false;
        }
        
        function enableDisplayIfEmpty()
        {
            $this->displayIfEmpty = true;
        }
        
        function render()
        {
            if ( false === $this->displayIfEmpty && empty( $this->data ) )
            {
                return '';
            }
            
            $colspan = count( $this->dataFields ) + count( $this->actionFields );
            
            $colspan = ' colspan="'.$colspan.'"';
            
            // table head
            
            $table = '<table style="width: 100%">' . "\n"
                . (!empty($this->title) ? '<caption>' . $this->title . '</caption>' . "\n" : '' )
                . '<thead>' . "\n"
                . '<tr>' . "\n"
                ;
                    
            foreach ( $this->dataFields as $field )
            {
                $table .= '<th>'
                    . $field
                    . '</th>'
                    ;
            }
            
            foreach ( $this->actionFields as $field )
            {
                $table .= '<th>'
                    . $field
                    . '</th>'
                    ;
            }
                    
            $table .= '</tr>' . "\n"
                . '</thead>' . "\n"
                ;
                
            // table body
            
            $table .= '<tbody>' . "\n";
            
            if ( count( $this->data ) > 0 )
            {
                foreach ( $this->data as $row )
                {
                    $table .= '<tr>';
                    
                    foreach ( array_keys($this->dataFields) as $key ) // => $value )
                    {
                        if ( array_key_exists( $key, $this->dataUrls ) )
                        {
                            $table .= '<td>';
                            
                            if ( !is_null( $row[$key] ) )
                            {
                            
                                $table .= str_replace( '%'.$key.'%', $row[$key],
                                    str_replace( '%html('.$key.')%', htmlspecialchars($row[$key]),
                                        str_replace( '%uu('.$key.')%', rawurlencode($row[$key]), $this->dataUrls[$key] )));
                            }
                            else
                            {
                                $table .= '&nbsp;';
                            }
                            
                            $table .= '</td>';
                        }
                        else
                        {
                            $table .= '<td>' . htmlspecialchars( $row[$key] ) . '</td>';
                        }
                    }
                    
                    foreach ( $this->actionUrls as $url )
                    {
                        foreach ( array_keys( $row ) as $key )
                        {
                            $table .= str_replace( '%'.$key.'%', $row[$key],
                                str_replace( '%html('.$key.')%', htmlspecialchars($row[$key]),
                                    str_replace( '%uu('.$key.')%', rawurlencode($row[$key]), $url )));
                        }
                    }
                    
                    $table .= '</tr>' . "\n";
                }
            }
            else
            {
                if ( is_null( $this->emptyMessage ) )
                {
                    $this->emptyMessage = 'Empty';
                }
                
                $table .= '<tr><td' . $colspan . '>' . $this->emptyMessage . '</td></tr>' . "\n";
            }
            
            $table .= '</tbody>' . "\n";
            
            // table foot
            
            if ( !empty( $this->footer ) )
            {
                $table .= '<tfoot>' . "\n"
                    . '<tr>'
                    . '<td'.$colspan.'>'
                    . $this->footer
                    . '</td>'
                    . '</tr>' . "\n"
                    . '</tfoot>'
                    . "\n"
                    ;
            }
            
            // end of table
            
            $table .= '</table>' . "\n";
            
            return $table;
        }
    }

