<?php

class CreateArchivesLinks extends Ruckusing_Migration_Base
{
	public function up()
	{
		$this->rename_table('works_links', 'archives_links');
		$this->rename_column('archives_links', 'work_id', 'archive_id');
		
		$this->execute("INSERT INTO archives_links (archive_id, link_id) SELECT exhibition_id, link_id FROM exhibitions_links");
		$this->execute("INSERT INTO archives_links (archive_id, link_id) SELECT publication_id, link_id FROM publications_links");

		$this->drop_table('exhibitions_links');
		$this->drop_Table('publications_links');

	}//up()

	public function down()
	{
		$t = $this->create_table("publications_links");

		$t->column("publication_id", "integer", array(
			"unsigned" => true,
			"null" => false
		));
		$t->column("link_id", "integer", array(
			"unsigned" => true,
			"null" => false
		));
		$t->finish();

		$t = $this->create_table("exhibitions_links");

		$t->column("exhibition_id", "integer", array(
			"unsigned" => true,
			"null" => false
		));
		$t->column("link_id", "integer", array(
			"unsigned" => true,
			"null" => false
		));
		$t->finish();

		$this->execute("INSERT INTO exhibitions_links (exhibition_id, link_id) SELECT archive_id, link_id FROM archives_links WHERE archive_id in (SELECT id FROM exhibitions)");
		
		$this->execute("INSERT INTO publications_links (publication_id, link_id) SELECT archive_id, link_id FROM archives_links WHERE archive_id in (SELECT id FROM publications)");

		$this->execute("DELETE FROM archives_links WHERE archive_id NOT IN (SELECT id FROM works)");

		$this->rename_table('archives_links', 'works_links');
		$this->rename_column('works_links', 'archive_id', 'work_id');

	}//down()
}
