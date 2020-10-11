<?php
/**
*   Plugin Name: Counter
*   Description: Plugin ten dodaje sekcję z licznikami do Twojej strony Wordpress
*   Version: 1.0
*   Author: Piotr Deja
*
**/

// Exit if Accessed Directly
if (!defined('ABSPATH')) {
    exit;
}


// Load Scripts

/*require_once( plugin_dir_path(__FILE__) . '/includes/counter-scripts.php');*/

    require_once 'libs/Request.php';
    require_once 'libs/counter_entry.php';
    require_once 'libs/counter_model.php';
    require_once 'libs/functions.php';


/*require_once( plugin_dir_path(__FILE__) . '/includes/counter_model.php');*/


class pd_counter {
    
    private static $plugin_id = 'pd-counter';
    private $plugin_version = '1.0.0';
    
    private $user_capability = 'manage_options';
    
    private $model;
    
	function __construct() {
        
        $this->model = new counter_model();
        
        
        //uruchamianie podczas aktywacji
        register_activation_hook(__FILE__, array($this, 'onActivate'));        
        
		//add filter for WordPress 2.8 changed backend box system !
		/*add_filter('screen_layout_columns', array(&$this, 'on_screen_layout_columns'), 10, 2);*/
		//register callback for admin menu  setup
		add_action('admin_menu', array(&$this, 'createAdminMenu')); 
		//register the callback been used if options of page been submitted and needs to be processed
		/*add_action('admin_post_save_howto_metaboxes_general', array(&$this, 'on_save_changes'));*/
	}
    
    function onActivate() {
        $ver_opt = static::$plugin_id.'-version';
        $installed_version = get_option($ver_opt, NULL);
        
        if($installed_version == NULL) {
            
            $this->model->createDbTable();
            update_option($ver_opt, $this->plugin_version);
            
        } else {
            

            
        }
    }
    
    
    function createAdminMenu() {
        $this->pagehook = add_menu_page(
            'pd_counter', 
            'Counter', 
            $this->user_capability, 
            static::$plugin_id, 
            array($this, 'printAdminPage')
        );
        add_action('load-'.$this->pagehook, array(&$this, 'on_load_page'));
    }
    
	//will be executed if wordpress core detects this page has to be rendered
	function on_load_page() {

		add_meta_box('gpminvoice_group', 'Lista Counter Items', array(&$this, 'gpminvoice_group'), $this->pagehook, 'additional', 'core');
	}   
    
	//executed to show the plugins complete admin page
	function printAdminPage() {
        
        
        $request = Request::instance();
        $view = $request->getQuerySingleParam('view', 'form');
        $action = $request->getQuerySingleParam('action');
        $countNumberId = (int)$request->getQuerySingleParam('countNumberId');        
        
        
        if($countNumberId > 0) {

        } else {

           $CounterEntry = new counter_entry(); 
        }
        
/*        $action_params = array('action' => 'save');
        if($Slide->hasId()){
            $action_params['countNumberId'] = $Slide->getField('id');
        }  */      
        
        if ($action == 'save' && $request->isMethod('POST') && isset($_POST['entry'])) {


            $CounterEntry->setFields($_POST['entry']);

                $entry_id = $this->model->saveEntry($CounterEntry);  
            





        }

        $action_params = array('action' => 'save');
        /*$action_params['slideid'] = $Slide->getField('id');*/
        
         ?>

<?php
//we need the global screen column value to beable to have a sidebar in WordPress 2.8

$data = array('My Data 1', 'My Data 2', 'Available Data 1');

		?>
		<div id="howto-metaboxes-general" class="wrap">
		<h2>Licznik</h2>
        <h3>Możesz dodać ten licznik do Twojej strony używając shortcode -> [counter]</h3>
        <?php echo $this->pagehook; ?>
		<form action="<?php echo $this->getAdminPageUrl($action_params); ?>" method="post">
						<?php do_meta_boxes($this->pagehook, 'additional', $data); ?>
							<input type="submit" value="Save Changes" class="button-primary" name="Submit"/>
		</form>
		</div>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php echo $this->pagehook; ?>');
		});
		//]]>
	</script>

        
        
		
		<?php
	}


    
    
    public function getAdminPageUrl(array $params = array()) {
        $admin_url = admin_url('admin.php?page='.static::$plugin_id);
        $admin_url = add_query_arg($params, $admin_url);

        return $admin_url;
    }    
    
    
	function gpminvoice_group($data) {

    global $post;

     wp_nonce_field( 'gpm_repeatable_meta_box_nonce', 'gpm_repeatable_meta_box_nonce' );
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function( $ ){
        $( '#add-row' ).on('click', function() {
            var row = $( '.empty-row.screen-reader-text' ).clone(true);
            var trVisibleCount = $('.visibleTr').length;
            var n = trVisibleCount-1;
            var n = n + 1;
            var id = 'entry[' + n + '][id]';
            row.find('input[name="id"]').attr('name', id);            
            var count_number = 'entry[' + n + '][count_number]';
            row.find('input[name="count_number"]').attr('name', count_number);
            var ikona = 'entry[' + n + '][ikona]';
            row.find('input[name="ikona"]').attr('name', ikona);
            var opis = 'entry[' + n + '][opis]';
            row.find('textarea[name="opis"]').attr('name', opis);            
            row.removeClass( 'empty-row screen-reader-text' );
            row.addClass( 'visibleTr' );            
            row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
            return false;
        });

        $( '.remove-row' ).on('click', function() {
            $(this).parents('tr').remove();
            return false;
        });
    });
  </script>

     
  <table id="repeatable-fieldset-one" width="100%">
  <tbody>
      
      <?php
        global $wpdb;
        
        $mobile_bar_items = $wpdb->get_results( "SELECT * FROM wp_pd_counter");
        
        /*print("<pre>".print_r($mobile_bar_items,true)."</pre>");*/
        
        if ($mobile_bar_items) { ?>
            
        <?php $count = 0; ?>            
      
        <?php foreach ($mobile_bar_items as $mobile_bar_single) { ?>
      
          
            <tr class="visibleTr">
              <input type="hidden" name="entry[<?php echo $count; ?>][id]" value="<?php echo $mobile_bar_single->id; ?>">
              <td width="20%">
                  <label>Numer do odliczania</label>
                <input type="text"  placeholder="Title" name="entry[<?php echo $count; ?>][count_number]" value="<?php echo $mobile_bar_single->count_number; ?>" /></td> 
              <td width="20%">
                  <label>ikona np: 'fa fa-home'</label>
                <input type="text"  placeholder="Title" name="entry[<?php echo $count; ?>][ikona]" value="<?php echo $mobile_bar_single->ikona; ?>" /></td>         
              <td width="50%">
                  <label>Opis</label>
              <textarea placeholder="Description" cols="55" rows="5" name="entry[<?php echo $count; ?>][opis]"><?php echo $mobile_bar_single->opis; ?></textarea></td>
              <td width="10%"><a class="button remove-row" href="#1">Usuń</a></td>
            </tr>         
          
        <?php $count++ ?>
      
      
      <?php }

        } else {
            
        
      ?>

    <tr class="visibleTr">
      <input type="hidden" name="entry[0][id]" value="">        
      <td width="20%">
          <label>Numer do odliczania</label>
        <input type="text"  placeholder="Title" name="entry[0][count_number]" value="" /></td> 
      <td width="20%">
          <label>ikona np: 'fa fa-home'</label>
        <input type="text"  placeholder="Title" name="entry[0][ikona]" value="" /></td>         
      <td width="50%">
          <label>Opis</label>
      <textarea placeholder="Description" cols="55" rows="5" name="entry[0][opis]"> </textarea></td>
      <td width="10%"><a class="button remove-row" href="#1">Usuń</a></td>
    </tr>
      
      
    <?php } ?>
    
      
    <tr class="empty-row screen-reader-text">
      <input type="hidden" name="id" value="">         
      <td width="20%">
          <label>Numer do odliczania</label>
        <input type="text"  placeholder="Title" name="count_number" value="" /></td> 
      <td width="20%">
          <label>ikona np: 'fa fa-home'</label>
        <input type="text"  placeholder="Title" name="ikona" value="" /></td>         
      <td width="50%">
          <label>Opis</label>
      <textarea placeholder="Description" cols="55" rows="5" name="opis"> </textarea></td>
      <td width="10%"><a class="button remove-row" href="#1">Usuń</a></td>
    </tr>      

  </tbody>
</table>
    
    


<p><a id="add-row" class="button" href="#">Add another</a></p>
        
		<?php
	}    
    
    
}

$counter = new pd_counter();








