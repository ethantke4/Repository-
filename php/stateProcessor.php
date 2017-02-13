<?php

// Start Session
session_start();

// Error Reporting
error_reporting(0);

// Variable grab from POST.
$operation = $_POST['operation'];

// Server Variables.
$host = 'SQL Host Name Here';
$dbname = 'DataBase Name';
$user = 'username';
$pass = 'password';

// Connection attempt to SQL.
try {
  // MySQL with PDO_MYSQL.
  $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    echo $e->getMessage();
}


// Operation Junction
switch($operation){
	case 0: // Approved State Request
		echo approvedStateQuery($db);
		break;
	case 1: // NonResidency Request
		echo nonResidenceQuery($db);
		break;
	case 2: // Request all NonResidency
		echo nonRedisenceQueryAll($db);
		break;	
	case 3: // Post all NonResidency Issuers
		echo nonResidencePostAll($db);
		break;	
	case 4: // Request all Non-Residential Reciprocity
		echo nonResidenceReciprocityQueryAll($db);
		break;	
	case 5: // Post all Non-Residential Reciprocity
		echo nonResidenceReciprocityPostAll($db);
		break;	
	case 6: // Request all Reciprocity
		echo reciprocityQueryAll($db);
		break;	
	case 7: // Post all Reciprocity
		echo reciprocityPostAll($db);
		break;
	case 8: // Authentication
		echo authenticator($db);
		break;
}

function approvedStateQuery($db){
	$data = json_decode($_POST['data']);
	$residentialLicense = $data->residentialLicense;
	$nonResidentialLicenses = $data->nonResidentialLicense;
	
	// SQL Query Building.
	$sql = " SELECT DISTINCT `reciprocityState` FROM `tbl_reciprocity` WHERE";
	if($residentialLicense !== ""){
		$sql .= " (`licensedState` = '$residentialLicense' AND `residentialAcceptance` = 'TRUE') ";
		if(count($nonResidentialLicenses) > 0){
			$sql .= "OR ";
		}
	}
	if(count($nonResidentialLicenses) > 0){
		foreach($nonResidentialLicenses as $key => $value){
				if($key > 0){
					$sql .= " OR ";	
				}
				$sql .= "(`licensedState` = '$value' AND `nonResidentialAcceptance` = 'TRUE')"; 
		}
	}
	
	// SQL Result
//	applicationLog($sql);
	$sth = $db->query($sql);
	$results = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
	return json_encode($results);
}

function nonResidenceQuery($db){
	$clickedState = $_POST['clickedState'];
	$sql = "SELECT `nonResidentialAllowed` FROM `tbl_nonresidential` WHERE `state` = '$clickedState'";
	$sth = $db->query($sql);
	$results = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
	return json_encode($results[0]);
}

function nonRedisenceQueryAll($db){
	$sql = "SELECT * FROM `tbl_nonresidential`";
	$sth = $db->query($sql);
	$results = $sth->fetchAll(PDO::FETCH_ASSOC);
	return json_encode($results);
}

function nonResidencePostAll($db){
	if(isset($_SESSION['authenticated'])){
		$dataGrid = json_decode($_POST['data']);
		$dataGridLength = count($dataGrid);
		$sql = "UPDATE `tbl_nonresidential` SET `nonResidentialAllowed` = CASE `state` ";				
		for($i = 0; $i < $dataGridLength; $i++){
			$state = $dataGrid[$i][0];
			$acceptance = $dataGrid[$i][1];
			$sql .= "WHEN '$state' THEN '$acceptance' ";	
		}
		$sql .= "END";
		$sth = $db->query($sql);
	} else {

	}
}

function nonResidenceReciprocityQueryAll($db){
	$filteredState = $_POST['filteredState'];
	$sql = "SELECT `licensedState`,`reciprocityState`,`nonResidentialAcceptance` FROM `tbl_reciprocity` WHERE `licensedState` = '$filteredState'";
	$sth = $db->query($sql);
	$results = $sth->fetchAll(PDO::FETCH_ASSOC);
	return json_encode($results);	
}

function nonResidenceReciprocityPostAll($db){
	if(isset($_SESSION['authenticated'])){
		$licenseState = $_POST['filteredState'];
		$dataGrid = json_decode($_POST['data']);
		$dataGridLength = count($dataGrid);
		$sql = "UPDATE `tbl_reciprocity` SET `nonResidentialAcceptance` = CASE `reciprocityState` ";				
		for($i = 0; $i < $dataGridLength; $i++){
			$state = $dataGrid[$i][0];
			$acceptance = $dataGrid[$i][1];
			$sql .= "WHEN '$state' THEN '$acceptance' ";	
		}
		$sql .= "END WHERE `licensedState` = \"$licenseState\"";
		$sth = $db->query($sql);
	}
}

function reciprocityQueryAll($db){
	$filteredState = $_POST['filteredState'];
	$sql = "SELECT `licensedState`,`reciprocityState`,`residentialAcceptance` FROM `tbl_reciprocity` WHERE `licensedState` = '$filteredState'";
	$sth = $db->query($sql);
	$results = $sth->fetchAll(PDO::FETCH_ASSOC);
	return json_encode($results);
}

function reciprocityPostAll($db){
	if(isset($_SESSION['authenticated'])){
		$licenseState = $_POST['filteredState'];
		$dataGrid = json_decode($_POST['data']);
		$dataGridLength = count($dataGrid);
		$sql = "UPDATE `tbl_reciprocity` SET `residentialAcceptance` = CASE `reciprocityState` ";				
		for($i = 0; $i < $dataGridLength; $i++){
			$state = $dataGrid[$i][0];
			$acceptance = $dataGrid[$i][1];
			$sql .= "WHEN '$state' THEN '$acceptance' ";	
		}
		$sql .= "END WHERE `licensedState` = \"$licenseState\"";
		$sth = $db->query($sql);
	} 
}

function authenticator($db){
	require 'passwordHash.php';
	$username = $_POST['username'];
	$password = $_POST['password'];
	$sql = "SELECT `password` FROM `tbl_users` WHERE `username` = '$username'";
	$sth = $db->query($sql);
	$fullHash = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
	if(validate_password($password, $fullHash[0])){
		$_SESSION['username'] = $username;
		$_SESSION['authenticated'] = true;
		return json_encode(true);
	} else {
		return json_encode(false);	
	}
}

function applicationLog($data){
	$logFile = fopen("log.txt", "w") or die("Unable to open file!");
	fwrite($logFile, $data);
	fclose($logFile);
}
?>