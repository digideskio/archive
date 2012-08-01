<?php

namespace app\controllers;

use app\models\Documents;
use app\models\WorksDocuments;

use app\models\Users;
use app\models\Roles;

use lithium\action\DispatchException;
use lithium\security\Auth;

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
		
		$limit = 20;
        $page = isset($this->request->params['page']) ? $this->request->params['page'] : 1;
        $order = array('date_modified' => 'DESC');
        $total = Documents::count();
        $documents = Documents::find('all', array(
			'with' => array('Formats'),
			'limit' => $limit,
			'order' => $order,
			'page' => $page
		));
		
		return compact('documents', 'page', 'limit', 'total', 'auth');
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
		
			$works_documents = WorksDocuments::find('all', array(
				'with' => 'Works',
				'conditions' => array('document_id' => $document->id)
			));
			
			//Send the retrieved data to the view
			return compact('document', 'works_documents', 'auth');
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
		
		return compact('document');
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
		
		$target_dir = 'files';
		
		$data = compact('target_dir');
		
		$document->delete($data);
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
        
        // HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		// Settings
		$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
		$targetDir = 'files';

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
		if (!file_exists($targetDir))
			@mkdir($targetDir);

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
			
			$data = compact('file_name', 'file_path');
			
			$document = Documents::create($data);
			$document->save();
		}


		// Return JSON-RPC response
		$response = array("jsonrpc" => "2.0", "result" => null, "id" => "id");
		return $this->render(array('json' => $response));
		
	}
}

?>
