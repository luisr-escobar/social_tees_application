<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>View Records</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen" />
</head>
<body>


<ul>
<li><a href="index.php">HOME</a></li>
<li><a href="search_record.php">SEARCH</a></li>
<li><a href="search_record.php">ADD APPLICANT</a></li>
</ul>


<div id="container1">

<h1>Dog Adoption Application Records</h1>


<form action='' method='POST' id='status_form'>
<input type='hidden' name='id' value='".$row->id."'>
<b>Order By: </b> <select name='order'>
  <option value=''>Select...</option>
  <option value='id'>ID</option>
  <option value='firstname'>First Name</option>
  <option value='lastname'>Last Name</option>
  <option value='date DESC'>Processing Date</option>
  <option value='status'>Application Status</option>
</select>

<input type='submit' name='orderby_submit' value='Submit'/>
</form><br>


<?php
error_reporting('E_ALL ^ E_NOTICE');

// connect to the database
include('connect-db.php');

$order_selected = htmlentities($_POST['order'], ENT_QUOTES);

if ($order_selected == ''){
$order_selected = 'id';
}

$page=$_REQUEST['p'];
$limit=5;
if($page=='')
{
 $page=1;
 $start=0;
}
else
{
 $start=$limit*($page-1);
}
$query=$mysqli->query("SELECT * from applicant_info ORDER BY " .$order_selected." limit $start, $limit");
$count_total=mysqli_num_rows($query);
$first_record=max($count_total);
$tot=$mysqli->query("SELECT * from applicant_info");
$total=mysqli_num_rows($tot);
$num_page=ceil($total/$limit);



echo '<p>Showing ' . $count_total . ' of ' . $total . ' records</p>';

echo "<table id='applicants'>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Address</th>
    <th>Phone</th>
    <th>Email</th>
    <th>Status</th>
    <th>Processing Date</th>
    <th>Email Sent</th>
  </tr>";


while ($row = $query->fetch_array())
{

//change date format
$originalDate = $row['date'];
$newDate = date("m/d/Y", strtotime($originalDate));


echo "<tr data-href='profile.php'>";
echo "<td>" . $row['id'] . "</td>";
echo "<td><a href='profile.php?id=" . $row['id'] . "'>".$row['firstname'] . " " . $row['lastname'] . "</a></td>";

echo "<td>" . $row['address'] . "</br>" .$row['city'] .  ", " . $row['state'] . " " . $row['zip'] . "</td>";

echo "<td>(".substr($row['phone'], 0, 3).") ".substr($row['phone'], 3, 3)."-".substr($row['phone'],6)."</td>";
echo "<td>" . $row['email'] . "</td>";

echo "<td>" . $row['status'] . "</td>";

if($row['status'] == "approved" || $row['status'] == "rejected"){
echo "<td>" . $newDate . "</td>";

}
else{
echo "<td></td>";
}

if($row->email_sent == "true"){
echo "<td>Yes</td>";


}
else{
echo "<td>No</td>";
}

echo "</tr>";
}
echo'</table>';

function pagination($page,$num_page)
{
  echo'<ul id="test1" style="list-style-type:none;">';
  for($i=1;$i<=$num_page;$i++)
  {
     if($i==$page)
{
 echo'<li id="test" style="float:left;">'.$i.'</li>';
}
else
{
 echo'<li id="test2" style="float:left;padding:0px;"><a id="test2" href="pagination.php?p='.$i.'">'.$i.'</a></li>';
}
  }
  echo'</ul>';
}
if($num_page>1)
{
 pagination($page,$num_page);
}
?> 

</div>





</body>
</html>
