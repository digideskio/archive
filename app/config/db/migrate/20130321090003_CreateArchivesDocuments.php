<?php

class CreateArchivesDocuments extends Ruckusing_Migration_Base {

  public function up() {

  	$this->rename_table('works_documents', 'archives_documents');
	$this->rename_column('archives_documents', 'work_id', 'archive_id');

	$this->execute("INSERT INTO archives_documents (archive_id, document_id) SELECT architecture_id, document_id FROM architectures_documents");
	$this->execute("INSERT INTO archives_documents (archive_id, document_id) SELECT exhibition_id, document_id FROM exhibitions_documents");
	$this->execute("INSERT INTO archives_documents (archive_id, document_id) SELECT publication_id, document_id FROM publications_documents");

	$this->drop_table('architectures_documents');
	$this->drop_table('exhibitions_documents');
	$this->drop_Table('publications_documents');

  }//up()

  public function down() {

	$t = $this->create_table("architectures_documents");
		
	$t->column("architecture_id", "integer", array(
		"unsigned" => true, 
		"null" => false
	));

	$t->column("document_id", "integer", array(
		"unsigned" => true, 
		"null" => false
	));
	$t->finish();
	  
	$t = $this->create_table("publications_documents");

	$t->column("publication_id", "integer", array(
		"unsigned" => true,
		"null" => false
	));
	$t->column("document_id", "integer", array(
		"unsigned" => true,
		"null" => false
	));
	$t->finish();

	$t = $this->create_table("exhibitions_documents");

	$t->column("exhibition_id", "integer", array(
		"unsigned" => true,
		"null" => false
	));
	$t->column("document_id", "integer", array(
		"unsigned" => true,
		"null" => false
	));
	$t->finish();

	$this->execute("INSERT INTO architectures_documents (architecture_id, document_id) SELECT archive_id, document_id FROM archives_documents WHERE archive_id in (SELECT id FROM architectures)");

	$this->execute("INSERT INTO exhibitions_documents (exhibition_id, document_id) SELECT archive_id, document_id FROM archives_documents WHERE archive_id in (SELECT id FROM exhibitions)");
	
	$this->execute("INSERT INTO publications_documents (publication_id, document_id) SELECT archive_id, document_id FROM archives_documents WHERE archive_id in (SELECT id FROM publications)");

	$this->execute("DELETE FROM archives_documents WHERE archive_id NOT IN (SELECT id FROM works)");

	$this->rename_table('archives_documents', 'works_documents');
	$this->rename_column('works_documents', 'archive_id', 'work_id');

  }//down()
}
?>
