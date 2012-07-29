<?php
 
namespace app\controllers;
 
use app\models\Documents;

use lithium\action\DispatchException;
use lithium\security\Auth;
 
class FilesController extends \lithium\action\Controller {

	public function __construct(array $config = array()) {
		$defaults = array('render' => array('auto' => false));
		return parent::__construct($config + $defaults);
	}
 
	public function view() {
    
	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
        //Don't run the query if no slug is provided
		if(isset($this->request->params['file'])) {
 
			$file = $this->request->params['file'];
	
			$ext = strrpos($file, '.');
			$slug = substr($file, 0, $ext);
		
			$document = Documents::first(array(
				'conditions' => array('slug' => $slug),
				'with' => array('Formats')
			));
			
			if($document) {
		
				$path = LITHIUM_APP_PATH.'/webroot/files/' . $document->hash . '.' . $document->format->extension;
				
				if(file_exists($path)){
					$this->response->headers(array('Content-type' => $document->format->mime_type));
					$this->response->body = file_get_contents($path);
		 
					return compact('file');
			
				}
			}
			
			$this->redirect(array('Documents::index'));
		
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Documents::index'));
	}
 
	public function small() {
    
	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
        //Don't run the query if no slug is provided
		if(isset($this->request->params['file'])) {
 
			$file = $this->request->params['file'];
	
			$ext = strrpos($file, '.');
			$slug = substr($file, 0, $ext);
			$px = '560';
		
			$document = Documents::first(array(
				'conditions' => array('slug' => $slug),
				'with' => array('Formats')
			));
			
			if($document) {
		
				$path = LITHIUM_APP_PATH . 
					'/webroot/files/' . 
					$document->hash . '_' . $px . 'x' . $px .
					'.' .
					'jpeg';
				
				if(file_exists($path)){
					$this->response->headers(array('Content-type' => 'image/jpeg'));
					$this->response->body = file_get_contents($path);
		 
					return compact('file');
			
				}
			}
			
			$this->redirect(array('Documents::index'));
		
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Documents::index'));
	}
 
	public function thumb() {
    
	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
        //Don't run the query if no slug is provided
		if(isset($this->request->params['file'])) {
 
			$file = $this->request->params['file'];
	
			$ext = strrpos($file, '.');
			$slug = substr($file, 0, $ext);
			$px = '260';
		
			$document = Documents::first(array(
				'conditions' => array('slug' => $slug),
				'with' => array('Formats')
			));
			
			if($document) {
		
				$path = LITHIUM_APP_PATH . 
					'/webroot/files/' . 
					$document->hash . '_' . $px . 'x' . $px .
					'.' .
					'jpeg';
				
				if(file_exists($path)){
					$this->response->headers(array('Content-type' => 'image/jpeg'));
					$this->response->body = file_get_contents($path);
		 
					return compact('file');
			
				}
			}
			
			$this->redirect(array('Documents::index'));
		
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Documents::index'));
	}
	
	public function download() {
    
	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
        //Don't run the query if no slug is provided
		if(isset($this->request->params['file'])) {
 
			$file = $this->request->params['file'];
	
			$ext = strrpos($file, '.');
			$slug = substr($file, 0, $ext);
		
			$document = Documents::first(array(
				'conditions' => array('slug' => $slug),
				'with' => array('Formats')
			));
			
			if($document) {
		
				$path = LITHIUM_APP_PATH.'/webroot/files/' . $document->hash . '.' . $document->format->extension;
				
				if(file_exists($path)){
					$this->response->headers('download', $file);
					$this->response->body = file_get_contents($path);
		 
					return compact('file');
			
				}
			}
			
			$this->redirect(array('Documents::index'));
		
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Documents::index'));
	}
}
 
?>
