<?php

namespace app\controllers;

use app\models\Collections;
use app\models\CollectionsWorks;
use app\models\Works;

use app\models\Users;
use app\models\Roles;

use li3_filesystem\extensions\storage\FileSystem;

use lithium\action\DispatchException;
use lithium\security\Auth;
use lithium\template\View;

use lithium\core\Libraries;

class CollectionsController extends \lithium\action\Controller {

	public function index() {
    
    	// Check authorization
	    $check = (Auth::check('default')) ?: null;
	
		// If the user is not authorized, redirect to the login screen
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
        // Look up the current user with his or her role
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));

		$order = array('title' => 'ASC'); 
		
		$collections = Collections::find('all', array(
			'with' => 'CollectionsWorks',
			'order' => $order
		));
		return compact('collections', 'auth');
	}

	public function view() {

	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
	
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {
		
			//Get single record from the database where the slug matches the URL
			$collection = Collections::first(array(
				'conditions' => array(
				'slug' => $this->request->params['slug'],
			)));
			
			if($collection) {
			
				$collection_works = CollectionsWorks::find('all', array(
					'with' => 'Works',
					'conditions' => array('collection_id' => $collection->id),
					'order' => 'earliest_date ASC'
				));

				$li3_pdf = Libraries::get("li3_pdf");
			
				//Send the retrieved data to the view
				return compact('collection', 'collection_works', 'auth', 'li3_pdf');
				
			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Collections::index'));
	}

	public function add() {
    
	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
        
        // If the user is not an Admin or Editor, redirect to the index
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect('Collections::index');
        }
        
		$collection = Collections::create();

		if (($this->request->data) && $collection->save($this->request->data)) {
			return $this->redirect(array('Collections::view', 'args' => array($collection->slug)));
		}
		return compact('collection');
	}

	public function edit() {
    
	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
		
		$collection = Collections::first(array(
				'conditions' => array(
				'slug' => $this->request->params['slug'],
			)));

		if (!$collection) {
			return $this->redirect('Collections::index');
		}
		if (($this->request->data) && $collection->save($this->request->data)) {
			return $this->redirect(array('Collections::view', 'args' => array($collection->slug)));
		}
		return compact('collection');
	}

	public function history() {

		$check = (Auth::check('default')) ?: null;
	
		if (!$check) {
			return $this->redirect('Sessions::add');
		}
		
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));

		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {
		
			//Get single record from the database where the slug matches the URL
			$collection = Collections::first(array(
				'conditions' => array(
				'slug' => $this->request->params['slug'],
			)));
			
			if($collection) {
			
				$works = Works::find('all', array(
					'with' => array('CollectionsWorks', 'Users'),
					'conditions' => array('collection_id' => $collection->id),
					'order' => 'date_modified DESC'
				));
			
				//Send the retrieved data to the view
				return compact('collection', 'works', 'auth');
				
			}
		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Collections::index'));
	}

	public function publish() {
    
	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
	
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {

			$options = $this->request->query;

			$layout = isset($options['layout']) ? $options['layout'] : 'download';

			$config = FileSystem::config('documents'); 
			$options['path'] = $config['path'];
		
			//Get single record from the database where the slug matches the URL
			$collection = Collections::first(array(
				'conditions' => array(
				'slug' => $this->request->params['slug'],
			)));
			
			if($collection) {
				
				$pdf = $collection->slug . '-' . $options['view'] . '.pdf';

				$collections_works = CollectionsWorks::find('all', array(
					'with' => 'Works',
					'conditions' => array('collection_id' => $collection->id),
					'order' => 'earliest_date ASC'
				));


				$view  = new View(array(
					'paths' => array(
						'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
						'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
					)
				));
				echo $view->render(
					'all',
					array('content' => compact('pdf', 'collection','collections_works', 'options')),
					array(
						'controller' => 'collections',
						'template'=>'view',
						'type' => 'pdf',
						'layout' => $layout
					)
				);
			}
		}
		
		//since no record was specified, redirect to the index page
		//$this->redirect(array('Collections::index'));
	}

	public function package() {

	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
	
		//Don't run the query if no slug is provided
		if(isset($this->request->params['slug'])) {
			
			//Get single record from the database where the slug matches the URL
			$collection = Collections::first(array(
				'conditions' => array(
				'slug' => $this->request->params['slug'],
			)));
			
			if($collection) {

				$package = $collection->slug . '.zip';

				$documents_config = FileSystem::config('documents');
				$documents_path = $documents_config['path'];

				$packages_config = FileSystem::config('public'); 
				$packages_path = $packages_config['path'];

				if (!file_exists($packages_path))
					@mkdir($packages_path);

				$package_path = $packages_path . DIRECTORY_SEPARATOR . $package;

				if (file_exists($package_path))
					unlink($package_path);

				$zip = new \ZipArchive();
				$success = $zip->open($package_path, \ZIPARCHIVE::CREATE);
				
				$collections_works = CollectionsWorks::find('all', array(
					'with' => 'Works',
					'conditions' => array('collection_id' => $collection->id),
					'order' => 'earliest_date ASC'
				));

				foreach ($collections_works as $cw) {
					$work = $cw->work;
					$documents = $work->documents();

					foreach ($documents as $document) {
						$slug = $document->slug;
						$extension = $document->format->extension;
						$document_file = $document->file();
						$document_path = $documents_path . DIRECTORY_SEPARATOR . $document_file;

						$document_localname = $cw->work->years() . '-' . $work->slug . '-' . $slug . '.' . $extension;

						$zip->addFile($document_path, $document_localname);
					}
				}

				$layout = 'file';
				$options['path'] = $documents_path;
				$options['view'] = 'artwork';
				$pdf = $packages_path . DIRECTORY_SEPARATOR . $collection->slug . '.pdf';
				
				$view  = new View(array(
					'paths' => array(
						'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
						'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
					)
				));
				$view->render(
					'all',
					array('content' => compact('pdf', 'collection','collections_works', 'options')),
					array(
						'controller' => 'collections',
						'template'=>'view',
						'type' => 'pdf',
						'layout' => $layout
					)
				);

				$zip->addFile($pdf, $collection->slug . '.pdf');

				$zip->close();

				//Send the retrieved data to the view
				return compact('collection', 'packages_path', 'package');
			}
		
		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Collections::index'));
	}

	public function delete() {
    
	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
		
		if(isset($this->request->params['slug'])) {
        
			$collection = Collections::first(array(
				'conditions' => array(
				'slug' => $this->request->params['slug'],
			)));
		
			if($collection) {
		    
				// If the user is not an Admin or Editor, redirect to the record view
				if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
					return $this->redirect(array(
						'Collections::view', 'args' => array($this->request->params['slug']))
					);
				}
				
				// For the following to work, the delete form must have an explicit 'method' => 'post'
				// since the default method is PUT
				if (!$this->request->is('post') && !$this->request->is('delete')) {
					$msg = "Collections::delete can only be called with http:post or http:delete.";
					throw new DispatchException($msg);
				}
		
				$collection->delete();
		
			}
		}
		return $this->redirect('Collections::index');
	}

}

?>
