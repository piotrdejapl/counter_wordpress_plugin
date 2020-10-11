<?php




    class counter_entry {
        
        
   
        
/*        private $id = NULL;
        private $count_cumber = NULL;
        private $ikona = NULL;
        private $opis = NULL;
        private $published = 'yes';
        
        private $errors = array();
        
        private $exists = FALSE;*/
        
        
        /*function __construct($id = NULL) {
            $this->id = $id;
        }*/
        /*private function load() {
            if(isset($this->id)) {
                $Model = new counter_model();
                $row = $Model->fetchRow($this->id);
                
                if(isset($row)) {
                    $this->setFields($row);
                    $this->exists = TRUE;
                }
            }
        }*/
        
        
        // w tej klasie 'counter_entry' znajduje się zawarość danych przekazywanych po naciśnięcu 'save' w formularzu
        
        
        
        
        
        
        public function exists() {
            return $this->exists;
        }
        
        
        function getField($entry_items, $field) {
            
            foreach($entry_items as $entry_item) {
                if(isset($entry_item->{$field})){
                    return $entry_item->{$field};
                }                
            }
            

               
               return NULL;
        }
        
        
        function isPublished() {
            return ($this->published =='yes');
        }
        
        
        function setFields($fields){
            foreach ($fields as $key => $val) {
                $this->{$key} = $val;
            }
        }
        
        function setError($field, $error) {
            $this->errors[$field] = $error;
        }
        
        function getError($field) {
            if(isset($this->errors[$field])) {
                return $this->errors[$field];
            }
            return NULL;
        }
        
        
        function hasId($entry) {
            
            foreach ($entry as $entry_item) {
                return $entry_item['id'];
            }
            
        }
        
        
        function getIds() {
            global $wpdb;
            $restuls = $wpdb->get_results( " SELECT * FROM  wp_pd_counter"  );
            $ids = array();
            foreach($restuls as $result) {
                $ids[] = $result->id;
            }
            
            return $ids;
        }
        
        
        function hasErrors() {
            return (count($this->errors) > 0);
        }
        
       
       
        
        
        
    }







?>