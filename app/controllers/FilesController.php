<?php

namespace app\controllers;

use app\models\Documents;

use li3_filesystem\extensions\storage\FileSystem;

use lithium\action\DispatchException;
use lithium\security\Auth;

class FilesController extends \lithium\action\Controller {

	public $rules = array(
		'view' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'small' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'thumb' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'download' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
		'package' => array(
			array('rule' => 'allowAll', 'redirect' => "Sessions::add"),
		),
		'secure' => array(
			array('rule' => 'allowAnyUser', 'redirect' => "Sessions::add"),
		),
	);

	public function __construct(array $config = array()) {
		$defaults = array('render' => array('auto' => false));
		return parent::__construct($config + $defaults);
	}

	public function view() {

        //Don't run the query if no slug is provided
		if (isset($this->request->params['file'])) {

			$file = $this->request->params['file'];

			$ext = strrpos($file, '.');
			$slug = substr($file, 0, $ext);

			$document = Documents::first(array(
				'conditions' => array('slug' => $slug),
				'with' => array('Formats')
			));

			if ($document) {

				$filename = $document->file();

				if (FileSystem::exists('documents', $filename)) {
					$this->response->headers(array('Content-type' => $document->format->mime_type));
					$this->response->body = FileSystem::read('documents', $filename);

					return compact('file');

				}
			}

			$this->redirect(array('Documents::index'));

		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Documents::index'));
	}

	public function small() {

        //Don't run the query if no slug is provided
		if (isset($this->request->params['file'])) {

			$file = $this->request->params['file'];

			$ext = strrpos($file, '.');
			$slug = substr($file, 0, $ext);
			$px = '560';

			$document = Documents::first(array(
				'conditions' => array('slug' => $slug),
			));

			if ($document) {

				$filename = $document->file(array('size' => 'small'));

				if ( FileSystem::exists('documents', $filename) ) {
					$this->response->headers(array('Content-type' => 'image/jpeg'));
					$this->response->body = FileSystem::read('documents', $filename);

					return compact('file');

				}
			}

			$this->redirect('/', array('status' => '404'));

		}

		$this->redirect('/', array('status' => '404'));
	}

	public function thumb() {

        //Don't run the query if no slug is provided
		if (isset($this->request->params['file'])) {

			$file = $this->request->params['file'];

			$ext = strrpos($file, '.');
			$slug = substr($file, 0, $ext);

			$document = Documents::first(array(
				'conditions' => array('slug' => $slug),
			));

			if ($document) {

				$filename = $document->file(array('size' => 'thumb'));

				if ( FileSystem::exists('documents', $filename) ) {
					$this->response->headers(array('Content-type' => 'image/jpeg'));
					$this->response->body = FileSystem::read('documents', $filename);

					return compact('file');

				}
			}

			$this->redirect('/', array('status' => '404'));

		}

		$this->redirect('/', array('status' => '404'));
	}

	public function download() {

        //Don't run the query if no slug is provided
		if (isset($this->request->params['file'])) {

			$file = $this->request->params['file'];

			$ext = strrpos($file, '.');
			$slug = substr($file, 0, $ext);

			$document = Documents::first(array(
				'conditions' => array('slug' => $slug),
				'with' => array('Formats')
			));

			if ($document) {

				$filename = $document->file();

				$config = FileSystem::config('documents');

				$send_file = $config['path'] . DIRECTORY_SEPARATOR . $filename;

				if (FileSystem::exists('documents', $filename)) {

					$this->response->headers(array(
						'X-Sendfile' => $send_file,
						'Content-type' => 'application/octet-stream',
						'Content-Disposition' => 'attachment; filename="' . $file . '"'
					));

					return compact('file');

				}
			}

			$this->redirect(array('Documents::index'));

		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Documents::index'));
	}

	public function package() {

		if (isset($this->request->params['file'])) {

			$filename = $this->request->params['file'];

			$config = FileSystem::config('packages');

			$send_file = $config['path'] . DIRECTORY_SEPARATOR . $filename;

			if (FileSystem::exists('packages', $filename)) {

				$this->response->headers(array(
					'X-Sendfile' => $send_file,
					'Content-type' => 'application/octet-stream',
					'Content-Disposition' => 'attachment; filename="' . $filename . '"'
				));

				return compact('file');

			}

			$this->redirect(array('Documents::index'));
		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Documents::index'));
	}

	public function secure() {

		if (isset($this->request->params['file'])) {

			$filename = $this->request->params['file'];

			$config = FileSystem::config('secure');

			$send_file = $config['path'] . DIRECTORY_SEPARATOR . $filename;

			if (FileSystem::exists('secure', $filename)) {

				$this->response->headers(array(
					'X-Sendfile' => $send_file,
					'Content-type' => 'application/octet-stream',
					'Content-Disposition' => 'attachment; filename="' . $filename . '"'
				));

				return compact('file');

			}

			$this->redirect(array('Documents::index'));
		}

		//since no record was specified, redirect to the index page
		$this->redirect(array('Documents::index'));
	}
}

?>
