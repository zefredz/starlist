<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

    /**
     * @author  Frederic Minne <zefredz@claroline.net>
     * @copyright Copyright &copy; 2006-2007, Frederic Minne
     * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
     * @version 1.0
     * @package HTML.Datagrid
     */
    
    
    class HTML_Datagrid_Template
    {
        protected $template;
        protected $data;
        protected $footer = '';
        protected $header = '';
        protected $emptyMessage = null;
        
        function setEmptyMessage( $str )
        {
            $this->emptyMessage = $str;
        }
        
        function setData( $data )
        {
            $this->data = $data;
        }
        
        function setTemplate( $template )
        {
            $this->template = $template;
        }
        
        function setFooter( $footer )
        {
            $this->footer = $footer;
        }
        
        function setHeader( $header )
        {
            $this->header = $header;
        }
        
        function render()
        {
            $output = '';
            
            if ( !empty( $this->header ) )
            {
                $output .= $this->header  . "\n";
            }
            
            if ( count( $this->data ) > 0 )
            {
                foreach ( $this->data as $row )
                {
                    $output .= $this->template->render( $row );
                }
            }
            else
            {
                if ( is_null( $this->emptyMessage ) )
                {
                    $this->emptyMessage = get_lang('Empty');
                }
                
                $output .= $this->emptyMessage;
            }
            
            if ( !empty( $this->footer ) )
            {
                $output .= $this->footer . "\n";
            }
            
            return $output;
        }
    }
?>
