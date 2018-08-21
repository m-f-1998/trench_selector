<?php
     /* Generate Options for Filters
     *
     * @author Matthew Frankland
     * @package Turnbull
     * @version 1.0
     */

    /* GET FILTER OPTIONS FOR CHANGING RESULTS TABLE */
    function getFilterOptions($id) {
        global $wpdb;
        
        $prepared = $wpdb->prepare("SELECT * FROM `wp_trench_filter_options` WHERE compatOption = '%s'", $id);
        $result = $wpdb->get_results($prepared);

        echo "<option selected value='--'> Select Value... </option>";
        foreach ($result as &$optionResult) { // RUN QUERY FOR OPTIONS
            
            echo "<option value='".trim($optionResult->optionID)."'>".$optionResult->optionText."</option>";
            
        }
        
    }

    /* GET CUSTOMISATION OPTIONS FOR POP-UP */
    function getCustomisationOptions($id) {
        global $wpdb;
        
        $prepared = $wpdb->prepare("SELECT * FROM `wp_trench_cust_options` WHERE custID = '%s'", $id);
        $result = $wpdb->get_results($prepared);
        
        foreach ($result as &$optionResult) { // RUN QUERY FOR OPTIONS
            
            echo "<option value='".trim($optionResult->optionName)."' percent='".trim($optionResult->pricePercentage)."'>".$optionResult->optionText."</option>";
            
        }
        
    }

    /* GET ACCESSORIES BASED ON COMPATABILITY */
    function getAccessories($compatString) {
        global $wpdb;
        
        $prepared = $wpdb->prepare("SELECT * FROM `wp_trench_access` WHERE compatAccess LIKE '%%%s%%'", $compatString);
        $result = $wpdb->get_results($prepared);
        
        foreach ($result as &$optionResult) { // RUN QUERY FOR OPTIONS
                        
            echo "<option>",trim($optionResult->accessName),"</option>";
            
        }
        
    }

    /* IF INDEX OF COMPATABLE ACCESSORIES PASSED RETURN VALID ACCESSORIES */
    if(isset($_GET['compatIndexes'])){

        /* MARK: WORDPRESS ENVIROMENT */
        require( '../../../../../wp-load.php' );
        return(getAccessories($_GET['compatIndexes']));

    }

?>
