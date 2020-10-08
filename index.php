<?php

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


$configurations = include ("appConfig.php");
require_once ('lib/Database.php');

$db = new Database($configurations);

if (isset($_POST["name"])){
    $value = $_POST["name"];
    $db->add_record($value);
}

?>


<link rel="stylesheet" href="https://bootswatch.com/4/litera/bootstrap.min.css"></link>



<nav class="navbar navbar-expand-lg navbar-light bg-light mb-5">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarColor03">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Some Heading</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Another Heading</a>
      </li>
      
    </ul>
    <ul>
    <li class="nav-item">
        <a class="nav-link" href="/SampleApplication/logout.php">Logout</a>
      </li>
    </ul>

    
  </div>
</nav>


<div class="container">


<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home">Search Record</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#profile">Add Record</a>
  </li>
 
</ul>
<div id="myTabContent" class="tab-content mt-5 mb-5">
  <div class="tab-pane fade show active" id="home">
  <form action="index.php">
  <div class="form-group">
    <label for="email">Name:</label>
    <input type="text" id="name" name="name" onkeyup="showHint(this.value)">
  </div>
  <div class="form-group">
    <input type="submit" value="Search!">
  </div>
  </form>
    </div>

  <div class="tab-pane fade mt-5 mb-5" id="profile">
  <form method="post" action="index.php">
  <div class="form-group">
    <label for="email">Name:</label>
    <input type="text" id="name" name="name" onkeyup="showHint(this.value)">
  </div>
  <div class="form-group">
    <input type="submit" value="Add!">
  </div>
  </form>

    
    </div>
</div>




<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Record ID</th>
      <th scope="col">Record Name</th>
      <th scope="col">Record Custom Attribute</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php

  
  if (!isset($_GET["name"]) || $_GET["name"] == ""){
    $result = $db->get_all_records();
  }
  else{
    $lookupKey = $_GET["name"];
    $result = $db->get_record($lookupKey);
  }

  while ($row = $result->fetch())
    {
        echo "<tr>";
        echo "<td id='record_id_".$row['id']."'>".$row['id']."</td>";
        echo "<td>".$row['name']."</td>";
        echo "<td><input disabled id='field_id_".$row['id']."' type='text' value='" . $row['attribute'] . "'></td>";
        echo "<td><button id='update_".$row['id']."' type='button' class='btn btn-primary updateRecord'>Update</button><button id='delete_".$row['id']."' type='button' class='btn btn-danger deleteRecord'>Delete</button></td>";
        echo "</tr>";
    }

?>
</tbody>
</table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script> 

        $(document).on('click','.deleteRecord',function() {
            var field = "field_id_" + this.id.substr(this.id.indexOf("_") + 1);
            var recordId = "record_id_" + this.id.substr(this.id.indexOf("_") + 1);
            var recordToDelete = new Object();
            recordToDelete.id = $("#" + recordId).text();
            var formData = JSON.stringify(recordToDelete);
            $.ajax({
                url : "/SampleApplication/api/delete.php", // Url of backend (can be python, php, etc..)
                type: "DELETE", // data type (can be get, post, put, delete)
                data : formData, // data in json format
                async : false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
                success: function(response, textStatus, jqXHR) {
                    console.log(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        });
    
        $(document).on('click','.updateRecord',function() {
            alert("asdf");
            var id = this.id;
            id = id.substr(id.indexOf("_") + 1)
            var field = "field_id_" + id;
            alert(field)
            $("#field_id_"+id).prop("disabled", false);

            $(this).html('Save');

            $(this).removeClass('updateRecord');
            $(this).addClass('saveRecord');
        });

        $(document).on('click','.saveRecord',function() {
            var field = "field_id_" + this.id.substr(this.id.indexOf("_") + 1);
            var recordId = "record_id_" + this.id.substr(this.id.indexOf("_") + 1);
            var recordToSave = new Object();
            alert($("#" + recordId).text());
            recordToSave.id = $("#" + recordId).text();
            recordToSave.customAttribute = $("#" + field).val()
            var formData = JSON.stringify(recordToSave);
            $.ajax({
                url : "/SampleApplication/api/update.php", // Url of backend (can be python, php, etc..)
                type: "POST", // data type (can be get, post, put, delete)
                data : formData, // data in json format
                async : false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
                success: function(response, textStatus, jqXHR) {
                    console.log(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });

            $(this).removeClass('saveRecord');
            $(this).addClass('updateRecord');
            $("#" + field).prop("disabled", true);
            $(this).html('Update');
        });

        
    </script>

