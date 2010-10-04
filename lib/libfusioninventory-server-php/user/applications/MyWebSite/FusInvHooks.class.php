<?php
require_once "Changes.class.php";

/**
* Hooks Contract
*/
interface IExistingHooks
{
    /* Inventory */
    public static function createMachine();
    public static function addSections($data, $idmachine);
    public static function removeSections($idsections, $idmachine);
}


/**
* User defines hooks in this class.
* There are three hooks to define: createMachine, addSection, removeSection
*/
class Hooks implements IExistingHooks
{

    /**
    * Disable instance
    * @access private
    *
    */
    private function __construct()
    {
    }

    private static function _createDatabase()
    {
        if (!file_exists(dirname(__FILE__).'/inventory.sqlite3'))
        {
            $dbh = new PDO('sqlite:'.dirname(__FILE__).'/inventory.sqlite3');
            $dbh->beginTransaction();
            $dbh->query('CREATE TABLE machine(idmachine INTEGER PRIMARY KEY AUTOINCREMENT, time)');

            $dbh->query('CREATE TABLE section(
            idsection INTEGER PRIMARY KEY AUTOINCREMENT,
            sectionName NOT NULL,
            sectionData,
            idmachine INTEGER NOT NULL CONSTRAINT fk_idmachine REFERENCES machine(idmachine))');

            $dbh->query('CREATE TABLE changeslog(
            idchange INTEGER PRIMARY KEY AUTOINCREMENT,
            nbAddedSections INTEGER,
            nbRemovedSections INTEGER,
            time,
            idmachine INTEGER NOT NULL CONSTRAINT fk2_idmachine REFERENCES machine (idmachine))');
            $dbh->commit();
        }
    }

    /**
    * create a new machine in an application
    * @access public
    * @return int $externalId Id to match application data with the library
    */
    public static function createMachine()
    {

        self::_createDatabase();

        $dbh = new PDO('sqlite:'.dirname(__FILE__).'/inventory.sqlite3');
        $stmt = $dbh->prepare("INSERT INTO machine (time) VALUES (:date)");

        $timestamp = time();
        $stmt->bindParam(':date', $timestamp);
        $stmt->execute();

        $idmachine = $dbh->lastInsertId();

        //changes log
        $changes = new Changes($dbh);
        $changes->notifyAddedMachine($idmachine);

        return $idmachine;

    }

    /**
    * add new sections to the machine in an application
    * @access public
    * @param array $datas(sectionName, dataSection)
    * @param int $idmachine
    * @return array $sectionsId
    */
    public static function addSections($datas, $idmachine)
    {
        $sectionsId = array();

        $dbh = new PDO('sqlite:'.dirname(__FILE__).'/inventory.sqlite3');

        $dbh->beginTransaction();

        foreach($datas as $section)
        {
            // You can specify an table name for software section.
            $stmt = $dbh->prepare("INSERT INTO section (sectionName, sectionData, idmachine) VALUES (:sectionName, :dataSection, :externalId)");
            $stmt->bindParam(':sectionName', $section['sectionName']);
            $stmt->bindParam(':dataSection', $section['dataSection']);
            $stmt->bindParam(':externalId', $idmachine);
            $stmt->execute();

            array_push($sectionsId, $dbh->lastInsertId());
        }

        $dbh->commit();

        //changes log
        $changes = new Changes($dbh);
        $changes->notifyAddedSection($idmachine, count($sectionsId));

        return $sectionsId;
    }


    /**
    * remove a machine's section in an application
    * @access public
    * @param array $idsections
    * @param int $idmachine
    */
    public static function removeSections($idsections, $idmachine)
    {

        $dbh = new PDO('sqlite:'.dirname(__FILE__).'/inventory.sqlite3');
        $dbh->beginTransaction();
        foreach($idsections as $idsection)
        {
            $stmt = $dbh->prepare("DELETE FROM section WHERE idsection = :idsection");
            $stmt->bindParam(':idsection', $idsection);
            $stmt->execute();
        }
        $dbh->commit();

        //changes log
        $changes = new Changes($dbh);
        $changes->notifyRemovedSection($idmachine, count($idsections));
    }

    /**
    * update a machine's section
    * @access public
    * @param array $datas(sectionId, dataSection)
    * @param int $idmachine
    */
    public static function updateSections($datas, $idmachine)
    {
        $dbh = new PDO('sqlite:'.dirname(__FILE__).'/inventory.sqlite3');

        $dbh->beginTransaction();

        foreach($datas as $section)
        {
            // You can specify an table name for software section.
            $stmt = $dbh->prepare("UPDATE section SET sectionData = :dataSection WHERE idsection = :idsection");
            $stmt->bindParam(':idsection', $section['sectionId']);
            $stmt->bindParam(':dataSection', $section['dataSection']);
            $stmt->execute();
        }

        $dbh->commit();
    }

}

?>
