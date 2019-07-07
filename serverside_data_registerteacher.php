<?php
/* Database connection start */
include('session.php');
/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	
	0 =>'admin_lName', 
	1 => 'admin_fName',
	2 => 'admin_status',
);

// getting total number records without any search
$sql = "SELECT admin_ID, admin_fName, admin_mName, admin_lName, admin_status  ";
$sql.=" FROM user_admin_detail";
$query=mysqli_query($con, $sql) or die("serverside_data_registerstud.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT admin_ID, admin_fName, admin_mName, admin_lName, admin_status  ";
$sql.=" FROM user_admin_detail WHERE 1=1";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( admin_fName LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR admin_mName LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR admin_lName LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR admin_status LIKE '%".$requestData['search']['value']."%' )";
	// $sql.=" OR teacher_department LIKE '%".$requestData['search']['value']."%' )";
}
$query=mysqli_query($con, $sql) or die("serverside_data_registerstud.php: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($con, $sql) or die("serverside_data_registerstud.php: get employees");

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 
	$teacher_ID = $row["admin_ID"];
	$nestedData[] = $row["admin_lName"].', '.$row["admin_fName"].' '.$row["admin_mName"].'.';
	$teacher_department = $row["admin_status"];
	$x = mysqli_query($con,"SELECT `admin_ID` FROM `user_admin_detail` WHERE admin_status = '$teacher_department'");
	$a = mysqli_fetch_array($x);

	$nestedData[] = $a["admin_ID"];
	$nestedData[] = "<div class='btn-group'>  
	 <a type='button' class='btn btn-primary' href='recordteacher_view.php?teacherID=$teacher_ID'><i class='fa fa-eye'></i></a>                                 
	<a  class='btn btn-metis-5' href='recordteacher_edit.php?teacherID=$teacher_ID'><i class='fa fa-edit'></i></a>
	<a  class='btn btn-metis-1' href='recordteacher_delete.php?teacherID=$teacher_ID' ><i class='fa fa-close'></i></a>
                                        </div>";
	
	$data[] = $nestedData;
}




$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
