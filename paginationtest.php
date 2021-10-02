<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Pagination</title>
</head>

<body>
	<?php
//connect to database
	$con = mysqli_connect('localhost', 'root', '');
	mysqli_select_db($con, 'baipajoin');

//define how many results you want per page
	$results_per_page = 2;

//find out the number of results stored in database
	$sql = "SELECT * FROM adventure";
	$result = mysqli_query($con, $sql);
	$number_of_results = mysqli_num_rows($result);

	//while($row = mysqli_fetch_array($result)){
	//	echo $row['adv_id'] . ' ' . $row['adv_name'] . '<br>';
	//}

//determine number of total pages available
	$number_of_pages = ceil($number_of_results / $results_per_page);

//determine which page number of visitor is currently on
	if(!isset($_GET['page'])){
		$page = 1;
	}

	else{
		$page = $_GET['page'];

	}

//determine the sql LIMIT starting number for the results on the displaying page
	$this_page_first_result = ($page - 1) * $results_per_page;

//retrieve selected results from database and display them on page
	$sql = "SELECT * FROM adventure LIMIT " . $this_page_first_result . ',' . $results_per_page;
	$result = mysqli_query($con, $sql);

	while($row = mysqli_fetch_array($result)){

		echo $row['adv_id'] . ' ' . $row['adv_name'] . '<br>';
	}

//display the links to the pages
for ($page=1; $page <= $number_of_pages; $page++){
	echo '<a href="paginationtest.php?page='. $page .'">' . $page . '</a> ';

}


	?>
</body>
