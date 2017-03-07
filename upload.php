
<?php
$connect = mysqli_connect('localhost','root','','csv_import_demo'); // First paramater stands for host, Second for Database-user, Third stand for Database-password, Forth Database-name.

if (!$connect) { //Connection is possible using above setting or not
 die('Could not connect to MySQL: ' . mysqli_error());
}

$class="";
$message='';
$error=0;
$target_dir = dirname(__FILE__)."/Uploads/";
if(isset($_POST["import"]) && !empty($_FILES)) {
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
 $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
 if($fileType != "csv")  // here we are checking for the file extension. We are not allowing othre then (.csv) extension .
 {
  $message .= "Sorry, only CSV file is allowed.<br>";
  $error=1;
 }
 elseif (empty($_POST["field_name"])) {
 	$message .= "Please select column to be inserted.<br>";
  $error=1;
 }
 else
 {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
   $message .="File uplaoded successfully.<br>";
   
   if (($getdata = fopen($target_file, "r")) !== FALSE) {
      fgetcsv($getdata);   
      while (($data = fgetcsv($getdata)) !== FALSE) {
     $fieldCount = count($data);
     for ($c=0; $c < $fieldCount; $c++) {
       $columnData[$c] = $data[$c];
     }
   $selected_field = $_POST["field_name"];
    $Id = mysqli_real_escape_string($connect ,$columnData[0]);
    $State = (in_array("state", $selected_field)) ? mysqli_real_escape_string($connect ,$columnData[1]) : '';
    $City = (in_array("city", $selected_field)) ? mysqli_real_escape_string($connect ,$columnData[2]) : '';
    $Dates = (in_array("date", $selected_field)) ? mysqli_real_escape_string($connect ,$columnData[3]) : '';
    $Value = (in_array("value", $selected_field)) ? mysqli_real_escape_string($connect ,$columnData[4]) : '';
    //$selected_field = $_POST["field_name"];
    //echo $selected_field;
    $import_data[]="('".$Id."','".$State."','".$City."','".$Dates."','".$Value."')";
    //print_r($import_data);
   // SQL Query to insert data into DataBase
   
    }
    $import_data = implode(",", $import_data);
   // print_r($import_data);
    //exit(0);
    $query = "INSERT INTO sample(Id,State,City,Dates,Value) VALUES  $import_data ;";
    $result = mysqli_query($connect ,$query);
    $message .="Data imported successfully.";
    fclose($getdata);
   }
    
  } else {
   $message .="Sorry, there was an error uploading your file.";
   $error=1;
  }
 }
}
$class="warning";
if($error!=1)
{
 $class="success";
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> 
<script type="text/javascript" src="d3.v2.min.js"></script>
</head>
<body>
<div class="fluid-container">
  <div class="page-header">
    <h1 align="center">Assignment</h1>      
  </div>
  </div>




<div class="fluid-container" style="margin-top:20px; margin-bottom:20px;padding:10px;">
<?php
 if(!empty($message))
 {
?>
<div class="btn-<?php echo $class;?>" style="width:30%;padding:10px;margin-bottom:20px;">
<?php
  echo $message;
 
 ?>
</div>
<?php } ?>

<form role="form" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post" enctype="multipart/form-data">
<fieldset class="form-group ">
 <div class="form-group">
 <input type="file" name="fileToUpload" id="fileToUpload">
 <label for="image upload" class="control-label">Only .csv file is allowed. </label>
 </div>

 <div class="form-group col-xs-3">
 <select class="form-control " name="field_name[]" multiple>
  <option value="state">State</option>
  <option value="city">City</option>
  <option value="date">Date</option>
  <option value="value">Value</option>
 </select>
  <b>Please select the column which you want to be upload</b><br><br>
    <input type="submit" class="btn btn-warning" value="Upload Csv File" name="import">
 </div>
 </fieldset>
</form>
    
</div>

<?php include 'graph.php'; ?>
</body>
</html>