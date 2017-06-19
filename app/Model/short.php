<?php
App::uses('AppModel', 'Model');

class Short extends AppModel {
     public $actsAs = array('Containable');
    /**
     * belongsTo associations
     *
     * @var array
     */
     
    public function build_url($n=null){
        $codeset = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $base = strlen($codeset);
        $converted = "";

        while ($n > 0) {
            $converted = substr($codeset, ($n % $base), 1) . $converted;
            $n = floor($n/$base);
        }
        return $converted; // 4Q 
    }
}