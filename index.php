<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Avoca To-Do!</title>
    <script src="assets/plugins/jquery/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="assets/plugins/bootstrap-4.4.1-dist/css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="assets/plugins/AngularJS/angular.min.js"></script>
    <script src="assets/plugins/AngularJS/angular-route.min.js"></script>
    <script src="js/app.js"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
</head>
<body ng-app="app" ng-controller="glavniController">

    <div ng-view>
    </div>

<script src="assets/plugins/bootstrap-4.4.1-dist/js/bootstrap.min.js"></script>

</body>
</html>