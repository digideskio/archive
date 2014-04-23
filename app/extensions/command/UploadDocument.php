<?php

namespace app\extensions\command;

use app\models\Documents;
use lithium\storage\Session;
use li3_filesystem\extensions\storage\FileSystem;

class UploadDocument extends \lithium\console\Command {

    public $file;

    public function run() {
		Session::config(array(
			'default' => array('adapter' => 'Php', 'session.name' => 'app')
		));

        $source_path = $this->file;
        $source_file_name = basename($source_path);

        $this->header('Upload Document');
        $this->out('Uploading a document... ' . $source_file_name);

        $documents_config = FileSystem::config('documents');
        $target_dir = $documents_config['path'];
        $target_file_name = preg_replace('/[^\w\._]+/', '_', $source_file_name);

        $target_path = $target_dir . DIRECTORY_SEPARATOR . $target_file_name;

        copy($source_path, $target_path);

        $data = array(
            'file_name' => $source_file_name,
            'file_path' => $target_path
        );

        $document = Documents::create();
        $document->save($data);
        $document_slug = $document->slug;

        $this->out('Uploaded document ' . $document_slug);
    }

}
