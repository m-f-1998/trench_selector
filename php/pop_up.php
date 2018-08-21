<?php
     /* Pop Up To Select Accessories
     *
     * @author Matthew Frankland
     * @package Turnbull
     * @version 1.0
     */

?>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- TITLE -->
            <div class="modal-header">
                <a class="close" data-dismiss="modal" href="#" onclick="">Close</a>
                <h4 class="modal-title">Customise Product</h4>
            </div>

            <!-- CUSTOMISATIONS -->
            <div class="modal-body">

                <!-- TROUGH MATERICAL -->
                <div class="filterCustomise">
                    <p>Trough Material: <i>{Included In Base Price}</i></p>
                    <span class="custom-dropdown">
                        <select name="troughMaterial" onchange="updateTroughMaterial(this, this.options[this.selectedIndex].getAttribute('percent'));" id="trough">
                            <?php echo getCustomisationOptions("troughMaterial"); ?>
                        </select>
                    </span>
                </div>

                <!-- POSITION OF WATER -->
                <div class="filterCustomise">
                    <p>Position of Water Connectors: <i>{Included In Base Price}</i></p>
                    <span class="custom-dropdown" id="selectOptional">
                        <select name="water" onchange="updateWaterConnection(this, this.options[this.selectedIndex].getAttribute('percent'))" id="water">
                            <?php echo getCustomisationOptions("waterConnection"); ?>
                        </select>
                    </span>
                </div>

                <!-- GRILLE -->
                <div class="filterCustomise">
                    <p>Grille:</p>
                    <span class="custom-dropdown" id="selectOptional">
                        <select name="grille" onchange="updateGrille(this)" id="grille">
                            <?php echo getCustomisationOptions("grille"); ?>
                        </select>
                    </span>
                </div>

                <!-- ALTERNATE SPEC -->
                <div class="filterCustomise">
                    <p>For Any Alternate Specifications Please Contact <a style="text-align: center; width: 100%;" href="tel:+441450372053">01450 372053</a></p>
                </div>

                <!-- ACCESSORIES -->
                <div class="modal-header">
                <h4 class="modal-title">You may wish to include these Standard Trench Accessories in your Specification Document:</h4>
                </div>

                <br /><select name="access" id="access" placeholder="Click to Add Accessories..." onchange="storeAccess(this)" multiple></select>

                <!-- DOWNLOAD FINAL SPECIFICATION -->
                <div class="modal-header">
                    <h4 class="modal-title">Final Specification</h4>
                </div>

                <div class="modal-body">

                    <p id="finalBasePrice" class="modalFooter">Base Price: &#163;0.00<br />
                    <p id="additionalCosts" class="modalFooter">Price of Grille: &#163;0.00</p>
                    <p id="loading" class="modalFooter"><i>Cost of Grille Loading</i></p>
                    <p id="finalEstimatedPrice" class="modalFooter">Estimated Price: &#163;0.00</p><br /><br />

                    <a href="#" class="modalFooter" onclick="downloadPDF(enterProjectID(), 'download');">
                        Download A Specification
                    </a><br />

                    <a href="#" class="modalFooter" onclick="downloadPDF('', 'email');">
                        Contact Us Via E-Mail
                    </a><br />

                    <a href="tel:+441450372053" class="modalFooter">
                        Contact Us Via Phone
                    </a><br />

                </div>

            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo(//STYLESHEET URL); ?>/trench_calculator/choices/choices.min.css" async>
<script type="text/javascript" src="<?php echo(//STYLESHEET URL); ?>/trench_calculator/choices/choices.min.js" async></script>
