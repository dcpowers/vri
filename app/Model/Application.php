<?php
// app/Model/App.php
App::uses('AppModel', 'Model');

class Application extends AppModel {
    public $actsAs = array('Tree', 'Containable');
}