<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    
    // init reporter for services
    $reporter = new HTML_Reporter;
    $reporter->setExpandMessage( 'show/hide results' );
    $reporter->hideDetailsByDefault();
    
    $profiler = $GLOBALS['profiler'];
    
    $tpl = '<h1>%starName%</h1>' . "\n"
        . '<ul>' . "\n"
        . '<li>Sytem Name: %sysName%</li>' . "\n"
        . '<li>Constellation: %constellation%</li>' . "\n"
        . '<li>Star Class: '
        . Javascript_Popup_Helper::popupLink(
            $_SERVER['PHP_SELF'] . '?page=help&amp;about=starclass'
            , '%class%', 'Spectral Type', 400, 600 )
        . '</li>' . "\n"
        . '<li>Distance from Sol: %dist% ('
        . Javascript_Popup_Helper::popupLink(
            $_SERVER['PHP_SELF'] . '?page=help&amp;about=units'
            , 'ly', 'Units', 400, 600 )
        . ')</li>' . "\n"
        . '<li>Position: %position%</li>' . "\n"
        . '</ul>' . "\n"
        . '<h2>Notes</h2>' . "\n"
        . '<p>%notes%</p>' . "\n"
        ;
        
    $template = new HTML_Template( $tpl );
    
    $output = '';
    
    $selectedId = array_key_exists( 'starList', $_REQUEST )
        ? (int) $_REQUEST['starList']
        : 0
        ;
        
    $starName = array_key_exists( 'starName', $_REQUEST )
        ? $_REQUEST['starName']
        : 'Sol'
        ;
    
    $fileList = array(
        'data/25LY-H.LST.gz',
        'data/50LY-H.LST.gz',
        'data/100LY-H.LST.gz',
        'data/150LY-H.LST.gz',
        'data/250LY.LST.gz'
    );
    
    $file = $fileList[$selectedId];
    
    $output .= "<p><small>Loading $file...</small></p>\n";
    
    $starList = new StarList;
    $profiler->mark( 'before load' );
    $starList->loadFile( $file, true );
    $profiler->mark( 'after load' );
    $nbrStar = $starList->size();
    $summary = "$file loaded, $nbrStar stars found\n";
    $details = '';
    $output .= $reporter->render( $summary, $details );
    
    $output .= "<p><small>Searching for $starName in $file...</small></p>\n";
    
    $profiler->mark( 'before search star' );
    
    $star = $starList->starExists( $starName )
        ? $starList->getStar( $starName )
        : null
        ;
    
    $profiler->mark( 'after loading star' );
    
    if ( ! is_null( $star ) )
    {
        if ( empty( $star['sysName'] ) )
        {
            $star['sysName'] = $star['starName'];
        }
        
        if ( empty( $star['constellation'] ) )
        {
            $star['constellation'] = '-none-';
        }
        
        $star['notes'] = str_replace( ';', '<br />', $star['notes'] );
        $star['notes'] = str_replace( 'FlareStar'
            , Javascript_Popup_Helper::popupLink(
                $_SERVER['PHP_SELF'] . '?page=help&amp;about=flare'
                , 'FlareStar', 'Flare Star', 400, 600 )
            , $star['notes'] );

        $output .= $template->render( $star );
    }
    else
    {
        $output .= HTML_MessageBox::Error( "Star $starName not found\n" );
    }
    
    $this->setOutput( $output );
