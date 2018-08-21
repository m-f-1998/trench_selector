<?php
/* Pop Up To Select Accessories
 *
 * @author Matthew Frankland
 * @package wp-admin
 * @version 1.0
*/

add_action(//tag//, //function_to_add//);

function tool_menu(){
    add_menu_page(//page_title//, //menu_title//, //capability//, //menu_slig//, //function//);
}

function trench_settings_page() { ?>

    <!–– MARK: AJAX PROCESSING OF CSV FILE -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        jQuery(function () {

            /* RESET FORM ON BACK-BUTTON PRESS */
            $(window).on('popstate', function(event) {
                $('form').find('input[type=file]').attr('disabled',false);
                $('form')[0].reset();
            });

            /* ONLY SUBMIT ONE FILE AT A TIME */
            $('input[type=file]').change(function (){
                $('form').find('input[type=file]').attr('disabled',true);
            });

            $('form').on('submit', function (e) {
                e.preventDefault();

                /* CHECK CSV PROVIDED */
                var inputsTestedEmpty = 0;
                $('form').find('input[type=file]').each(function(index, inputObject) {

                    if(typeof(inputObject.files[0]) == "undefined") {

                        if (inputsTestedEmpty == 8) { //ALL INPUTS CHECKED
                            /* ALERT USER TO NO FILE SUBMISSION */
                            $("#result").empty(); $("#result").append("<br />Please Upload File Before Submission");
                            return false;
                        }
                        inputsTestedEmpty++;
                        return;

                    }

                    var fileName = inputObject.name;
                    $("#result").empty(); $("#result").append("<br />Parsing CSV File...");
                    var csvFile = new FormData();
                    csvFile.append('name', fileName);
                    csvFile.append('csv', $("input[name='" + fileName + "']")[0].files[0]); // PARSE CSV IN BACK-END
                    csvFile.append('nonce', $("#uploadCSV").attr("data-nonce"));

                    $.ajax({
                        type: 'POST',
                        url: // url //,
                        data: csvFile,
                        processData: false,
                        success: function (data) {

                            /* ALERT USER TO SUCCESS - MESSAGE ECHOED FROM PHP*/
                            $('#result').empty(); $("#result").append(data);
                            $('form').find('input[type=file]').attr('disabled',false); $('form')[0].reset(); // RESET FILE INPUTS
                            return false; // DONT'T RELOAD PAGE

                    }});

                });
            });
        });
    </script>

<!–– MARK: FRONT-END HTML -->

<?php } ?>
