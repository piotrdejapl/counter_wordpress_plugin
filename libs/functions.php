<?php





    /**
     * Registers a stylesheet.
     */
    function wpdocs_register_plugin_styles() {
        wp_enqueue_style( 'counter-awesome',  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css' );
        wp_enqueue_style( 'counter-styles',  '/wp-content/plugins/counter/assets/css/pd-counter-styles.css' );
        wp_enqueue_script( 'counter-script',  '/wp-content/plugins/counter/assets/js/pd-counter-script.js', array('jquery'), '1.0.0', true );
    }
    // Register style sheet.
    add_action( 'wp_enqueue_scripts', 'wpdocs_register_plugin_styles' ); 



    function wpdocs_enqueue_custom_admin_style() {
            wp_enqueue_style( 'counter-admin', '/wp-content/plugins/counter/assets/css/counter-admin.css');
    }
    add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_style' );





add_shortcode( 'counter', 'counter_code' );


    function counter_code() {
        
        $model = new counter_model();

        $counter_rows = $model->getPublishedSlides();  
        
        
        $string = '<section class="counter-wrap"><ul class="counter">';
        
                
	   foreach ($counter_rows as $counter_item) {
           
       $string .= '<li class="counter__item">';
       $string .= '<div class="counter__liczba">';
       $string .= '<i class="' . $counter_item->ikona . '"></i> <span class="count-number" data-count="' . $counter_item->count_number . '">0</span>';
       $string .= '</div>';
       $string .= '<div class="counter__opis">' . $counter_item->opis . '</div>';
       $string .= '</li>';
       }
		
        $string .= '</ul></section>';      
        
        return $string;           
        
    }
    
    
    
    
    
?>