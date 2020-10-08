<?php
class Database {
  // Properties
  private $databaseName;
  private $databaseUsername;
  private $databasePassword;
  public $connection;
  
  // Default Constructor
  function __construct($configurations) {
    $this->connection = new PDO('mysql:host=localhost;dbname='.$configurations['database'], $configurations['username'], $configurations['password']);
  }

  // Get Single Record
  function get_record($lookupKey){
    $stmt = $this->connection->prepare("SELECT * FROM records where name = :lookupKey");
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->bindParam(':lookupKey', $lookupKey, PDO::PARAM_STR, 12);
    $stmt->execute();
    return $stmt;
  }

  // Get All Records
  function get_all_records(){
    $sql = $this->connection->prepare("SELECT * FROM records");
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $sql->execute();
    return $sql;
  }

  function add_record($id){
    $sql = "INSERT INTO records (name) VALUES (?)";
    $this->connection->prepare($sql)->execute([$id]);

  }


  function update_record($id, $customAttribute){
    $sql = "UPDATE records SET attribute=? WHERE id=?";
    $stmt= $this->connection->prepare($sql);
    $stmt->execute([$customAttribute, $id]);

  }

  function delete_record($id){
    $stmt = $this->connection->prepare( "DELETE FROM records WHERE id =:id" );
        $stmt->bindParam(':id', $id);
        $stmt->execute();

  }
}
?>