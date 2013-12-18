<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    class HTML_Reporter
    {
        protected $expandMessage;
        protected $showDetails;
        
        function __construct()
        {
            $this->expandMessage = 'details';
            $this->showDetails = false;
        }

        function setExpandMessage( $msg )
        {
            $this->expandMessage = $msg;
        }

        function showDetailsByDefault()
        {
            $this->showDetails = true;
        }

        function hideDetailsByDefault()
        {
            $this->showDetails = false;
        }

        function renderJs()
        {
            $js = '<script type="text/javascript">
function toggleDetails( id )
{
    var details = document.getElementById( id );

    if ( details.style.display == \'block\' )
    {
        details.style.display = \'none\';
    }
    else
    {
        details.style.display = \'block\';
    }
}
</script>' . "\n";

            return $js;
        }
        
        function renderStyle()
        {
            $style = '<style type="text/css" media="screen">' . "\n"
                . '.details{display:' 
                . ( $this->showDetails
                    ? 'block'
                    : 'none' )
                . ';}'
                . '</style>' . "\n"
                ;
                
            $style .= '<style type="text/css" media="print">' . "\n"
                . '.toggleDetails{display:none;}'
                . '</style>' . "\n"
                ;
                
            return $style;
        }

        /**
         * Display error report
         * @param   string summary
         * @param   string details
         * @return  string html code
         */
        function render( $summary, $details )
        {
            $id = uniqid('details');

            if ( empty( $details ) )
            {
                $display = '<p class="summary">'.$summary.'</p>' . "\n";
            }
            else
            {
                $display = '<p class="summary">' . $summary
                    . '<span class="toggleDetails">'
                    .'[<a href="javascript:toggleDetails(\''.$id.'\')">'
                    . $this->expandMessage
                    . '</a>]'
                    . '</span>'
                    . '</p>' . "\n"
                    . '<div id="'.$id.'" class="details">' . "\n"
                    . $details
                    . "\n" . '</div>' . "\n"
                    ;
            }

            return $display;
        }
    }

