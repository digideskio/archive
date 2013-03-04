<?php

namespace app\controllers;

use app\models\Albums;
use app\models\AlbumsWorks;
use app\models\Works;
use app\models\Packages;

use app\models\Users;
use app\models\Roles;

use li3_filesystem\extensions\storage\FileSystem;

use lithium\action\DispatchException;
use lithium\security\Auth;
use lithium\template\View;

use lithium\core\Libraries;

class PackagesController extends \lithium\action\Controller {

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

		$package = Packages::create();

		if (($this->request->data) && $package->save($this->request->data)) {
			$album_id = $package->album_id;
			$filesystem = $package->filesystem;

			$album = Albums::find('first', array(
				'with' => 'Archives',
				'conditions' => array('Albums.id' => $package->album_id)
			));

			$packages_path = $package->directory();
			$package_path = $package->path();

			if (!file_exists($packages_path)){
				@mkdir($packages_path, 0775, true);
			}

			$documents_config = FileSystem::config('documents');
			$documents_path = $documents_config['path'];

			$zip = new \ZipArchive();
			$success = $zip->open($package_path, \ZIPARCHIVE::CREATE);

			$album_works = AlbumsWorks::find('all', array(
				'fields' => 'work_id',
				'conditions' => array('album_id' => $album->id),
			));

			$works = array();

			if($album_works->count()) {

				$work_ids = array();	
			
				//Get all the work IDs in a plain array
				$work_ids = $album_works->map(function($work) {
					return $work->work_id;
				}, array('collect' => false));

				$works = Works::find('all', array(
					'with' => 'Archives',
					'conditions' => array('Works.id' => $work_ids),
					'order' => 'earliest_date DESC'
				));

			}

			foreach ($works as $work) {
				$documents = $work->documents();

				foreach ($documents as $document) {
					if ($document->published) {
						$slug = $document->slug;
						$extension = $document->format->extension;
						$document_file = $document->file();
						$document_path = $documents_path . DIRECTORY_SEPARATOR . $document_file;

						$document_localname = $work->archive->years() . '-' . $work->archive->slug . '-' . $slug . '.' . $extension;

						$zip->addFile($document_path, $document_localname);
					}
				}
			}

			$layout = 'file';
			$options['path'] = $documents_path;
			$options['view'] = 'artwork';
			$pdf = $packages_path . DIRECTORY_SEPARATOR . $album->archive->slug . '.pdf';
			
			$view  = new View(array(
				'paths' => array(
					'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
					'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
				)
			));
			$view->render(
				'all',
				array('content' => compact('pdf', 'album','works', 'options')),
				array(
					'controller' => 'albums',
					'template'=>'view',
					'type' => 'pdf',
					'layout' => $layout
				)
			);

			$zip->addFile($pdf, $album->archive->slug . '.pdf');

			$zip->close();

			unlink($pdf);

			return $this->redirect(array('Albums::package', 'slug' => $album->archive->slug));
		}

		return $this->redirect('Albums::index');

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

        // If the user is not an Admin or Editor, redirect to the index
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect('Albums::index');
        }
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Packages::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}

		$package = Packages::find($this->request->id);

		$album = Albums::find('first', array(
			'with' => 'Archives',
			'conditions' => array('Albums.id' => $package->album_id)
		));

		FileSystem::delete($package->filesystem, $package->name);

		Packages::find($this->request->id)->delete();
		return $this->redirect(array('Albums::package', 'slug' => $album->archive->slug));
	}
}

?>
