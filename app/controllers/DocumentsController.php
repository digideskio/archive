<?php

namespace app\controllers;

use app\models\Documents;
use app\models\Albums;
use app\models\Works;
use app\models\Architectures;
use app\models\Exhibitions;
use app\models\Publications;
use app\models\ArchivesDocuments;

use app\models\Users;
use app\models\Roles;

use li3_filesystem\extensions\storage\FileSystem;

use lithium\action\DispatchException;
use lithium\security\Auth;
use lithium\core\Environment;

class DocumentsController extends \lithium\action\Controller {

	protected function _init() {
		// the negotiate option tells li3 to serve up the proper content type
		$this->_render['negotiate'] = true; parent::_init();
	}

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
		
		$conditions = isset($this->request->query['search']) ? array('title' => array('LIKE' => '%' . $this->request->query['search'] . '%')) : null;
		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 20;
        $page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
        $order = array('date_modified' => 'DESC');
        $total = Documents::find('count', array(
			'conditions' => $conditions
		));
        $documents = Documents::find('all', array(
			'with' => array('Formats'),
			'conditions' => $conditions,
			'limit' => $limit,
			'order' => $order,
			'page' => $page
		));

		$search = isset($this->request->query['search']) ? $this->request->query['search'] : '';
		
		return compact('documents', 'page', 'limit', 'total', 'search', 'auth');
	}

	public function search() {

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

		$documents = array();

		$order = array('file_date' => 'DESC');

		$query = '';
		$condition = '';

		$limit = isset($this->request->query['limit']) ? $this->request->query['limit'] : 40;
		$page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
		$total = 0;

		$data = $this->request->data ?: $this->request->query;

		if (isset($data['query']) && $data['query']) {
			$condition = isset($data['condition']) ? $data['condition'] : '';

			$query = $data['query'];

			if ($condition) {
				$conditions = array("$condition" => array('LIKE' => "%$query%"));
			} else {

				$document_ids = array();

				$fields = array('title', 'date_created', 'repository', 'credit', 'remarks');

				foreach ($fields as $field) {
					$matching_docs = Documents::find('all', array(
						'fields' => 'Documents.id',
						'conditions' => array($field => array('LIKE' => "%$query%")),
					));

					if ($matching_docs) {
						$matching_ids = $matching_docs->map(function($md) {
							return $md->id;
						}, array('collect' => false));

						$document_ids = array_unique(array_merge($document_ids, $matching_ids));
					}
				}

				$conditions = $document_ids ?  array('Documents.id' => $document_ids) : array('title' => $query);

			}

			$documents = Documents::find('all', array(
				'with' => array('Formats'),
				'order' => $order,
				'conditions' => $conditions,
				'limit' => $limit,
				'page' => $page
			));

			$total = Documents::count('all', array(
				'conditions' => $conditions,
			));

		}
		return compact('documents', 'condition', 'query', 'total', 'page', 'limit', 'auth');
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
			$document = Documents::first(array(
				'conditions' => array('slug' => $this->request->params['slug']),
				'with' => array('Formats')
			));

			if ($document) {

				$albums = Albums::find('all', array(
					'with' => array('Archives', 'ArchivesDocuments'),
					'conditions' => array('ArchivesDocuments.document_id' => $document->id),
					'order' => 'earliest_date DESC'
				));

				$works = Works::find('all', array(
					'with' => array('Archives', 'ArchivesDocuments'),
					'conditions' => array('ArchivesDocuments.document_id' => $document->id),
					'order' => 'earliest_date DESC'
				));

				$architectures = Architectures::find('all', array(
					'with' => array('Archives', 'ArchivesDocuments'),
					'conditions' => array('ArchivesDocuments.document_id' => $document->id),
					'order' => 'earliest_date DESC'
				));

				$exhibitions = Exhibitions::find('all', array(
					'with' => array('Archives', 'ArchivesDocuments'),
					'conditions' => array('ArchivesDocuments.document_id' => $document->id),
					'order' => 'earliest_date DESC'
				));

				$publications = Publications::find('all', array(
					'with' => array('Archives', 'ArchivesDocuments'),
					'conditions' => array('ArchivesDocuments.document_id' => $document->id),
					'order' => 'earliest_date DESC'
				));

				$architecture = Environment::get('architecture');

				//Send the retrieved data to the view
				return compact(
					'document', 
					'albums',
					'works', 
					'architectures',
					'exhibitions',
					'publications', 
					'architecture',
					'auth'
				);

			}
		}
		
		//since no record was specified, redirect to the index page
		$this->redirect(array('Documents::index'));
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
        	return $this->redirect('Documents::index');
        }
        
		return compact('auth');
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
		
		$document = Documents::first(array(
			'conditions' => array('slug' => $this->request->params['slug'])
		));

		if (!$document) {
			return $this->redirect('Documents::index');
		}
		if (($this->request->data) && $document->save($this->request->data)) {
			return $this->redirect(array('Documents::view', 'args' => array($document->slug)));
		}
		
		// If the database times are zero, just show an empty string in the form
		if($document->file_date == '0000-00-00 00:00:00') {
			$document->file_date = '';
		}

		$order = array('title' => 'ASC');

		$albums = Albums::find('all', array(
			'with' => array('Archives', 'ArchivesDocuments'),
			'conditions' => array('ArchivesDocuments.document_id' => $document->id),
			'order' => $order
		));

		$album_ids = array();

		if ($albums->count()) {
			$album_ids = $albums->map(function($alb) {
				return $alb->id;
			}, array('collect' => false));
		}

		//Find the albums the document is NOT in
		$other_album_conditions = ($album_ids) ? array('Albums.id' => array('!=' => $album_ids)) : '';

		$other_albums = Albums::find('all', array(
			'with' => 'Archives',
			'order' => $order,
			'conditions' => $other_album_conditions
		));

		return compact('document', 'albums', 'other_albums');
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
        
		$document = Documents::first(array(
			'conditions' => array('slug' => $this->request->params['slug']),
		));
        
        // If the user is not an Admin or Editor, redirect to the record view
        if($auth->role->name != 'Admin' && $auth->role->name != 'Editor') {
        	return $this->redirect(array(
        		'Documents::view', 'args' => array($this->request->params['slug']))
        	);
        }
        
        // For the following to work, the delete form must have an explicit 'method' => 'post'
        // since the default method is PUT
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Documents::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		
		$file = $document->file();
		$small = $document->file(array('size' => 'small'));
		$thumb = $document->file(array('size' => 'thumb'));

		FileSystem::delete('documents', $file);
		FileSystem::delete('documents', $small);
		FileSystem::delete('documents', $thumb);

		$document->delete();
		return $this->redirect('Documents::index');
	}
	
	public function upload() {
    
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
        	return $this->redirect('Documents::index');
        }

		//Keep track of the resulting document_id to use in the response
		$document_id = 0;
        
        // HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		// Settings
		$config = FileSystem::config('documents'); 
		$targetDir = $config['path'];

		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds

		// 5 minutes execution time
		@set_time_limit(5 * 60);

		// Uncomment this one to fake upload time
		// usleep(5000);

		// Get parameters
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

		// Keep a record of the original file name
		$originalFileName = $fileName;

		// Clean the fileName for security reasons
		$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);

		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
			$ext = strrpos($fileName, '.');
			$fileName_a = substr($fileName, 0, $ext);
			$fileName_b = substr($fileName, $ext);

			$count = 1;
			while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
				$count++;

			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

		// Create target dir
		if (!file_exists($targetDir)) {
			@mkdir($targetDir, 0775, true);
		}

		// Remove old temp files	
		if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
					@unlink($tmpfilePath);
				}
			}

			closedir($dir);
		} else {
			$response = array(
				"jsonrpc" => "2.0",
				"error" => array(
					"code" => 100, 
					"message" => "Failed to open temp directory."
				),
				"id" => "id"
			);
			return $this->render(array('json' => $response));
		}

		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
			$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

		if (isset($_SERVER["CONTENT_TYPE"]))
			$contentType = $_SERVER["CONTENT_TYPE"];

		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				// Open temp file
				$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = fopen($_FILES['file']['tmp_name'], "rb");

					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else {
						$response = array(
							"jsonrpc" => "2.0",
							"error" => array(
								"code" => 101, 
								"message" => "Failed to open input stream."
							),
							"id" => "id"
						);
						return $this->render(array('json' => $response));
					}
					fclose($in);
					fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				} else {
					$response = array(
						"jsonrpc" => "2.0",
						"error" => array(
							"code" => 101, 
							"message" => "Failed to open output stream."
						),
						"id" => "id"
					);
					return $this->render(array('json' => $response));
				}
			} else {
				$response = array(
					"jsonrpc" => "2.0",
					"error" => array(
						"code" => 104, 
						"message" => "Failed to move uploaded file."
					),
					"id" => "id"
				);
				return $this->render(array('json' => $response));
			}
		} else {
			// Open temp file
			$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen("php://input", "rb");

				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else {
					$response = array(
						"jsonrpc" => "2.0",
						"error" => array(
							"code" => 101, 
							"message" => "Failed to open input stream."
						),
						"id" => "id"
					);
					return $this->render(array('json' => $response));
				}

				fclose($in);
				fclose($out);
			} else {
				$response = array(
					"jsonrpc" => "2.0",
					"error" => array(
						"code" => 101, 
						"message" => "Failed to open output stream."
					),
					"id" => "id"
				);
				return $this->render(array('json' => $response));
			}
		}

		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath);
			
			$file_name = $originalFileName;
			$file_path = $filePath;

			//Check if a document already exists for this file
			$document_id = Documents::findDocumentIdByFile($file_path);

			if (!$document_id) {
			
				$data = compact('file_name', 'file_path');
			
				$document = Documents::create();
				$document->save($data);
				$document_id = $document->id;

			}

			$options = $this->request->query;

			if (isset($options['archive_id'])) {
				$archive_id = $options['archive_id'];

				$data = compact('archive_id', 'document_id');
				$ad = ArchivesDocuments::create();
				$ad->save($data);
			}
		}


		// Return JSON-RPC response
		$response = array("jsonrpc" => "2.0", "result" => $document_id, "id" => "id");
		return $this->render(array('json' => $response));
		
	}
}

?>
