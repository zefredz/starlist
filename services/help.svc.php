<?php  // $Id: help.svc.php,v 1.1 2006/12/21 09:48:33 zefredz Exp $
    
    // vim: expandtab sw=4 ts=4 sts=4:

if ( count( get_included_files() ) == 1 ) die( '---' );

// {{{ SCRIPT INITIALISATION
{ 
    // success dialog
    $dispSuccess            = false;
    $successMsg             = '';
    
    // error dialog
    $dispError              = false; // display error box
    $fatalError             = false; // if set to true, the script ends after 
                                     // displaying the error
    $errorMsg = '';                  // error message to display
    $dispErrorBoxBackButton = false; // display back button on error
    $err                    = '';    // error string 
}
// }}}
// {{{ MODEL
{ 
    require_once dirname(__FILE__) . '/../lib/html/popup/helper.class.php';
    
    $starList = WebApplication::getInstance();
}
// }}}
// {{{ CONTROLLER
{ 
    $about = isset( $_REQUEST['about'] )
        ? trim( $_REQUEST['about'] )
        : 'about'
        ;
        
    // $helpPath = $GLOBALS['helpDir'] . '/' . $about . '.hlp.thtml';
    $helpPath = $starList->helpDir . '/' . $about . '.hlp.thtml';
        
    if ( file_exists( $helpPath ) )
    {
        $content = file_get_contents( $helpPath );
    }
    else
    {
        $dispError = true;
        $fatalError = true;
        $errorMsg = 'No help found';
    }
}
// }}}
// {{{ VIEW
{
    $output = HTML_Popup_Helper::windowClose();
    
    if ( true == $dispError )
    {
        // display error
        $errorMessage =  '<h2>'
            . ( ( true == $fatalError ) 
                ? 'Error (Fatal)'
                : 'Error' )
            . '</h2>'
            . "\n"
            ;
        
        $errorMessage .= '<p>'
            . htmlspecialchars($errorMsg) . '</p>' 
            . "\n"
            ;
        
        if ( true === $fatalError )
        {
            $output .= HTML_MessageBox::FatalError( $errorMessage );
        }
        else
        {
            $output .= HTML_MessageBox::Error( $errorMessage );
        }
    }
    
    if ( true === $dispSuccess )
    {
        // display error
        $successMessage =  '<h2>'
            . get_lang( 'Success' )
            . '</h2>'
            . "\n"
            ;
        
        $successMessage .= '<p>'
            . htmlspecialchars($successMsg) . '</p>' 
            . "\n"
            ;
            
        $output .= HTML_MessageBox::Success( $successMessage );
    }
    
    // no fatal error
    if ( true != $fatalError )
    {
        $output .= $content;
    }
    else
    {
        // nothing to do
    }
    
    $output .= HTML_Popup_Helper::windowClose();
    
    $this->setOutput( $output );
}
// }}}
