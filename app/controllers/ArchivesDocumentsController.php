<?php

namespace app\controllers;

use app\models\ArchivesDocuments;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

class ArchivesDocumentsController extends \lithium\action\Controller {

	public $rules = array(
		'add' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
		'delete' => array(
			array('rule' => 'allowEditorUser', 'redirect' => "Pages::home"),
		),
	);

	public function add() {

		if ($this->request->data) {

			$archive_id = $this->request->data['archive_id'];
			$document_id = $this->request->data['document_id'];

			$archive_document = ArchivesDocuments::find('first', array(
				'conditions' => compact('archive_id', 'document_id'),
			));

			if (!$archive_document) {

				$archive_document = ArchivesDocuments::create();
				$archive_document->save($this->request->data);

			}
		}

		return $this->redirect($this->request->env('HTTP_REFERER'));
	}


	public function delete() {

		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Components::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}

		$archive_document = ArchivesDocuments::find($this->request->id);
		
		if ($archive_document) {
			$archive_document->delete();
		}

		return $this->redirect($this->request->env('HTTP_REFERER'));
	}
}

?>
