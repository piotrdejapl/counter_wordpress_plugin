<?php



class counter_model {
    
    
    private $table_name = 'pd_counter';
    private $wpdb;
    
    function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        
        
    }
    
    function getTableName() {
        return $this->wpdb->prefix.$this->table_name;
    }
    
    function createDbTable() {
        
        $table_name = $this->getTableName();
        
        $sql = '
        
            CREATE TABLE IF NOT EXISTS ' . $table_name . '(
                id INT NOT NULL AUTO_INCREMENT,
                count_number VARCHAR(255) NOT NULL,
                ikona VARCHAR(255) NOT NULL,
                opis VARCHAR(255) NOT NULL,
                published enum("yes", "no") NOT NULL DEFAULT "yes",
                PRIMARY KEY(id)
                
            )ENGINE=InnoDB DEFAULT CHARSET=utf8';
        
        require_once ABSPATH. 'wp-admin/includes/upgrade.php';
        
        dbDelta($sql);
    }
    
    
/*    function saveEntry(counter_entry $entry) {
        
        $toSave = array(
            'count_number' => $entry->getField('count_number'),
            'ikona' => $entry->getField('ikona'),
            'opis' => $entry->getField('opis'),  
            'published' => $entry->getField('published')
        );
        
        
        $maps = array('%s','%s','%s');
        
        
        $table_name = $this->getTableName();
        
        if($this->wpdb->insert($table_name, $toSave, $maps)) {
            
            return $this->wpdb->insert_id;
            
        } else {
            
            FALSE;
        }
    }*/
    
    
    function do_insert($entry) {

        
        global $wpdb;

        /*$query           = "INSERT INTO wp_pd_counter (`count_number`, `ikona`, `opis`) VALUES ";
        $query           .= implode( ', ', $place_holders );
        $sql             = $wpdb->prepare( "$query ", $values );*/
        
        $queries = array();
        
        foreach($entry as $data) {
            /*array_push( $values, $data['count_number'], $data['ikona'], $data['opis']);
            $place_holders[] = "(%s, %s, %s)";
            $this->wpdb->insert_id;*/
            $queries[] = "INSERT INTO wp_pd_counter (`count_number`, `ikona`, `opis`) VALUES ('" . $data['count_number'] . "', '" . $data['ikona'] . "', '" . $data['opis'] . "')"; 
        }
        
        
        /*$wpdb->insert_id;*/
        /*print("<pre>".print_r($values,true)."</pre>");*/

        /*if ( $wpdb->query( $sql ) ) {
            return true;
        } else {
            return false;
        }*/
        foreach ($queries as $query) {
            /*$query  = $wpdb->prepare( $query);*/
            /*if (  ) {
                return true;
            } else {
                return false;
            }*/
            $wpdb->query( $query );
        }        

    }     
    
    function do_update($entry) {

        global $wpdb;
        
        $queriess = array();        
    
        
        foreach($entry as $data) {
            

        $queriess[] ="UPDATE wp_pd_counter SET count_number='" . $data['count_number'] . "', ikona='"  . $data['ikona'] . "', opis='" . $data['opis'] . "' WHERE id='" .  $data['id']  . "'";
            
            
        }
        
        foreach ($queriess as $query) {
            $wpdb->query($query);
        }
        

    }     

    
    
    function fetch_rows() {
        
        global $wpdb;
        
        $results = $wpdb->get_results( " SELECT * FROM  wp_pd_counter"  );
        
        return $results;
    }     

    
   
        
        
    function saveEntry(counter_entry $entry) {   
        
        global $wpdb;

        $values = $place_holders = array();
        
        // Pobieramy ID wszyskich istniejących rekordów w bazie
        $results = $wpdb->get_results( " SELECT * FROM  wp_pd_counter");
        $admin_ids = array();
        foreach($results as $result) {
            $admin_ids[] = $result->id;
        } 
        
        // Pobieramy ID rekodów z $entry
        $entry_ids = array();
        
        foreach($entry as $entry_item) {
            if ($entry_item['id']) {
                $entry_ids[] = $entry_item['id'];                
            }

        }        
        
        
        // Szukamy różnic pomiędzy tablicami rekordami z bazy i z $entry
        $differences = array_diff($admin_ids, $entry_ids);
        
        
        
        // z bazy usuwamy rekordy będące różnicą rekordów z 1)bazy i 2)$entry
        $queriez = array(); 
        
        if ($differences) {

            foreach($differences as $difference) {
                $queriez[] = "DELETE FROM wp_pd_counter WHERE id='" . $difference . "'";
            }

            foreach ($queriez as $query) {
                $wpdb->query($query);
            }    
        
        }
        
        $update = array();
        $insert = array();
        
        foreach ($entry as $entry_item) {
            
            if ($entry_item['id']) {
                  $update[] = $entry_item;
            } else {
                  $insert[] = $entry_item;
            }   
        }
        
        $this->do_update($update);
        $this->do_insert($insert);
        


    } 
    
    
    function getPublishedSlides(){
        $table_name = $this->getTableName();
        
        $sql = "SELECT * FROM {$table_name} WHERE published = 'yes' ";
        return $this->wpdb->get_results($sql);
    }    
    
    
    
}





?>