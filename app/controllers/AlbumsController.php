<?php

namespace app\controllers;

use app\models\Archives;
use app\models\Albums;
use app\models\ArchivesHistories;
use app\models\Works;
use app\models\Components;
use app\models\Packages;

use app\models\Users;
use app\models\Roles;

use li3_filesystem\extensions\storage\FileSystem;

use lithium\action\DispatchException;
use lithium\security\Auth;
use lithium\template\View;

use lithium\core\Libraries;

class AlbumsController extends \lithium\action\Controller {

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
		
		$albums = Albums::find('all', array(
			'with' => 'Archives',
			'order' => $order
		));
		return compact('albums', 'auth');
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
			$album = Albums::first(array(
				'with' => 'Archives',
				'conditions' => array(
				'slug' => $this->request->params['slug'],
			)));
			
			if($album) {

				$work_ids = array();	
			
				$album_works = Components::find('all', array(
					'fields' => 'archive_id2',
					'conditions' => array('archive_id1' => $album->id),
				));

				$works = array();

				if ($album_works->count()) {

					//Get all the work IDs in a plain array
					$work_ids = $album_works->map(function($aw) {
						return $aw->archive_id2;
					}, array('collect' => false));

					$works = Works::find('all', array(
						'with' => 'Archives',
						'conditions' => array('Works.id' => $work_ids),
						'order' => 'earliest_date DESC'
					));

				}

				$li3_pdf = Libraries::get("li3_pdf");

				//Send the retrieved data to the view
				return compact('album', 'works', 'auth', 'li3_pdf');
				
			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Albums::index'));
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
        	return $this->redirect('Albums::index');
        }
        
		$album = Albums::create();

		if (($this->request->data) && $album->save($this->request->data)) {
			//The slug has been saved with the Archive object, so let's look it up
			$archive = Archives::find('first', array(
				'conditions' => array('id' => $album->id)
			));
			return $this->redirect(array('Albums::view', 'slug' => $archive->slug));
		}
		return compact('album');
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
		
		$album = Albums::first(array(
			'with' => 'Archives',
			'conditions' => array(
			'slug' => $this->request->params['slug'],
		)));

		if (!$album) {
			return $this->redirect('Albums::index');
		}
		if (($this->request->data) && $album->save($this->request->data)) {
			return $this->redirect(array('Albums::view', 'slug' => $album->archive->slug));
		}
		return compact('album');
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
			$album = Albums::first(array(
				'with' => 'Archives',
				'conditions' => array(
				'slug' => $this->request->params['slug'],
			)));
			
			if($album) {

				$album_works = Components::find('all', array(
					'fields' => 'archive_id2',
					'conditions' => array('archive_id1' => $album->id),
				));

				$archives_histories = array();

				if ($album_works->count()) {

					//Get all the work IDs in a plain array
					$work_ids = $album_works->map(function($aw) {
						return $aw->archive_id2;
					}, array('collect' => false));
				
					$archives_histories = ArchivesHistories::find('all', array(
						'with' => 'Users',
						'conditions' => array('archive_id' => $work_ids),
						'order' => 'start_date DESC'
					));

				}

				//Send the retrieved data to the view
				return compact('album', 'archives_histories', 'auth');
				
			}
		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Albums::index'));
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
			$album = Albums::first(array(
				'with' => 'Archives',
				'conditions' => array(
				'slug' => $this->request->params['slug'],
			)));
			
			if($album) {
				
				$pdf = $album->archive->slug . '-' . $options['view'] . '.pdf';

				$album_works = Components::find('all', array(
					'fields' => 'archive_id2',
					'conditions' => array('archive_id1' => $album->id),
				));

				$works = array();

				if ($album_works->count()) {

					$work_ids = array();	

					//Get all the work IDs in a plain array
					$work_ids = $album_works->map(function($aw) {
						return $aw->archive_id2;
					}, array('collect' => false));

					$works = Works::find('all', array(
						'with' => 'Archives',
						'conditions' => array('Works.id' => $work_ids),
						'order' => 'earliest_date DESC'
					));

				}

				$view  = new View(array(
					'paths' => array(
						'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
						'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
					)
				));
				echo $view->render(
					'all',
					array('content' => compact('pdf', 'album','works', 'options')),
					array(
						'controller' => 'albums',
						'template'=>'view',
						'type' => 'pdf',
						'layout' => $layout
					)
				);
			}
		}
		
		//since no record was specified, redirect to the index page
		//$this->redirect(array('Albums::index'));
	}

	public function packages() {

	    $check = (Auth::check('default')) ?: null;
	
        if (!$check) {
            return $this->redirect('Sessions::add');
        }
        
		$auth = Users::first(array(
			'conditions' => array('username' => $check['username']),
			'with' => array('Roles')
		));
	
		$test_packages = Packages::all();

		// Some packages may be removed from disk from time to time
		// Therefore, first check if the actual file exists on disk
		// If it does not, remove the record from the database
		foreach ($test_packages as $package) {
			$exists = FileSystem::exists($package->filesystem, $package->name);

			if (!$exists) {
				$package->delete();
			}
		}

		// Find whatever packages are left
		$packages = Packages::find('all', array(
			'with' => 'Albums',
			'order' => array('date_created' => 'DESC')
		));

		//Send the retrieved data to the view
		return compact('auth', 'packages', 'auth');
		
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

			$options = $this->request->query;

			$filesystem = isset($options['filesystem']) ? $options['filesystem'] : 'secure'; 
			
			//Get single record from the database where the slug matches the URL
			$album = Albums::first(array(
				'with' => 'Archives',
				'conditions' => array(
				'slug' => $this->request->params['slug'],
			)));
			
			if($album) {

				$test_packages = Packages::find('all', array(
					'conditions' => array(
					'album_id' => $album->id
				)));

				// Some packages may be removed from disk from time to time
				// Therefore, first check if the actual file exists on disk
				// If it does not, remove the record from the database
				foreach ($test_packages as $package) {
					$exists = FileSystem::exists($package->filesystem, $package->name);

					if (!$exists) {
						$package->delete();
					}
				}

				// Find whatever packages are left
				$packages = Packages::find('all', array(
					'order' => array('date_created' => 'DESC'),
					'conditions' => array(
					'album_id' => $album->id
				)));

				//Send the retrieved data to the view
				return compact('album', 'packages', 'auth');
			}
		
		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Albums::index'));
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
        
			$album = Albums::first(array(
				'with' => 'Archives',
				'conditions' => array(
				'slug' => $this->request->params['slug'],
			)));
		
			if($album) {
		    
				// If the user is not an Admin or Editor, redirect to the record view
				if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
					return $this->redirect(array(
						'Albums::view', 'args' => array($this->request->params['slug']))
					);
				}
				
				// For the following to work, the delete form must have an explicit 'method' => 'post'
				// since the default method is PUT
				if (!$this->request->is('post') && !$this->request->is('delete')) {
					$msg = "Albums::delete can only be called with http:post or http:delete.";
					throw new DispatchException($msg);
				}
		
				$album->delete();
		
			}
		}
		return $this->redirect('Albums::index');
	}

}

?>
