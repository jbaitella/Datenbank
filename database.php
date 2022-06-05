<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Database {

    private $host = 'localhost';
    private $person = 'root';
    private $password = 'root';
    private $db = 'db_schauspieler';

    /**
     * Creates a simple database-connection.
     *
     * @return PDO
     */
    private function create_connection() {
        $conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->person, $this->password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    private function check_if_table_exist($connection, $table) {
        try {
            $connection->query("SELECT 1 FROM $table");
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    /**
     * Create person Table
     * ---
     * Checks if "person" table exists already.
     * Creates the table if not already exist.
     *
     * TABLE person:
     *  - person_id
     *  - vorname
     *  - nachname
     *  - password
     *  
     *  
     */
    private function create_person_table() {
        // here: create table if not exist.
        try {
            $conn = $this->create_connection();
            if (!$this->check_if_table_exist($conn, 'person')) {
                // sql to create table
                $sql = "CREATE TABLE person (
                    person_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    password VARCHAR(160) NOT NULL,
                    vorname VARCHAR(40) NOT NULL,
                    nachname VARCHAR(60)";
                // use exec() because no results are returned
                $conn->exec($sql);
                echo "person table created successfully.<br>";
            } else {
                echo "person table already exist.<br>";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $conn = null;
    }

    private function create_geschlecht_table() {
        // here: create geschlecht table if not exist.
        try {
            $conn = $this->create_connection();
            if (!$this->check_if_table_exist($conn, 'geschlecht' )) {
                // sql to create table
                $sql = "CREATE TABLE geschlecht (
                    geschlecht_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    geschlecht VARCHAR(12)";
                // use exec() because no results are returned
                $conn->exec($sql);

                // Add connection between geschlecht and person table.
                $sql = "
                    ALTER TABLE `geschlecht`  
                    ADD CONSTRAINT `FK_geschlecht_person` 
                        FOREIGN KEY (`person_id`) REFERENCES `person`(`person_id`) 
                            ON UPDATE CASCADE 
                            ON DELETE CASCADE;
                    ";
                // use exec() because no results are returned
                $conn->exec($sql);
                echo "geschlecht table created and connected successfully.<br>";
            } else {
                echo "geschlecht table already exist.<br>";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $conn = null;
    }

    

    public function prepare_schauspieler() {
        $this->create_person_table();
        $this->create_geschlecht_table();
        return true;
    }

    public function prepare_login() {
        $this->create_person_table();
        $this->create_geschlecht_table();
        return true;
    }

    public function prepare_registration() {
        $this->create_person_table();
        $this->create_geschlecht_table();
        return true;
    }

    public function login_person($vorname, $password) {
        try {
            $conn = $this->create_connection();
            $query = "SELECT * FROM `person` WHERE vorname = ?";
            $statement = $conn->prepare($query);
            $statement->execute([$vorname]);

            $person = $statement->fetchAll(PDO::FETCH_CLASS);

            if (empty($person)) {
                return false;
            }

            // person exist, we only look at the first entry.
            $person = $person[0];

            $password_saved = $person->password;
            if (!password_verify($password, $password_saved)) {
                return false;
            }

            // remove the password, we don't want to transfer this anywhere.
            unset($person->password);

            return $person;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return false;
    }

    public function register_person($vorname, $password, $nachname=null) {
        // here: insert a new person into the database.
        try {
            $conn = $this->create_connection();
            $query = "SELECT * FROM `person` WHERE vorname = ?";
            $statement = $conn->prepare($query);
            $statement->execute([$vorname]);

            $person = $statement->fetchAll(PDO::FETCH_CLASS);
            if (!empty($person)) {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        // now: save person.
        try {
            $conn = $this->create_connection();

            $sql = 'INSERT INTO person(vorname, password, nachname, register_date, geschlecht)
            VALUES(?, ?, ?, NOW, ?())';
            $statement = $conn->prepare($sql);
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $statement->execute([$vorname, $password_hash, $nachname]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return false;
    }

    public function drop_all() {
        try {
            $conn = $this->create_connection();

            $sql = 'ALTER TABLE `geschlecht`
                DROP FOREIGN KEY `FK_geschlecht_person`;';
            $conn->exec($sql);

            $sql = 'DROP TABLE `person`';
            $conn->exec($sql);

            $sql = 'DROP TABLE `geschlecht`';
            $conn->exec($sql);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return false;
    }
}