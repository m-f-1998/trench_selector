<?php
     /* Return Price of Grille
     *
     * @author Matthew Frankland
     * @package Turnbull
     * @version 1.0
     */

    /* RETURN THE PRICE FOR SPECIFIED GRILLE FILTERS */
    function getGrillePrice($grilleLength, $grilleID, $electricFlag) {
        global $wpdb; //DATABASE
        
        $prepared = $wpdb->prepare("SELECT grillePrice FROM wp_trench_grille_prices WHERE grilleLength = %s AND grilleID = %s AND grilleElectric = %s", $grilleLength, $grilleID, $electricFlag);
        $result = $wpdb->get_row($prepared);
        
        return $result->grillePrice;
    }

    /* IF GRILLE FILTERS SET RETRIEVE PRICE */
    if(isset($_GET['grilleLength']) && isset($_GET['grilleID']) && isset($_GET['electricFlag'])){
        
        /* MARK: WORDPRESS ENVIROMENT */
        require( '../../../../../wp-load.php' );
        echo(getGrillePrice($_GET['grilleLength'], $_GET['grilleID'], $_GET['electricFlag']));
            
    }


?>
