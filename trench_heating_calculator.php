<?php
    /*
     * Template for Trench-Heating Calculator
     *
     * @author Matthew Frankland
     * @package Turnbull
     * Template Name: Trench-Calculator
     */
    
    add_filter( 'body_class', 'add_body_class' );

    function add_body_class( $classes ) {
        $classes[] = 'tb-category';
        return $classes;
    }

    get_header();
    
?>

<script type="text/javascript">
    /* REFRESH PAGE ON HISTORY TRAVEL */
    window.addEventListener( "pageshow", function ( event ) {
        var historyTraversal = event.persisted || 
                ( 
                    typeof window.performance != "undefined" 
                    && window.performance.navigation.type === 2 
                );
        
        if ( historyTraversal ) {
            window.location.reload(false);            
        }
        
    });
</script>

<?php
    function getLink() {
        echo get_stylesheet_directory_uri()."/trench_calculator/";
    }
?>

<!-- MARK: INCLUDE SCRIPTS & STYLESHEETS -->

<!-- MARK: REQUIRE ONCE TEMPLATE LAYOUT -->
