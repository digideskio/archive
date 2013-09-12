<?php

class CreatePersonsHistoriesTable extends Ruckusing_Migration_Base {

  public function up() {

  	$t = $this->create_table("persons_histories", array("id" => false));

	$t->column("id", "integer", array("unsigned" => true, "primary_key" => true, "auto_increment" => true));
	
	$t->column("person_id", "integer", array("unsigned" => true, "null" => false));
	$t->column("family_name", "string", array("null" => false));
	$t->column("given_name", "string", array("null" => false));
	$t->column("native_family_name", "string", array("null" => false));
	$t->column("native_given_name", "string", array("null" => false));
	$t->column("sex", "string", array("limit" => 20, "null" => false));
	$t->column("nationality", "string", array("null" => false));
	$t->column("biography", "text", array("null" => false));
	$t->column("remarks", "text", array("null" => false));
	$t->column("roles", "string", array("limit" => 128, "null" => false));
	$t->column("email", "string", array("limit" => 128, "null" => false));
	$t->column("address", "string", array("null" => false));
	$t->column("phone", "string", array("null" => false));

	$t->column("start_date", "integer", array("unsigned" => true));
	$t->column("end_date", "integer", array("unsigned" => true));

  	$t->finish();

	$this->execute("CREATE TRIGGER PersonsHistoriesTableInsert AFTER INSERT ON persons FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); INSERT INTO persons_histories (person_id, family_name, given_name, native_family_name, native_given_name, sex, nationality, biography, remarks, roles, email, address, phone, start_date, end_date) VALUES (NEW.id, NEW.family_name, NEW.given_name, NEW.native_family_name, NEW.native_given_name, NEW.sex, NEW.nationality, NEW.biography, NEW.remarks, NEW.roles, NEW.email, NEW.address, NEW.phone, N, NULL); END;");
	$this->execute("CREATE TRIGGER PersonsHistoriesTableDelete AFTER DELETE ON persons FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE persons_histories SET end_date = N WHERE person_id = OLD.id AND end_date IS NULL; END;");
	$this->execute("CREATE TRIGGER PersonsHistoriesTableUpdate AFTER UPDATE ON persons FOR EACH ROW BEGIN DECLARE N int(11); SET N = UNIX_TIMESTAMP(); UPDATE persons_histories SET end_date = N WHERE person_id = OLD.id AND end_date IS NULL; INSERT INTO persons_histories (person_id, family_name, given_name, native_family_name, native_given_name, sex, nationality, biography, remarks, roles, email, address, phone, start_date, end_date) VALUES (NEW.id, NEW.family_name, NEW.given_name, NEW.native_family_name, NEW.native_given_name, NEW.sex, NEW.nationality, NEW.biography, NEW.remarks, NEW.roles, NEW.email, NEW.address, NEW.phone, N, NULL); END;");
  }//up()

  public function down() {

  	$this->drop_table("persons_histories");

	$this->execute("DROP TRIGGER PersonsHistoriesTableInsert");
	$this->execute("DROP TRIGGER PersonsHistoriesTableDelete");
	$this->execute("DROP TRIGGER PersonsHistoriesTableUpdate");


  }//down()
}
?>
