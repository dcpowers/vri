<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Associate $Associate
 */
class LinksController extends AppController {

    //public $components = array('Search.Prg');
    #public $helpers = array( 'Tree' );
    //Search Plugin

    var $uses = array(
        'Link',
        'Setting',
		'AuthRole'
    );

    public $components = array( 'RequestHandler', 'Paginator');

    public $presetVars = array(
        array('field' => 'q', 'type' => 'value')
    );

    public $paginate = array(
        'order' => array(
            'Improvement.name' => 'asc'
        ),
        'limit'=>50
    );

    public function pluginSetup() {
        $user = AuthComponent::user();

        //These Two Lines are Required
        parent::pluginSetup();
        Configure::write('App.Name', 'Quick Links');
    }

    public function beforeFilter() {
        parent::beforeFilter();

        $this->set('title_for_layout', 'Quick Links');
        /*
        $this->set('breadcrumbs', array(
            array('title'=>'Improvements', 'link'=>array('controller'=>'Improvements', 'action'=>'index')),
        ));
		*/
    }

    public function index() {
        $links = $this->Link->find('all', array(
            'conditions' => array(
            ),
            'contain'=>array(
                'Status'=>array(),
            ),
            'order'=>array('Link.name'=> 'asc'),
        ));

		$this->set('links', $links);
        $this->set('roles', $this->AuthRole->pickListByRole(2));
    }

	public function dashboard() {
        $links = $this->Link->find('all', array(
            'conditions' => array(
                'Link.is_active'=>1,
                'Link.permission_level <=' => AuthComponent::user('Role.permission_level')
            ),
            'contain'=>array(
                'Status'=>array(),
            ),
            'order'=>array('Link.name'=> 'asc'),
        ));
		#pr($links);
		#exit;
		if ($this->request->is('requested')) {
            return $links;
        }

		$this->set('links', $links);
    }

    public function view($id=null){
        $this->Link->id = $id;
        if (!$this->Link->exists()) {
            throw new NotFoundException(__('Invalid Account Id'));
        }

        $link = $this->request->data = $this->Link->find('first', array(
            'conditions' => array(
                'Link.id' => $id
            ),
            'contain' => array(
            ),
        ));

		$this->set('id', $id);
        $this->set('link', $link);
		$this->set('status', $this->Setting->pickList('status'));
		$this->set('roles', $this->AuthRole->pickListByRole($this->Auth->user('Role.id')));
    }

    public function edit(){
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Link->saveAll($this->request->data)) {
                $this->Flash->alertBox(
                    'The Link: "'.$this->request->data['Link']['name'].'" has been saved',
                    array('params' => array('class'=>'alert-success'))
                );
            }else{
                $this->Flash->alertBox(
                    'There Was An Error! Please, try again.',
                    array('params' => array('class'=>'alert-danger'))
                );
            }

			$this->redirect(array('controller'=>'Links', 'action'=>'index'));
        }
    }

    public function add(){
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Link->saveAll($this->request->data)) {
            	#Audit::log('Group record added', $this->request->data );
                //$this->Group->reorder(array('id' => $parent[0]['Group']['id'], 'field' => 'name', 'order' => 'ASC', 'verify' => true));
                $this->Flash->alertBox(
                	'A New Link Has Been Added',
                    array( 'params' => array('class'=>'alert-success'))
                );
            }else{
            	$this->Flash->alertBox(
                	'There Was An Error! Please, try again.',
                    array('params' => array('class'=>'alert-danger'))
                );
            }

            $this->redirect(array('controller'=>'Links', 'action'=>'index'));
        }

		$this->set('status', $this->Setting->pickList('status'));
		$this->set('roles', $this->AuthRole->pickListByRole($this->Auth->user('Role.id')));

    }

    public function delete($id = null) {
        $this->Link->id = $id;

        if (!$this->Link->exists()) {
            throw new NotFoundException(__('Invalid Link Id'));
        }

        $this->request->data['Link']['id'] = $id;

        if ($this->Link->delete()) {
            #Audit::log('Group record added', $this->request->data );
            //$this->Group->reorder(array('id' => $parent[0]['Group']['id'], 'field' => 'name', 'order' => 'ASC', 'verify' => true));
            $this->Flash->alertBox(
                'Link Deleted',
                array('params' => array('class'=>'alert-success'))
            );
        }else{
            $this->Flash->alertBox(
                'There Was An Error! Please, try again.',
                array('params' => array('class'=>'alert-danger'))
            );
        }

        $this->redirect(array('controller'=>'Links', 'action'=>'index'));
    }

}