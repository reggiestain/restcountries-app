<?php

require_once __DIR__ . '/vendor/autoload.php'; 

$client = new GuzzleHttp\Client();
$res = $client->request('GET', 'https://restcountries.eu/rest/v2/all', [
    'headers' => [
        'User-Agent' => 'testing/1.0',
        'Accept'     => 'application/json',
    ]
]);

$countries = false;

if($res->getStatusCode()){
    $countries = json_decode($res->getBody(), true);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content=""/>
    <title>Country List</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">   
  </head>
  <body>
  <div class="container">
  <br>
  <div class="row">
  <div class="col-md-6 mb-3">
  	<label for="validationCustom">Countries:</label>
      <select id="countries" class="form-control form-control-lg">       
      <option value="">--Select Country--</option>
      <?php 
      if ($countries) {
          foreach ($countries as $country) {
              echo "<option value=".$country['name'].">".$country['name']."</option>";
          }
      }else{
              echo "<option style='color:red'>Response was not found.</option>";
      }
      ?>      
      </select>
  </div>
  <div class="col-md-6 mb-3">
  	<label>Borders:</label>
    <ul id="borders" class="list-group"></ul>
  </div>
  </div>
  </div>
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script> 
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> 
  <script>
   $(document).ready(function(){
    $('#countries').on('change', function() {
        $('#borders').trigger('mousedown')
      var name = this.value;
      console.log(name);
      $.ajax({
        url: "https://restcountries.eu/rest/v2/name/"+name,
        dataType:"json",
        type: "get",
        success: function(data){     
        
           var res = data[0].borders;
           var borders = res.join().split(',');
           var theDiv = document.getElementById("borders");
               theDiv.innerHTML = "";
           for (var b in borders) {
           var newElement = document.createElement('li');
           newElement.id = borders[b]; newElement.className = "list-group-item";
           newElement.innerHTML = borders[b];
           theDiv.appendChild(newElement);
           }         
        },
        error: function(res){

            if (jqXHR.status == 500) {
                $('#borders').html("<li class='list-group-item'>Internal error: "+ jqXHR.responseText+"</li>");
               } else {
                $('#borders').html("<li class='list-group-item'>Unexpected error.</li>");
               }           
        }
    });
    });
   })
  </script> 
  </body>
</html>
