<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

    if ( count( get_included_files() ) == 1 )
    {
        die( 'The file ' . __FILE__ . ' cannot be accessed directly, use include instead' );
    }

    class HTML_ProgressBar
    {

        //----------------- parameters -----------------
        // position parameters
        protected $top = 60;
        protected $left = 50;
        protected $width = 200;
        protected $height = 30;

        //color parameters
        protected $border_color = '#000000';
        protected $percent_color = '#FFCC00';
        protected $txt_color = '#000000';
        protected $background_color = '#006699';

        //size parameters;
        protected $percent_size = 15;
        protected $txt_size = 15;
        
        // output buffer
        protected $buffer;
        
        function __construct( &$buffer )
        {
            $this->buffer =& $buffer;
        }

        //html parameter

        //----------------- private functions -----------------
        function Hide()
        {
            $this->buffer->append( '<script type="text/javascript">'
                . 'document.getElementById("myprogressbar").style.display="none";'
                . '</script>'
                . "\n" );
            
            $this->buffer->flush();
        }

        //----------------- public functions -----------------
        function setPosition($left, $top, $width, $height)
        {
            $this->top = $top;
            $this->left = $left;
            $this->width = $width;
            $this->height = $height;
        }

        function setColors($border_color, $percent_color, $txt_color, $background_color)
        {
            $this->border_color = $border_color;
            $this->percent_color = $percent_color;
            $this->txt_color = $txt_color;
            $this->background_color = $background_color;
        }

        function setSizes($percent_size, $txt_size)
        {
            $this->percent_size = $percent_size;
            $this->txt_size = $txt_size;
        }

        function generateHTML()
        {
            $html = '<div id="myprogressbar" style="display:block;position:absolute;top:'.$this->top;
            $html .= ';left:'.$this->left;
            $html .= ';width:'.$this->width.'px;">';

            //progress bar div
            $html .= '<div id="progrbar" style="position: relative; top: 0px';
            $html .= ';left: 1px';
            $html .= ';width:0px';
            $html .= ';height:'.$this->height.'px';
            $html .= ';background-color:'.$this->background_color.';z-index:0;"></div>';

            //percent div
            $html .= '<div id="percent" style="position:relative;top:-'.($this->height+1).'px';
            $html .= ';left: 0px';
            $html .= ';width: 100%';
            $html .= ';height:'.$this->height.'px;border:1px solid '.$this->border_color.';font-family:Tahoma;font-weight:bold';
            $html .= ';font-size:'.$this->percent_size.'px;color:'.$this->percent_color.';z-index:1;text-align:center;">0%</div>';

            //text div
            $txttop = $this->top+1+$this->height+1;
            $html .= '<div id="progrbartxt" style="position:relative;top:-'.($this->height).'px';
            $html .= ';left:0px';
            $html .= ';width: 100%';
            $html .= ';text-align:center';
            $html .= ';font-size:'.$this->txt_size.'px;color:'.$this->txt_color.';z-index:1;text-align:center;">loading</div>';

            $html .= '</div>';
            $this->buffer->append( $html );
            $this->buffer->flush();
        }

        function SetTimeOut($timeout)
        {
            set_time_limit($timeout); //need safe mode off !
        }

        function SetPercent($percent, $text = "")
        {
            $this->buffer->append( '<script type="text/javascript">'
                . 'document.getElementById("percent").innerHTML="'.$percent.'%";'
                . 'document.getElementById("progrbar").style.width="'.($percent * 2).'";'
                . (!empty($text)
                    ? 'document.getElementById("progrbartxt").innerHTML="'.$text.'";'
                    :'')
                . '</script>'
                . "\n" );
                
            $this->buffer->flush();
        }

        function End($hide = true, $text = 'loaded !')
        {
            $this->buffer->append( '<script type="text/javascript">'
                . 'document.getElementById("percent").innerHTML="100%";'
                . 'document.getElementById("progrbar").style.width="200";'
                . 'document.getElementById("progrbartxt").innerHTML="'.$text.'";'
                . '</script>'. "\n" );

            $this->buffer->flush();
            
            if (!empty($hide))
                $this->Hide();
        }
    }

