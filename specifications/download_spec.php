<?php

    /* DELETE GENERATED FILE */
    function deleteFile($fileName) {
        unlink(getcwd().'/'.$fileName);
    }

    /* IF CALLED WHEN FILE GENERATED DELETE FILE */
    if(isset($_GET['fileName'])){
        
        /* MARK: WORDPRESS ENVIROMENT */
        require( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
        deleteFile($_GET['fileName']);
        return;
            
    }

    /* INCLUDE FDPF STRUCTURE */
    include('fpdf.php');
    define('POUND',chr(163));
    
    /* HEADER FOR OUTPUTING PDF DOCUMENT */
    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/pdf');
    
    /* DATA FOR GENERATING PDF */
    $products = json_decode($_POST['products'], true);
    $custom = json_decode($_POST['custom'], true);
    $access = json_decode($_POST['access'], true);
    $id = $_POST['custID'];
    
    /* NEW PDF WITH SINGLE PAGE */
    $pdf = new FPDF();
    $pdf->AddPage();
    
    /* ADD MAIN LOGO TO DOCUMENT */
    $pdf->Image('logo.png',10,7,-600);
    $pdf->SetFont('Courier','BU',18);
    $pdf->Multicell(185,10,'',0,"L"); // EMPTY ROW
    $pdf->Multicell(185,10,'',0,"L"); // EMPTY ROW
    
    /* ADD Project REFERENCE */
    $pdf->SetFont('Courier','I',18);
    $pdf->Image('drawing.jpg',122,25,-150);
    $pdf->Multicell(185,10,'Project Reference: '.$id,0,"L");
    $pdf->Multicell(185,5,'',0,"L");
    
    /* CALCULATE TOTAL PRICE (BASE + ACCESSORIES) ONGOING */
    $totalPrice = number_format((float)$products[0][8], 2, '.', '');
    
    /* ADD PRODUCTS SUB-SECTION - H1 TITLE */
    $pdf->SetFont('Courier','BU',18);
    $pdf->SetTextColor(248, 151, 29);
    $pdf->Multicell(185,10,'TRENCH UNITS SPECIFIED',0,"L");
    $pdf->SetTextColor(0, 0, 0);
    
    for ($x = 0; $x < (sizeof($products)); $x++) {
        /* PRODUCT ID IS IN H2 */
        $pdf->SetFont('Courier','B',16);
        $pdf->Multicell(185,10,'Product ID: '.$products[$x][1],0,"L");
        
        /* PRODUCT DETAILS */
        $pdf->SetFont('Courier','I',14);
        $pdf->Multicell(185,10,'Length (mm/unit): '.$products[$x][2],0,"L");
        $pdf->Multicell(185,10,'Depth (mm/unit): '.$products[$x][3],0,"L");
        $pdf->Multicell(185,10,'Width (mm/unit): '.$products[$x][4],0,"L");
        $pdf->Multicell(185,10,'Heat Output (kW): '.$products[$x][5],0,"L");
        $pdf->Multicell(185,10,'Cooling Output (kW): '.$products[$x][6],0,"L");
        $pdf->Multicell(185,10,'Base Price ('.POUND.'/unit): '.number_format((float)$products[$x][7], 2, '.', ''),0,"L");
        $pdf->Multicell(185,10,'Quantity: '.$products[$x][9],0,"L");
        $pdf->Multicell(185,5,'',0,"L");
        
        /* INCREASE TOTAL PRICE BY BASE PRICE * QUANTITY */
        $totalPrice = $totalPrice + ((float)$products[$x][7] * (float)$products[$x][9]);
    }
    
    /* ADD CUSTOMISATIONS SUB-SECTION - H1 TITLE */
    $pdf->SetFont('Courier','BU',18);
    $pdf->SetTextColor(248, 151, 29);
    $pdf->Multicell(185,10,'CUSTOMISATIONS',0,"L");
    $pdf->SetTextColor(0, 0, 0);
    
    /* CUSTOMISATION DETAILS */
    for ($x = 0; $x < (sizeof($custom)); $x++) {
        $pdf->SetFont('Courier','I',14);
        $pdf->Multicell(185,10,$custom[$x],0,"L");
    }
    
    /* IF NO ACCESSORIES CHOSEN */
    if ($access[0] == "" || sizeof($access) == 0) {
        
        $pdf->SetFont('Courier','BU',18);
        $pdf->Multicell(185,5,'',0,"L");
        $pdf->SetTextColor(248, 151, 29);
        $pdf->Multicell(185,10,'TRENCH UNITS TO BE SUPPLIED WITH:',0,"L");
        $pdf->SetTextColor(0, 0, 0);
        
        $pdf->SetFont('Courier','I',14);
        $pdf->Multicell(185,10,'No Accessories Chosen',0,"L");
    } else {
        
        /* ADD ACCESSORIES SUB-SECTION - H1 TITLE */
        $pdf->SetFont('Courier','BU',18);
        $pdf->Multicell(185,5,'',0,"L");
        $pdf->SetTextColor(248, 151, 29);
        $pdf->Multicell(185,10,'TRENCH UNITS TO BE SUPPLIED WITH:',0,"L");
        $pdf->SetTextColor(0, 0, 0);
        
        /* ACCESSORIES DETAILS */
        for ($x = 0; $x < (sizeof($access)); $x++) {
            /* SUBSECTION HEADER */
            $pdf->SetFont('Courier','I',14);
            $pdf->Multicell(185,10,$access[$x],0,"L");
        }
    }
    
    /* ADD TOTAL PRICE */
    $pdf->Multicell(185,5,'',0,"L");
    $pdf->SetFont('Courier','B',16);
    $pdf->Multicell(185,10,'Total Base Price ('.POUND.'): '.number_format(((float)$totalPrice) - ((float)$products[0][8]), 2, '.', ''),0,"L");
    $pdf->Multicell(185,10,'Total Price for Grille ('.POUND.'): '.number_format($products[0][8], 2, '.', ''),0,"L");
    $pdf->Multicell(185,10,'Total Estimated Project Price for Final Specification ('.POUND.'):'.number_format($totalPrice, 2, '.', ''),0,"L");
    
    /* GENERATE FILE AND RETURN OK */
    $fileName = 'ts-specification-' . md5(time()) . '.pdf';
    $pdf->Output('F', $fileName);
    http_response_code(200);
    
    /* RETURN RANDOM FILE-NAME SAVED */
    echo $fileName;

?>
