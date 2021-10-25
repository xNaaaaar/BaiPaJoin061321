<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(isset($_GET['table'])) {
		if($_GET['table'] == 'legal_document'){
			// DELETE TABLE IN LEGAL DOCUMENT
			deleteSQLDataTable($_GET['table'], $_GET['image']);
			//
			header("Location: settings.php?deleted=1");
		}
		else if($_GET['table'] == 'adventure'){
			// DELETE TABLE IN ADVENTURE
			deleteSQLDataTable($_GET['table'], $_GET['id']);
			//
			header("Location: adventures_posted.php?deleted=1");
		}
		else if($_GET['table'] == 'voucher'){
			// DELETE TABLE IN ADVENTURE
			deleteSQLDataTable($_GET['table'], $_GET['id']);
			//
			header("Location: voucher.php?deleted=1");
		}
		else if($_GET['table'] == 'booking' && isset($_GET['triggers'])){
			// DELETE TABLE IN BOOKING WITH WAITING FOR PAYMENT STATUS
			deleteSQLDataTable($_GET['table'], $_GET['book_id'], "waiting for payment");
			//
			header("Location: reports_booking.php?exp_suc");
		}
		else if($_GET['table'] == 'booking'){
			// DELETE TABLE IN BOOKING
			deleteSQLDataTable($_GET['table'], $_GET['id']);
			//
			header("Location: book.php?id={$_GET['adv']}");
		}
		else {

		}
	}
?>
