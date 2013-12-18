<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:

// {{{ INIT
{
    // core libraries
    require_once dirname(__FILE__) . '/lib/classloader.class.php';

    $classLoader = new classLoader;
    $classLoader->register();
    
    // global variables
    $rootScript = $_SERVER['PHP_SELF'];
    $imageRepositoryWeb = dirname($_SERVER['PHP_SELF']).'/img';
    $imageRepositorySys = dirname(__FILE__).'/img';    
    $javascriptRepositoryWeb = './js';
    $cssRepositoryWeb = './css'; 
    $helpDir = dirname(__FILE__) . '/help';
}
// }}}
// {{{ MODEL
{
    // instanciate dispatcher and bind services 
    $dispatcher = Dispatcher::getInstance();
    $dispatcher->setDefault( new Service_BufferedScript('./services/test.svc.php') );
    $dispatcher->bind( 'test', new Service_BufferedScript('./services/test.svc.php') );
    $dispatcher->bind( 'list', new Service_BufferedScript('./services/list.svc.php') );
    $dispatcher->bind( 'star', new Service_Script('./services/star.svc.php') );
    $dispatcher->bind( 'help', new Service_Script('./services/help.svc.php') );
    
    $starList = WebApplication::getInstance( 'StarList' );
    
    $starList->imageRepositoryWeb = dirname($_SERVER['PHP_SELF']).'/img';
    $starList->imageRepositorySys = dirname(__FILE__).'/img';
    $starList->javascriptRepositoryWeb = './js';
    $starList->cssRepositoryWeb = './css';
    $starList->helpDir = dirname(__FILE__) . '/help';
    
    // init reporter for debugging
    $debugReporter = new HTML_Reporter;
    $debugReporter->setExpandMessage( 'show/hide profiling' );
    $debugReporter->hideDetailsByDefault();

    $starList->debugReporter = $debugReporter;
    
    // init reporter for services
    $reporter = new HTML_Reporter;
    $reporter->setExpandMessage( 'show/hide results' );
    $reporter->hideDetailsByDefault();
    
    $starList->reporter = $reporter;
    
    // init profiler
    $profiler = new Profiler;
    $profiler->start();
    
    $starList->profiler = $profiler;
}
// {{{ CONTROLLER
{
    // set dispatcher requested service identifier
    $requestedService = isset( $_REQUEST['page'] )
        ? $_REQUEST['page']
        : null
        ;
        
    $noHeader = isset( $_REQUEST['noHeader'] ) && $_REQUEST['noHeader'] === 'true'
        ? true
        : false
        ;
        
    $noFooter = isset( $_REQUEST['noFooter'] ) && $_REQUEST['noFooter'] === 'true'
        ? true
        : false
        ;
}
// }}}
// {{{ VIEW
{
    if ( false === $noHeader )
    {
        include 'templates/header.thtml';
    }
    
    if ( $dispatcher->hasError() )
    {
        echo HTML_MessageBox::FatalError( $dispatcher->getError() );
    }
    else
    {
        // serve requested page
        $svc = is_null( $requestedService )
            ? $dispatcher->serveDefault()
            : $dispatcher->serve( $requestedService )
            ;

        if ( $svc->hasError() )
        {
            echo HTML_MessageBox::FatalError( $dispatcher->getError() );
        }
        else
        {
            echo $svc->getOutput();
        }
    }
    
    if ( false === $noFooter )
    {
        include 'templates/footer.thtml';
    }
}
// }}}

