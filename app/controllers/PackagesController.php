<?php

namespace app\controllers;

use app\models\Albums;
use app\models\Works;
use app\models\Publications;
use app\models\Documents;
use app\models\Components;
use app\models\Packages;

use app\models\Users;
use app\models\Roles;

use li3_filesystem\extensions\storage\FileSystem;

use lithium\action\DispatchException;
use lithium\security\Auth;
use lithium\template\View;

use lithium\core\Libraries;

class PackagesController extends \lithium\action\Controller {

	public $rules = array(
		'add' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
		'delete' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
	);

	public function add() {

		$package = Packages::create();

		if (($this->request->data) && $package->save($this->request->data)) {

			// Ask the package where to write it to, and create the directory
			$packages_path = $package->directory();

			if (!file_exists($packages_path)){
				@mkdir($packages_path, 0775, true);
			}

			// Ask the package what path it will be stored at, and create a ZIP file
			$package_path = $package->path();
			$zip = new \ZipArchive();
			$success = $zip->open($package_path, \ZIPARCHIVE::CREATE);

			// Look up the album, and its documents, works, publications, etc.
			$album_id = $package->album_id;

			$album = Albums::find('first', array(
				'with' => 'Archives',
				'conditions' => array('Albums.id' => $album_id)
			));

			$documents = Documents::find('all', array(
				'with' => array(
					'ArchivesDocuments',
					'Formats'
				),
				'conditions' => array(
					'ArchivesDocuments.archive_id' => $album_id,
					'published' => '1',
				),
			));

			$works = Works::find('all', array(
				'with' => array('Archives', 'Components'),
				'conditions' => array(
					'Components.archive_id1' => $album->id,
					'Components.type' => 'albums_works',
				),
				'order' => 'Archives.earliest_date DESC',
			));

			$work_ids = array();

			if ($works->count()) {
				$work_ids = $works->map(function($w) {
					return $w->id;
				}, array('collect' => false));
			}

			$publications = Publications::find('all', array(
				'with' => array('Archives', 'Components'),
				'conditions' => array(
					'Components.archive_id1' => $album->id,
					'Components.type' => 'albums_publications',
				),
				'order' => 'Archives.earliest_date DESC',
			));

			$pub_ids = array();

			if ($publications->count()) {
				$pub_ids = $publications->map(function($p) {
					return $p->id;
				}, array('collect' => false));
			}

			// Collect the IDs for the album, and its components which might have documents
			$archive_ids = array_merge(array($album_id), $work_ids, $pub_ids);

			//Look up the documents for all of these archives which are PUBLISHED
			$all_documents = Documents::find('all', array(
				'with' => array(
					'ArchivesDocuments',
					'Formats'
				),
				'conditions' => array(
					'ArchivesDocuments.archive_id' => $archive_ids,
					'published' => '1',
				),
			));

			// Check where in the filesystem the documents are located
			$documents_config = FileSystem::config('documents');
			$documents_path = $documents_config['path'];

			// Add each document to the ZIP file
			foreach ($all_documents as $document) {
				if ($document->published) {
					$slug = $document->slug;
					$extension = $document->format->extension;
					$document_file = $document->file();

					if (FileSystem::exists('documents', $document_file)) {
						$document_path = $documents_path . DIRECTORY_SEPARATOR . $document_file;
						$document_localname = $slug . '.' . $extension;
						$zip->addFile($document_path, $document_localname);
					}
				}
			}

			// Lay out the PDF file using the Album PDF view
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
				array('content' => compact(
					'pdf',
					'album',
					'works',
					'publications',
					'documents',
					'options'
				)),
				array(
					'controller' => 'albums',
					'template'=>'view',
					'type' => 'pdf',
					'layout' => $layout
				)
			);

			// Add the PDF to the ZIP file
			$zip->addFile($pdf, $album->archive->slug . '.pdf');

			$zip->close();

			// Remove the temporary PDF file
			unlink($pdf);

			// Redirect to the package, which will in effect download it
			return $this->redirect(array('Albums::package', 'slug' => $album->archive->slug));
		}

		return $this->redirect('Albums::index');

	}

	public function delete() {

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
