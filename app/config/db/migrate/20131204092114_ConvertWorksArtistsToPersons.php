<?php

class ConvertWorksArtistsToPersons extends Ruckusing_Migration_Base
{
    public function up()
    {
		// Drop triggers
		$this->execute("DROP TRIGGER WorksHistoriesTableInsert");
		$this->execute("DROP TRIGGER WorksHistoriesTableDelete");
		$this->execute("DROP TRIGGER WorksHistoriesTableUpdate");

		$this->execute("INSERT INTO archives (name, native_name, slug, controller, classification, date_created, date_modified, published) SELECT artist AS name, artist_native_name AS native_name, CONCAT('Artist-', id) as slug, 'artists' AS controller, 'Artist' AS classification, NOW() AS date_created, NOW() AS date_modified, 1 AS published FROM works WHERE artist != '' OR artist_native_name != '' GROUP BY artist, artist_native_name");

		$this->execute("INSERT INTO persons (id) SELECT id FROM archives WHERE controller = 'artists' AND id NOT IN (SELECT id FROM persons)");

		$this->execute("INSERT INTO components (archive_id1, archive_id2, type, role, date_created, date_modified) SELECT archives.id AS archive_id1, works.id AS archive_id2, 'persons_works' AS type, 'artist' AS role, NOW() as date_created, NOW() as date_modified FROM works LEFT JOIN archives ON works.artist = archives.name AND works.artist_native_name = archives.native_name WHERE archives.controller = 'artists'");

		// Remove columns
  		$this->remove_column('works', 'artist');
  		$this->remove_column('works', 'artist_native_name');

		//Create triggers
		$this->execute("CREATE TRIGGER WorksHistoriesTableInsert AFTER INSERT ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO works_histories (work_id, title, creation_number, materials, techniques, color, format, shape, size, state, location, quantity, annotation, inscriptions, height, width, depth, length, circumference, diameter, volume, weight, area, base, running_time, measurement_remarks, attributes, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.creation_number, NEW.materials, NEW.techniques, NEW.color, NEW.format, NEW.shape, NEW.size, NEW.state, NEW.location, NEW.quantity, NEW.annotation, NEW.inscriptions, NEW.height, NEW.width, NEW.depth, NEW.length, NEW.circumference, NEW.diameter, NEW.volume, NEW.weight, NEW.area, NEW.base, NEW.running_time, NEW.measurement_remarks, NEW.attributes, NEW.remarks, N, NULL); END");
		$this->execute("CREATE TRIGGER WorksHistoriesTableDelete AFTER DELETE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; END");
		$this->execute("CREATE TRIGGER WorksHistoriesTableUpdate AFTER UPDATE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; INSERT INTO works_histories (work_id, title, creation_number, materials, techniques, color, format, shape, size, state, location, quantity, annotation, inscriptions, height, width, depth, length, circumference, diameter, volume, weight, area, base, running_time, measurement_remarks, attributes, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.creation_number, NEW.materials, NEW.techniques, NEW.color, NEW.format, NEW.shape, NEW.size, NEW.state, NEW.location, NEW.quantity, NEW.annotation, NEW.inscriptions, NEW.height, NEW.width, NEW.depth, NEW.length, NEW.circumference, NEW.diameter, NEW.volume, NEW.weight, NEW.area, NEW.base, NEW.running_time, NEW.measurement_remarks, NEW.attributes, NEW.remarks, N, NULL); END");	
    }//up()

    public function down()
    {
		// Drop triggers
		$this->execute("DROP TRIGGER WorksHistoriesTableInsert");
		$this->execute("DROP TRIGGER WorksHistoriesTableDelete");
		$this->execute("DROP TRIGGER WorksHistoriesTableUpdate");

		// Add columns
		$this->execute("ALTER TABLE works ADD COLUMN artist VARCHAR(255) NOT NULL AFTER title");
		$this->execute("ALTER TABLE works ADD COLUMN artist_native_name VARCHAR(255) NOT NULL AFTER artist");

		//Create triggers
		$this->execute("CREATE TRIGGER WorksHistoriesTableInsert AFTER INSERT ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO works_histories (work_id, title, creation_number, materials, techniques, color, format, shape, size, state, location, quantity, annotation, inscriptions, height, width, depth, length, circumference, diameter, volume, weight, area, base, running_time, measurement_remarks, attributes, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.creation_number, NEW.materials, NEW.techniques, NEW.color, NEW.format, NEW.shape, NEW.size, NEW.state, NEW.location, NEW.quantity, NEW.annotation, NEW.inscriptions, NEW.height, NEW.width, NEW.depth, NEW.length, NEW.circumference, NEW.diameter, NEW.volume, NEW.weight, NEW.area, NEW.base, NEW.running_time, NEW.measurement_remarks, NEW.attributes, NEW.remarks, N, NULL); END");
		$this->execute("CREATE TRIGGER WorksHistoriesTableDelete AFTER DELETE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; END");
		$this->execute("CREATE TRIGGER WorksHistoriesTableUpdate AFTER UPDATE ON works FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE works_histories SET end_date = N WHERE work_id = OLD.id AND end_date IS NULL; INSERT INTO works_histories (work_id, title, creation_number, materials, techniques, color, format, shape, size, state, location, quantity, annotation, inscriptions, height, width, depth, length, circumference, diameter, volume, weight, area, base, running_time, measurement_remarks, attributes, remarks, start_date, end_date) VALUES (NEW.id, NEW.title, NEW.creation_number, NEW.materials, NEW.techniques, NEW.color, NEW.format, NEW.shape, NEW.size, NEW.state, NEW.location, NEW.quantity, NEW.annotation, NEW.inscriptions, NEW.height, NEW.width, NEW.depth, NEW.length, NEW.circumference, NEW.diameter, NEW.volume, NEW.weight, NEW.area, NEW.base, NEW.running_time, NEW.measurement_remarks, NEW.attributes, NEW.remarks, N, NULL); END");	

		// Update works with artists
		$this->execute("UPDATE works LEFT JOIN (SELECT components.archive_id1 as person_id, components.archive_id2 as work_id, GROUP_CONCAT(if (archives.name = '', null, archives.name) SEPARATOR ', ') as artist, GROUP_CONCAT(if (archives.native_name = '', null, archives.native_name) SEPARATOR ', ') as artist_native_name FROM components LEFT JOIN archives ON components.archive_id1 = archives.id WHERE components.type = 'persons_works' AND role = 'artist' GROUP BY components.archive_id2) AS artists ON artists.work_id = works.id SET works.artist = artists.artist, works.artist_native_name = artists.artist_native_name");

		// Remove components
		$this->query("DELETE FROM components WHERE type = 'persons_works'");

    }//down()
}
