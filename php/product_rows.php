<?php
    /*
     * Generate Results Table for Trench-Heating Calculator
     *
     * @author Matthew Frankland
     * @package Turnbull
     * @version 1.0
     */

    $queryProducts;

    /* GET HEATING/COOLING OUTPUT BY APPLICABLE PRODUCT ID */
    function getOutputs($product, $outputID, $waterTemp) {
        global $wpdb; //DATABASE

        $heatOutput = "0"; $coolOutput = "N/A";
        $middleTempOfWater = $waterTemp; $middleTempOfAir = 20;

        if ($outputID == "natural" || $outputID == "fan-Assisted") {

            $prepare = $wpdb->prepare("SELECT `heatOutputConstOne`, `heatOutputConstTwo`, `heatOutputConstThree` FROM `wp_trench_heat_constants` WHERE productID = %s", $product->productID);
            $heatConstants = $wpdb->get_row($prepare);

            /* HEAT OUTPUT CALCULATION - INDUSTRY STANDARD */
            $heatOutput = number_format((
                (
                    (float)$heatConstants->heatOutputConstOne *
                    pow(($middleTempOfWater - $middleTempOfAir), (float) $heatConstants->heatOutputConstTwo)
                ) * (
                    ($product->length - 315) / ((float)$heatConstants->heatOutputConstThree - 315)
                )/1000), 2, '.', '');

        } else if ($outputID == "electric"){

            $prepare = $wpdb->prepare("SELECT `heatOutput` FROM `wp_trench_elec_output` WHERE productID = %s", $product->productID);
            $trenchOutput = $wpdb->get_row($prepare);

            $heatOutput = number_format($trenchOutput->heatOutput, 2, '.', '');

        } else if ($outputID == "heating") {

            $prepare = $wpdb->prepare("SELECT `heatOutput`, `coolOutput` FROM `wp_trench_hc_output` WHERE productID = %s", $product->productID);
            $trenchOutput = $wpdb->get_row($prepare);

            $heatOutput = number_format($trenchOutput->heatOutput, 2, '.', '');
            $coolOutput = number_format($trenchOutput->coolOutput, 2, '.', '');

        } else {

            die("Unable to Retrieve Outputs of Trench-Products");

        }

        return [$heatOutput, $coolOutput];

    }

    /* PRINT RESULTS TABLE HEADER */
    function printHeader() {
        //HEADER
    }

    /* PRINT RESULTS TABLE BODY */
    function printBody($product, $productID, $outputs) {
        //BODY
    }

    /* PRINT RESULTS TABLE FOOTER */
    function printFooter($product, $outputs, $productIndex, $indexCount) {

        echo "<th><input type='number' name='countQuantity' class='quantity' step='1' min='1' onchange='updateTally(this, ".$product->basePrice.", ".$outputs[0].", ".$product->length.", ".$productIndex.", ".$indexCount."); checkTarget(); linkClicked(true);'></th></tr>";

    }

    /* RETURN EACH PRODUCT CELL PER ROW IN DATABASE */
    function generateProductCells($waterTemp) {
        global $wpdb; //DATABASE

        $query = "SELECT * FROM wp_trench_product WHERE 1 ORDER BY FIELD (`wp_trench_product`.`modelName`, 'Natural Convection', 'Fan-Assisted Convection', 'Electric', 'Heating and Cooling')";
        $queryResult = $wpdb->get_results($query);

        $originalProductID = lcfirst(explode(' ',trim($queryResult[0]->modelName))[0]);
        $productIndex = 0; $indexCount = 0;
        $productIndex = 0; $indexCount = 0;

        echo printHeader();

        foreach ($queryResult as $product) { // RUN QUERY FOR PRODUCTS
            $productID = lcfirst(explode(' ',trim($product->modelName))[0]); // GET FIRST WORD OF OUTPUT AS ID FOR PRODUCT ROW
            $outputs = getOutputs($product,$productID, $waterTemp); // GET OUTPUTS OF PRODUCTS

            if ($originalProductID != $productID) {

                $originalProductID = $productID;
                $indexCount = 1; $productIndex++;

            } else {

                $indexCount++;

            }

            echo printBody($product, $productID, $outputs);
            echo printFooter($product, $outputs, $productIndex, $indexCount);
        }

        echo "</tbody>";

    }

    if(isset($_GET['waterTemp'])){

        /* MARK: WORDPRESS ENVIROMENT */
        require( '../../../../../wp-load.php' );
        return(generateProductCells($_GET['waterTemp'], ''));

    }

?>
