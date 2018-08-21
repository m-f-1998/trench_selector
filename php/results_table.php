<?php
     /* Filters for Restricting Results Table
     *
     * @author Matthew Frankland
     * @package Turnbull
     * @version 1.0
     */
?>

<script>
    /* SET ZERO */
    function setZero() { // eslint-disable-line no-unused-vars
        'use strict';
        
        sessionStorage.setItem("totalLength", 0);
        sessionStorage.setItem("heatOutput", 0);
        
    }
    window.onload = setZero;
</script>

<div class="container container-fw-cta-image" onload="setZero()">
    <div class="bg-black"></div>
    <div class="row">
        <section>
            <div class="fw-text-wrapper">
                <div class="fw-text">
                    <div id="resultOverlay">

                        <!-- FILTERS ROW ONE -->
                        <div class="filterRow">

                            <div class="filterBlock">
                                <p>Product Type:</p>
                                <select class="filter" name="productModel" id="6" onchange="filterCells(this, this.name)">
                                    <option selected value="--"> Select Value... </option>
                                    <option value="natural">Natural Convection</option>
                                    <option value="fan-Assisted">Fan-Assisted</option>
                                    <option value="electric">Electric</option>
                                    <option value="heating">Heating and Cooling</option>
                                </select>
                            </div>

                            <div class="filterBlock">
                                <p>Mean Hot Water Temp (&#176;C):**</p>
                                <select class="filter" name="hotWater" onchange="adjustWaterTemp(this.value, this)">
                                    <option selected value="70">70</option>
                                    <option value="40">40</option>
                                </select>
                            </div>
                            
                            <div class="filterBlock">
                                <p>Cooling Output/unit (kW):*</p>
                                <select class="filter" name="coolingOutput" id="1" onchange="filterCells(this, this.name)">
                                    <?php echo getFilterOptions("output"); ?>
                                </select>
                            </div>
                            
                            <div class="filterBlock">
                                <i>Mean Cold Water Temperature<br /> Calculated at 9&#176;C</i>
                            </div>
                            
                        </div>
                        
                        <!-- FILTERS ROW TWO -->
                        <div class="filterRow">
                            
                            <div class="filterBlock">
                                <p>Required Length/unit (mm):***</p>
                                <select class="filter" name="length" id="3" onchange="filterCells(this, this.name)">
                                    <?php echo getFilterOptions("length"); ?>
                                </select>
                            </div>
                            
                            <div class="filterBlock">
                                <p>Max Depth/unit (mm):***</p>
                                <select class="filter" name="depth" id="4" onchange="filterCells(this, this.name)">
                                    <?php echo getFilterOptions("depth"); ?>
                                </select>
                            </div>
                            
                            <div class="filterBlock">
                                <p>Max Width/unit (mm):***</p>
                                <select class="filter" name="width" id="5" onchange="filterCells(this, this.name)">
                                    <?php echo getFilterOptions("width"); ?>
                                </select>
                            </div>
                            
                            <div class="filterBlock">
                                <p>Heat Output/unit (kW)*:</p>
                                <select class="filter" name="heatOutput" id="0" onchange="filterCells(this, this.name)">
                                    <?php echo getFilterOptions("output"); ?>
                                </select>
                            </div>
                            
                        </div>
                                                        
                        <!-- TALLY -->
                        <table id="accumTable">
                            <tr>
                                <th class="accumulation" id="price">Total Price:<br /><b>&#163;0.00</b></th>
                                <th class="accumulation" id="output">Total Output:<br /><b>0 kW</b></th>
                                <th class="accumulation" id="length">Total Length:<br /><b>0 mm</b></th>
                                <th class="accumulation" id="selected">Total Units:<br /><b>0</b></th>
                                <th class="accumulation">
                                    <a href="#" id="refresh" onclick="return refresh();">
                                        Start Again
                                    </a><br />
                                </th>
                            </tr>
                        </table>
                        <br />
                        
                        <!-- RESULT TABLE -->
                        <div class="resultTable">
                            <table id="tableAllResults" style="width: 100%;">
                                <?php echo(generateProductCells(70)); ?>
                            </table>
                        </div>
                        
                        <!-- WHEN RESETTING NOTIFICATION TO DIFFERENT OUTPUT -->
                        <br /><p id="outputNotification"></p>
                            
                        <!-- NO ENTERING 0 OR NEGATIVE -->
                        <script>
                        jQuery(".quantity, #targetHeat, #maxLength").keypress(function (e) {
                            if (this.value.length === 0 && (e.which === 45 || e.which === 48)) {
                                return false;
                            }
                        });
                        </script>
                        
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
