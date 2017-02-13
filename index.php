<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Reciprocity Map</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="container">
  <div id="admin">
    <div id="adminHeader">
      <h1>Admin Center</h1>
      <h2 id="adminInformation">Enter Your Credentials to change the rules of the map.</h2>
      <h2 id="adminSubInformation"> </h2>
      <div id="credentialArea">
        <form id="credentialForm" action="">
          <input id="username" type="text" placeholder="User-Name"><input id="password" type="password" placeholder="Password"><br><button type="submit" id="signInButton">Sign In</button><br><button id="closeAdmin">Close Admin</button>
        </form>
      </div>
      <div id="ruleNavigation">
      <button id="adminNonResidMenuButton" class="standardButton">NonResidential Issuers</button><button id="adminNonResidMenuButton2" class="standardButton">NonResidential Reciprocity</button><button id="adminRecipMenuButton" class="standardButton">Residential Reciprocity</button><br><button id="closeAdmin2" class="wideButton">Close Admin</button>
      <div id="stateFilterContainer">
        <select id="stateFilter">
        </select>
      </div>
      </div>
    </div>
    <div id="adminDataWrapper">
    <div id="adminData">
      <h1 id="adminContextHeader"></h1>
      <h2 id="adminContextSubHeader"></h2>
      <table id="adminTableLeft">
      </table>
      <table id="adminTableRight">
      </table>
	  <button id='commitButton'>Commit Data</button>
    </div>
    </div>
  </div>
  <div class="mapWrapper">
    <div id="map"></div>
    <div id="DC">
      <h1>DC</h1>
    </div>
    <div id="text"></div>
    <div id="mapMessengerWrapper">
      <div id="mapMessengerFlex">
        <h1>You Chose:</h1>
        <h2 id="stateMessage"></h2>
        <p>What type of license do you have here?</p>
        <button id="resident" class="option">Residential</button><button id="nonResident" class="option">Non-Residential</button>
        <button id="closeButton">Cancel</button>
      </div>
    </div>
  </div>
  <div id="information">
    <h1>CWP Reciprocity Map</h1>
    <?php
	if(isset($_GET['admin'])){
    print '<button id="adminButton">Admin</button>';
	}
	?>
    <button id="resetButton">Reset Map</button>
    <div class="CB" id="colorBlock1"></div>
    <h2>Chosen residential license</h2>
    <p id="residential"></p>
    <div class="CB" id="colorBlock2"></div>
    <h2>Chosen non-residential license(s)</h2>
    <p id="nonResidential"></p>
    <div class="CB" id="colorBlock3"></div>
    <h2>States that honor your permits:</h2>
    <p id="permittedStates"></p>
    <div class="CB" id="colorBlock4"></div>
    <h2>States that do not honor your permits:</h2>
    <p id="notPermittedStates"></p>
  </div>
</div>
</body>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery-ui.min.js" type="text/javascript"></script>
<script src="js/raphael.min.js" type="text/javascript"></script>
<script src="js/scale.raphael.js" type="text/javascript"></script>
<script src="js/paths.js" type="text/javascript"></script>
<script src="js/init.js" type="text/javascript"></script>
<script src="js/logic.js" type="text/javascript"></script>
</html>