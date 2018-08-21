<?php
     /* Present Instructions And Inputs at Start of Calculator
     *
     * @author Matthew Frankland
     * @package Turnbull
     * @version 1.0
     */

    /* USE PREVIOUSLY CREATED TEMPLATE CATEGORY ENQUIRY FOR LEFT/RIGHT EFFECT */
?>

<div class="container container-category-enquiry">
	<div class="row">
		<section>
			<div class="row">

                <!-- INSTRUCTIONS TO USE TRENCH-HEATING CALCULATOR -->
				<div class="small-12 medium-6 columns">
					<div class="text-section-left">

						<b>To Start Using the Calculator Follow These Steps:<br /><br /></b>
                        
                        <ol>
                            <li>If you wish to set a particular target, enter your 'Maximum Heat Output' and/or the 'Total Length Available' in the relevant input boxes. If no target is required leave input boxes blank, then click continue.</li>
                            
                            <li>You can browse all our available Trench-Heating Products by using the table below.</li>
                            
                            <li>Once you have identified an appropriate product, enter the quantity required. Once all products have been selected, click to add custom features and specifications....</li>
                            
                            <li>A pop up box showing available customisations and accessories will appear. Complete as required. A total estimated price (including additions) is also available.</li>
                            
                            <li>A final specification can be downloaded, outlining your selected products. To further discuss your requirements please email or phone.</li>
                            
                        </ol>
					</div>
				</div>

                <!-- OUTPUT TARGETS FOR ENTERING POP-UP -->
				<div class="small-12 medium-6 columns">
					<div class="text-section-right">
                        <form id="targetForm">
                            
                            <label>
                                <b>Total Heat Output Required for Project Space (kW):<br /><br /></b>
                            </label>
                            
                            <input id="targetHeat" min="0" name="heatOutput" step="1" type="number" onchange="storeTarget(this);"/>
                            
                            <label>
                                <b>Total Maximum Length Required for Project Space (mm):<br /><br /></b>
                            </label>
                            
                            <input id="maxLength" min="0" name="totalLength" step="1" type="number" onchange="storeTarget(this);"/>

                            <p id="notifications" style="font-weight: bold; font-style: italic;">Continue Without Target</p>

                            <a id="continue" href="#resultOverlay">Proceed...</a>
                            
                        </form>
					</div>
				</div>	
                
			</div>
		</section>
	</div>
</div>
