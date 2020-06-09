var oModul = angular.module("app", ["ngRoute"]);

// Rute

oModul.config(function ($routeProvider) {
  $routeProvider.when("/", {
    templateUrl: "templates/login.html",
    controller: "glavniController",
  });
  $routeProvider.when("/moji_zadaci", {
    templateUrl: "templates/moji_zadaci.html",
    controller: "glavniController",
  });
  $routeProvider.when("/kreirani_zadaci", {
    templateUrl: "templates/kreirani_zadaci.html",
    controller: "glavniController",
  });
  $routeProvider.when("/kalendar", {
    templateUrl: "templates/kalendar.html",
    controller: "glavniController",
  });
  $routeProvider.otherwise({
    template: "Došlo je do pogreške",
  });
});

// Kontroler

oModul.controller("glavniController", function (
  $scope,
  $http,
  $location,
  $timeout,
  $rootScope
) {
  $scope.CheckLoggedIn = function () {
    $http
      .post("action.php", {
        action_id: "check_logged_in",
      })
      .then(
        function (response) {
          if (response.data.status == 1) {
            $scope.loggedin = true;
            $rootScope.korisnik = response.data.user_id;
            $scope.logiran = response.data.user_id;
          } else {
            $scope.loggedin = false;
            $location.path("/");
          }
        },
        function (error) {
          console.log(error);
        }
      );
  };

  // Prijava

  $scope.Prijava = function () {
    var oData = {
      action_id: "login",
      email: $scope.email,
      password: $scope.pass,
    };
    $http.post("action.php", oData).then(
      function (response) {
        if (response.data.status == 1) {
          $scope.closeModal();
          $scope.loggedin = true;
          $rootScope.korisnik = response.data.user_id;
          $scope.logiran = response.data.user_id;
          $location.path("/moji_zadaci");
        } else {
          alert("Neispravno korisničko ime i/ili lozinka! Pokušajte ponovno!");
        }
      },
      function (error) {
        console.log(error);
      }
    );
  };

  // Registracija

  $scope.Registracija = function () {
    $scope.alertMsg = true;
    if (
      $scope.regIme == undefined ||
      $scope.regPrezime == undefined ||
      $scope.regEmail == undefined ||
      $scope.regLozinka == undefined ||
      $scope.regKorisnickoIme == undefined
    ) {
      $scope.alertClass = "alert-danger";
      $scope.alertMessage = "Sva polja moraju biti popunjena!";
    } else {
      var lozinka = $scope.regLozinka;
      if (lozinka.length < 6) {
        $scope.alertClass = "alert-danger";
        $scope.alertMessage =
          "Lozinka se mora sastojati od minimalno 6 znakova!";
      } else {
        var oData = {
          action_id: "registracija",
          ime: $scope.regIme,
          prezime: $scope.regPrezime,
          email: $scope.regEmail,
          lozinka: $scope.regLozinka,
          korisnickoIme: $scope.regKorisnickoIme,
        };
        $http.post("action.php", oData).then(function (response) {
          if (response.data == 1) {
            $scope.alertClass = "alert-success";
            $scope.alertMessage = "Uspješna registracija!";
            $timeout(uspjesnaRegistracija, 1200);
          } else {
            $scope.alertMessage = response.data;
          }
        });
      }
    }
  };

  function uspjesnaRegistracija() {
    $scope.closeModal();
    $scope.regIme = null;
    $scope.regPrezime = null;
    $scope.regEmail = null;
    $scope.regLozinka = null;
    $scope.regKorisnickoIme = null;
    $scope.alertMsg = false;
  }

  // Odjava

  $scope.Odjava = function () {
    var oData = {
      action_id: "logout",
    };
    $http.post("action.php", oData).then(
      function (response) {
        $scope.ulogiran = false;
        alert("Uspješno ste se odjavili");
        $location.path("/");
      },
      function (e) {
        console.log("error");
      }
    );
  };

  // Rad sa modalom za registraciju

  $scope.openModal = function () {
    var modal_popup = angular.element("#modalSignUp");
    modal_popup.modal("show");
  };

  $scope.closeModal = function () {
    var modal_popup = angular.element("#modalSignUp");
    modal_popup.modal("hide");
  };

  $scope.modalRegistracija = function () {
    $scope.openModal();
  };

  $scope.closeMsg = function () {
    $scope.alertMsg = false;
  };

  // Dohvacanje mojih zadataka

  $scope.dohvatiMojeZadatke = function () {
    $http({
      method: "GET",
      url:
        "json.php?json_id=dohvati_zadatke&korisnik_id=" + $rootScope.korisnik,
    }).then(
      function (response) {
        console.log(response.data);
        $scope.zadaci = response.data;
      },
      function (error) {
        console.log(error);
      }
    );
  };
});
