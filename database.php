<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $driver = new mysqli_driver();
    $driver->report_mode = MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX;


// Verbindung zu der Datenbank: db_schauspieler1 herstellen
    $dbconnection = new mysqli("localhost", "root", "root", "db_schauspieler1");


//Verbindung "testen"
    if (!$dbconnection) {
        echo "failed... " . $dbconnection->connect_error;
        exit();
    }


// Bestehende Tabellen loeschen, falls vorhanden = braucht kein "check if table exists"
	$dbconnection->query("DROP TABLE IF EXISTS person");
	$dbconnection->query("DROP TABLE IF EXISTS film_person");
	$dbconnection->query("DROP TABLE IF EXISTS partner");
	$dbconnection->query("DROP TABLE IF EXISTS nationalitaet");
	$dbconnection->query("DROP TABLE IF EXISTS geschlecht");
	$dbconnection->query("DROP TABLE IF EXISTS film");
	
	

// Tabellen erstellen:
//Tabelle nationalitaet
   $create = $dbconnection->prepare("CREATE TABLE nationalitaet(
    nationalitaet_id INT(6) Auto_INCREMENT NOT NULL PRIMARY KEY,
    Land varchar(30) NOT NULL
    );");

    $create->execute();


//Tabelle geschlecht: 
    $create = $dbconnection->prepare("CREATE TABLE geschlecht(
        geschlecht_id INT(6) Auto_INCREMENT NOT NULL PRIMARY KEY,
        geschlecht varchar(30) NOT NULL
        );");


//Tabelle partner:
        $create = $dbconnection->prepare("CREATE TABLE film(
            film_id INT(6) Auto_INCREMENT NOT NULL PRIMARY KEY,
            name_film varchar(75) NOT NULL
            );");    


 // Tabelle 'person' mit 
// person_id, voname, nachnae, Geburtsdatum, Geschlecht, auszeichnungen, Partner, Film, Nationalitaet1&2
        $create = $dbconnection -> prepare ("CREATE TABLE 'person'(
            person_id INT(6) AUTO_INCREMENT NOT NULL PRIMARY KEY,
            vorname VARCHAR (30) NOT NULL,
            nachname VARCHAR (30) NOT NULL,
            geburtsdatum DATE NOT NULL,
            nationalitaet1_id INT (6) NOT NULL,
            nationalitaet2_id  INT(6) DEFAULT 1,
            geschlecht_id INT(6) NOT NULL,
            auszeichnungen INT(6) NOT NULL,
            partner_name VARCHAR (60) DEFAULT '',
            film_id INT(6) DEFAULT '',
            FOREIGN KEY (nationalitaet1_id) REFERENCES nationalitaet(nationalitaet_id),
            FOREIGN KEY (nationalitaet2_id) REFERENCES nationalitaet(nationalitaet_id),
            FOREIGN KEY (geschlecht_id) REFERENCES geschlecht(geschlecht_id),
            FOREIGN KEY (film_id) REFERENCES film(film_id),
        );");
        $create-> execute();


//Tabelle film_person erstellen:   
        $create = $dbconnection->prepare("CREATE TABLE film_person(
            film_person_id INT(6) Auto_INCREMENT NOT NULL PRIMARY KEY,
            person_id INT(6) NOT NULL,
            film_id INT(6) NOT NULL,
            FOREIGN KEY (person_id) REFERENCES person(person_id),
            FOREIGN KEY (film_id) REFERENCES film(film_id)
            );");
        
        $create->execute();
   

//InhaltNationalitaet: 1: Deutschland / 2: Australien/ 3:Grossbritanien / 4: Amerika / 5: Südkorea
	$insertnationalitaet = $dbconnection->prepare("INSERT INTO Nationalitaet
		(Land)
		VALUES ('');");
	$insertnationalitaet->execute();
	$insertnationalitaet = $dbconnection->prepare("INSERT INTO Nationalitaet
		(Land)
		VALUES ('Deutschland');");
	$insertnationalitaet->execute();
	$insertnationalitaet = $dbconnection->prepare("INSERT INTO Nationalitaet
		(Land)
		VALUES ('Australien');");
	$insertnationalitaet->execute();
	$insertnationalitaet = $dbconnection->prepare("INSERT INTO Nationalitaet
		(Land)
		VALUES ('Grossbritanien');");
	$insertnationalitaet->execute();
	$insertnationalitaet = $dbconnection->prepare("INSERT INTO Nationalitaet
		(Land)
		VALUES ('Amerika');");
	$insertnationalitaet->execute();
	$insertnationalitaet = $dbconnection->prepare("INSERT INTO Nationalitaet
		(Land)
		VALUES ('Südkorea');");
	$insertNationalitaet->execute();


//inhalt geschlecht: 0: männlich / 1: weiblich
    $insertgeschlecht = $dbconnection->prepare("INSERT INTO geschlecht
        (geschlecht)
        VALUES ('männlich');");
    $insertgeschlecht->execute();

    $insertgeschlecht = $dbconnection->prepare("INSERT INTO geschlecht
        (geschlecht)
        VALUES ('weiblich');");
    $insertgeschlecht->execute();


//inhalt film: 1:ocean 8 
    $insertfilm = $dbconnection-> prepare ("INSERT INTO film 
        (film)
        VALUES ('');");
    $insertfilm->execute();

    $insertfilm = $dbconnection-> prepare ("INSERT INTO film 
    (film)
    VALUES ('Ocean's eight');");
$insertfilm->execute();
    
    
//Inhalt person
    $insertperson = $dbconnection->prepare("INSERT INTO person
        (vorname, nachname, geburtsdatum, nationaitaet1_id, nationalitaet2_id, geschlecht_id, auszeichungen, partner_name, film_id )
        VALUES ('anne', 'heathaway', '12.11.1982', 4, 0, 1, '40', 'Adam Shulman', 1);");
    $insertperson->execute();

    $insertperson = $dbconnection->prepare("INSERT INTO person
        (vorname, nachname, geburtsdatum, nationaitaet1_id, nationalitaet2_id, geschlecht_id, auszeichungen, partner_name, film_id )
        VALUES ('sandra', 'bullock', '26.07.1964', 1, 4, 1, '76', 'Bryan Randall', 1);");
    $insertperson->execute();

    $insertperson = $dbconnection->prepare("INSERT INTO person
        (vorname, nachname, geburtsdatum, nationaitaet1_id, nationalitaet2_id, geschlecht_id, auszeichungen, partner_name, film_id )
        VALUES ('cate', 'blanchett', '14.05.1969', 2, 4, 1, '162', Andrew Upton, 1);");
    $insertperson->execute();


// drop all definition
    public function drop_all() {
        try {
            $conn = $this->create_connection();

            $sql = 'ALTER TABLE `geschlecht`
                DROP FOREIGN KEY `FK_geschlecht_id`;';
            $conn->exec($sql);
		
            $sql = 'ALTER TABLE `nationalitaet1`
                DROP FOREIGN KEY `FK_nationalitaet1_id`;';
            $conn->exec($sql);
		
            $sql = 'ALTER TABLE `nationalitaet2`
                DROP FOREIGN KEY `FK_nationalitaet2_id`;';
            $conn->exec($sql);
		
            $sql = 'ALTER TABLE `film`
                DROP FOREIGN KEY `FK_film_id`;';
            $conn->exec($sql);

            $sql = 'DROP TABLE `person`';
            $conn->exec($sql);

            $sql = 'DROP TABLE `geschlecht`';
            $conn->exec($sql);

            $sql = 'DROP TABLE `nationalitaet1`';
            $conn->exec($sql);

		
            $sql = 'DROP TABLE `nationalitaet2`';
            $conn->exec($sql);
v

            $sql = 'DROP TABLE `film_person`';
            $conn->exec($sql);

            $sql = 'DROP TABLE film';
            $conn->exec($sql);
		
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return false;
   
