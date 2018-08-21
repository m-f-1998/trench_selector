<?php
/*
 * Process Updates To Trench Heating Database
 *
 * @author Matthew Frankland
 * @package wp-admin
 */

/* MARK: WORDPRESS ENVIROMENT */
define( 'SHORTINIT', true );
require( '../../../../../wp-load.php' );

/* FORMAT TO UTF8 STRING */
function utf8_string_refine($text) {

    $bom = pack('H*','EFBBBF');
    $text = preg_replace("/^$bom/", '', $text);
    $text = str_replace(array("\r", "\n"), '', $text);
    return $text;

}

/* IF FILE NAME IS CORRECT FOR INPUT => INSERTED TO CORRECT TABLE */
function check_file_name($name, $fileHeader) {

    $name = utf8_string_refine($name);
    $fileNameHeader = utf8_string_refine($fileHeader);
    $fileNameHeader = substr($fileNameHeader, 54, -1);

    if ("wp_".$name.".csv" != $fileNameHeader) { exit("<br />Wrong File Uploaded For This Database Table"); }

}

/* CHECK THAT COLUMN TITLES ARE NOT MALFORMED */
function check_titles($fileTitles) {

    $encodedChecks = array (
      #databaseTables
    );

    $arraysChecked = 0;
    foreach ($encodedChecks as $encodedTitles) {
        for ($x = 0; $x < sizeof($fileTitles); $x++) {

            $fileString = utf8_string_refine($fileTitles[$x]);

            if (($encodedTitles[$x + 1]) != $fileString) {

                if ($arraysChecked == 8) { // IF FINISH WITH LAST ARRAY CHECK THEN RETURN MALFORMED
                    exit("<br />Column Names Could Not Be Verified. Please Adjust And Try Again.");
                }

                $arraysChecked++;
                continue 2;

            }

            if (($encodedTitles[$x + 1]) == end($encodedTitles)) { // IF FILE FORMATTED CORRECTLY BREAK TEST
                break 2;
            }

        }
    }

}

/* PARSE UPDATES TO APPLICABLE ARRAY */
function get_updates($updates) {

    $updatesToRun = []; // STORE UPDATES AS AN ARRAY
    for ($x = 1; $x < (sizeof($updates)-1); $x++) {

        $update = explode(',', $updates[$x]);
        for ($y = 0; $y < sizeof($update); $y++) {

            $check = $update[$y];
            $update[$y] = filter_var($update[$y], FILTER_SANITIZE_STRING);
            if ($check != $update[$y]) {
                exit("<br />Invalid Data At Column ".$y." And Row ".($x+1));
            }

        }
        array_push($updatesToRun, $update);
    }

    array_pop($updatesToRun); // REMOVE DEFAULT FILE ENCODING FROM WEBKIT
    return $updatesToRun;

}

/* CREATE ARRAY OF QUERIES TO RUN */
function create_queries($databaseName, $updates, $columnNames) {
    $startOfQuery = "INSERT INTO wp_".$databaseName." (";
    for ($x = 0; $x < sizeof($columnNames); $x++) {

        if ($x == (sizeof($columnNames) - 1)) { // IF ON THE LAST COLUMN NAME APPEND CLOSING BRACKET

            $startOfQuery = $startOfQuery."`".substr($columnNames[$x],0,-1)."`) VALUES (";

        } else {

            ($x==0) ? $startOfQuery = $startOfQuery."`".substr($columnNames[$x],3)."`, " // IF ON FIRST COLUMN NAME REMOVE SPECIAL CHARACTERS
            : $startOfQuery = $startOfQuery."`".$columnNames[$x]."`, ";

        }
    }

    $updatesToRun = get_updates($updates);
    return form_queries($updatesToRun, $columnNames, $startOfQuery);

}

/* FORM INSERT AND ON DUPLICATE BODY OF QUERIES */
function form_queries($updatesToRun, $columnNames, $startOfQuery) {

    $queries = [];
    for ($x = 0; $x < sizeof($updatesToRun); $x++) {
        if (sizeof($updatesToRun[$x]) < 2) { exit("<br />Upload Malformed For This Table. Edit From Default File And Try Again"); return; } // IF UPDATE ARRAY COUNT < MIN  COLUMNS IN DATABASE

        $manipulatedQuery = $startOfQuery;
        for ($y = 0; $y < (sizeof($updatesToRun[$x])); $y++) {

            if (substr($updatesToRun[$x][$y],0) == "") { exit("<br />An Update Cell Is Empty"); return; } // IF UPDATE DATA IS EMPTY

            if ($y == (sizeof($updatesToRun[$x]) - 1)) { // ON LAST UPDATE APPEND CLOSE (REMOVE SPECIAL CHARACTERS)

                $manipulatedQuery = $manipulatedQuery."'%s') ON DUPLICATE KEY UPDATE ";
                array_push($queries, $manipulatedQuery);

            } else { $manipulatedQuery = $manipulatedQuery."'%s',"; }

        }

    }

    for ($x = 0; $x < sizeof($queries); $x++) {
        for ($y = 0; $y < sizeof($columnNames); $y++) {

            if ($y == (sizeof($columnNames) - 1)) { //APPEND LAST 'ON DUPLICATE' DATA (REMOVE SPECIAL CHARACTERS)
                $queries[$x] = $queries[$x]."`".substr($columnNames[$y],0,-1)."` = '%s'";

            } else {

                ($y == 0) ? $queries[$x] = $queries[$x]."`".substr($columnNames[$y], 3)."` = %s, " : $queries[$x] = $queries[$x]."`".$columnNames[$y]."` = '%s', ";

            }
        }
    }

    return $queries;

}

/* RUN ALL GENERATED QUERIES */
function update_database($databaseName, $queries, $updates) {

    global $wpdb;
    $wpdb->query("TRUNCATE TABLE wp_".$databaseName);
    $wpdb->query("START TRANSACTION");

    for ($x = 0; $x < sizeof($queries); $x++) {

        $updates[$x][(sizeof($updates[$x])-1)] = substr($updates[$x][(sizeof($updates[$x])-1)], 0, -1);
        $updates[$x] = array_merge($updates[$x], $updates[$x]);
        $prepared = $wpdb->prepare($queries[$x], $updates[$x]);

        $wpdb->query($prepared);

        if($wpdb->last_error) {
            $wpdb->query("ROLLBACK"); // MAKE NO COMMITS IF ANY ERROR OCCURS
            exit("<br />Error: Generated Query Invalid"); return;
        }

    }

    $wpdb->query("COMMIT");

}

/* SECURITY CHECK NONCE AND USER RANK */
function verify() {

    if (! isset( $file[2196] ) || !wp_verify_nonce( $file[2196], "trench_register_nonce")) {
        exit("<br />Error: Request Timestamp Invalid"); return;
    } else {

        $user = wp_get_current_user();
        if (!in_array( 'administrator', (array) $user->roles )) {
            exit("<br />Error: User Privileges are Invalid"); return;
        }

    }

}

/* RESET POST AND PARSE FILE TO ARRAY */
reset($_POST);
$file = $_POST[key($_POST)];
$file = explode("\n",$file);

/* SECURITY VERIFICATION */
add_action('init','verify');
array_splice($file, (sizeof($file)-4));

/* CHECK FILE NAMES */
$databaseName = $file[2];
check_file_name($databaseName, $file[4]);

/* CHECK COLUMN TITLES */
$file = array_slice($file, 7);
$fileTitles = explode(',', $file[0]);
check_titles($fileTitles);

/* CREATE PREPARED QUERIES AND UPDATES TO RUN */
$queries = create_queries($databaseName, $file, $fileTitles);
$updatesToRun = get_updates($file);

/* UPDATE DATABASE */
update_database($databaseName, create_queries($databaseName, $file, $fileTitles), $updatesToRun);
echo("<br />Upload Successful"); return;

?>
