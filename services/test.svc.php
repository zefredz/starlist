<?php // $Id$

    // vim: expandtab sw=4 ts=4 sts=4:
    /*
    $pb = new HTML_ProgressBar( $this->output );
    $pb->setPosition(5, 5, 200, 15);
    $pb->setSizes(12, 12);
    $pb->setTimeout(60);

    $pbl = new HTML_ProgressBar_Listener( $pb );

    Event_Manager::addListener( $pbl->progressEvent(), $pbl );
    Event_Manager::addListener( $pbl->finishedEvent(), $pbl );
    */
    $starList = WebApplication::getInstance();
    
    $output = '';
    
    $selectedId = array_key_exists( 'starList', $_REQUEST )
        ? (int) $_REQUEST['starList']
        : 0
        ;
    
    $fileList = array(
        'data/25LY-H.LST.gz',
        'data/50LY-H.LST.gz',
        'data/100LY-H.LST.gz',
        'data/150LY-H.LST.gz',
        'data/250LY.LST.gz'
    );

    ob_start();
    include 'templates/fileselector.thtml';
    $contents = ob_get_contents();
    ob_end_clean();
    
    // $output .= $contents;
    $this->output->append( $contents );
    $this->output->flush();
    
    $table = new HTML_Datagrid_Table;
    $dataFields = array(
        // 'sysName' => 'System Name',
        'starName' => 'Star Name',
        'dist' => 'Distance from Sun ('
            . Javascript_Popup_Helper::popupLink(
                $_SERVER['PHP_SELF'] . '?page=help&amp;about=units'
                , 'ly', 'Units', 400, 600 )
            . ')',
        'class' => 'Star Class',
        'mass' => 'Mass',
        'constellation' => 'Constellation',
        'position' => 'Position from sun ('
            . Javascript_Popup_Helper::popupLink(
                $_SERVER['PHP_SELF'] . '?page=help&amp;about=units'
                , 'ly', 'Units', 400, 600 )
            . ')',
        // 'notes' => 'Remarks',
    );
            
    $table->setDataFields( $dataFields );
    
    $dataUrls = array(
        'starName' => '<a href="'
            . $_SERVER['PHP_SELF']
            . '?page=star&amp;starList=' . (int) $selectedId
            . '&amp;starName=%uu(starName)%'
            . '">%html(starName)%'
            . '</a>',
        'class' => Javascript_Popup_Helper::popupLink(
            $_SERVER['PHP_SELF'] . '?page=help&amp;about=starclass'
            , '%html(class)%', 'Spectral Type', 400, 600 )
    );
    
    $table->setDataUrls( $dataUrls );
    
    $file = $fileList[$selectedId];
    
    $this->output->append( "<p><small>Loading $file...</small></p>\n" );
    $this->output->flush();
    
    $slist = new StarList;
    $starList->profiler->mark( 'before load' );
    $slist->loadFile( $file, true );
    
    $starList->profiler->mark( 'after load' );
    /*Event_Manager::notify( new HTML_ProgressBar_EventFinished( true ) );*/
    $nbrStar = $slist->size();
    $summary = "$file loaded, $nbrStar stars found\n";
    $details = '';
    $this->output->append(  $starList->reporter->render( $summary, $details ) );
    $this->output->flush();
    
    $this->output->append(  "<p><small>Searching $file...</small></p>\n" );
    $this->output->flush();
    
    $starList->profiler->mark( 'before search G type' );
    $search = new StarList_SearchEngine;
    $search->addCondition( array( 
        'type' => 'REGEXP'
        , 'criterium' => '/G/i'
        , 'key' => 'class'
        , 'operator' => 'OR' )
    );
    
    $gTypeStars = $search->search( $slist );
    $starList->profiler->mark( 'after search' );
    $nbrGType = $gTypeStars->size();
    $summary = "Found $nbrGType stars with spectral type G\n";
    $table->setData($gTypeStars->toArray());
    $details = $table->render();
    $this->output->append(  $starList->reporter->render( $summary, $details ) );
    $this->output->flush();
    
    $starList->profiler->mark( 'before search G type with dist <= 10 Ly' );
    $search = new StarList_SearchEngine;
    $search->addCondition( array( 
        'type' => 'DIST'
        , 'criterium' => array( 'dist' => 10, 'operator' => '<=' )
        , 'operator' => 'OR' )
    );
    $search->addCondition( array( 
        'type' => 'REGEXP'
        , 'criterium' => '/G/i'
        , 'key' => 'class'
        , 'operator' => 'AND' )
    );
    $gTypeStars = $search->search( $slist );
    $starList->profiler->mark( 'after search' );
    $nbrGType = $gTypeStars->size();
    $summary = "Found $nbrGType stars with spectral type G distant of less than 10Ly from sun\n";
    $table->setData($gTypeStars->toArray());
    $details = $table->render();
    $this->output->append(  $starList->reporter->render( $summary, $details ) );
    $this->output->flush();
    
    
    $starList->profiler->mark( 'before search Flare stars' );
    $search = new StarList_SearchEngine;
    $search->addCondition( array( 
        'type' => 'CRITERIUM'
        , 'criterium' => 'Flare'
        , 'key' => 'notes'
        , 'operator' => 'OR' )
    );
    $flareStars = $search->search($slist);
    $starList->profiler->mark( 'after search' );
    $nbrFlare = $flareStars->size();
    $summary = "Found $nbrFlare flare stars\n";
    $table->setData($flareStars->toArray());
    $details = $table->render();
    $this->output->append(  $starList->reporter->render( $summary, $details ) );
    $this->output->flush();
    
    $starList->profiler->mark( 'before search Mu Dra' );
    $search = new StarList_SearchEngine;
    $search->addCondition( array( 
        'type' => 'CRITERIUM'
        , 'criterium' => 'mu dra'
        , 'key' => array( 'sysName', 'starName', 'notes' )
        , 'operator' => 'OR' )
    );
    $muDrac = $search->search( $slist );
    $starList->profiler->mark( 'after search' );
    $nbrMuDrac = $muDrac->size();
    $summary = "Found $nbrMuDrac matching mu dra\n";
    $table->setData($muDrac->toArray());
    $details = $table->render();
    $this->output->append(  $starList->reporter->render( $summary, $details ) );
    $this->output->flush();
    
    $starList->profiler->mark( 'before search Groombridge 1618' );
    $search = new StarList_SearchEngine;
    $search->addCondition( array(
        'type' => 'CRITERIUM'
        , 'criterium' => 'groombridge 1618'
        , 'key' => array( 'sysName', 'starName', 'notes' )
        , 'operator' => 'OR' )
    );
    $gb1618 = $search->search( $slist );
    $starList->profiler->mark( 'after search' );
    $nbrGb1618 = $gb1618->size();
    $summary = "Found $nbrGb1618 matching Groombridge 1618\n";
    $table->setData($gb1618->toArray());
    $details = $table->render();
    $this->output->append(  $starList->reporter->render( $summary, $details ) );
    $this->output->flush();
    
    $dist = 10;
    $comparator = '<=';
    $starList->profiler->mark( 'before search <= 10 Ly' );
    $search = new StarList_SearchEngine;
    $search->addCondition( array( 
        'type' => 'DIST'
        , 'criterium' => array( 'dist' => $dist, 'operator' => $comparator )
        , 'operator' => 'OR' )
    );
    $tenLy = $search->search( $slist );
    $starList->profiler->mark( 'after search' );
    $nbrTenLy = $tenLy->size();
    $summary = "Found $nbrTenLy stars whith dist $comparator $dist from sun\n";
    $table->setData($tenLy->toArray());
    $details = $table->render();
    $this->output->append(  $starList->reporter->render( $summary, $details ) );
    $this->output->flush();
    
    // $this->setOutput( $output );
