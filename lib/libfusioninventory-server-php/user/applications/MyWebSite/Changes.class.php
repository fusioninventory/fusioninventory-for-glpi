<?php
class Changes
{

    private $_dbh;

    public function __construct($dbh)
    {
        $this->_dbh = $dbh;
    }

    public function notifyAddedMachine($idmachine)
    {
        $stmt = $this->_dbh->prepare("INSERT INTO changeslog (idmachine) VALUES (:idmachine)");
        $stmt->bindParam(':idmachine', $idmachine);
        $stmt->execute();
    }

    public function notifyAddedSection($idmachine, $nbAddedSections)
    {
        $stmt = $this->_dbh->prepare("UPDATE changeslog SET nbAddedSections=:nbAddedSections, time=:time WHERE idmachine=:idmachine");
        $stmt->bindParam(':nbAddedSections', $nbAddedSections);
        $timestamp = time();
        $stmt->bindParam(':time', $timestamp);
        $stmt->bindParam(':idmachine', $idmachine);
        $stmt->execute();

    }

    public function notifyRemovedSection($idmachine, $nbRemovedSections)
    {
        $stmt = $this->_dbh->prepare("UPDATE changeslog SET nbRemovedSections=:nbRemovedSections, time=:time WHERE idmachine=:idmachine");
        $stmt->bindParam(':nbRemovedSections', $nbRemovedSections);
        $timestamp = time();
        $stmt->bindParam(':time', $timestamp);
        $stmt->bindParam(':idmachine', $idmachine);
        $stmt->execute();

    }
}
?>
