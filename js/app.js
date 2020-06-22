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
  $routeProvider.when("/user", {
    templateUrl: "templates/user.html",
    controller: "glavniController",
  });
  $routeProvider.when("/postavke", {
    templateUrl: "templates/postavke.html",
    controller: "glavniController",
  });
  $routeProvider.when("/pregled_zadatka", {
    templateUrl: "templates/pregled_zadatka.html",
    controller: "glavniController",
  });
  $routeProvider.when("/pregled_kreiranog_zadatka", {
    templateUrl: "templates/pregled_kreiranog_zadatka.html",
    controller: "glavniController",
  });
  $routeProvider.when("/drugi_user", {
    templateUrl: "templates/drugi_user.html",
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
  $rootScope,
  $timeout,
  $window
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
            $scope.logiran = response.data.user_username;

            localStorage.setItem(
              "korisnik",
              JSON.stringify(response.data.user_id)
            );
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
          $scope.loggedin = true;
          $rootScope.korisnik = response.data.user_id;
          $scope.logiran = response.data.user_username;

          localStorage.setItem(
            "korisnik",
            JSON.stringify(response.data.user_id)
          );
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

  // Modal za novi zadatak

  $scope.openModalNoviZadatak = function () {
    var modal_popup = angular.element("#modalNoviZadatak");
    modal_popup.modal("show");
    $scope.regDatumPocetka = new Date();
    $scope.regDatumZavrsetka = new Date();
  };
  $scope.closeModalNoviZadatak = function () {
    var modal_popup = angular.element("#modalNoviZadatak");
    modal_popup.modal("hide");
  };

  $scope.ModalNoviZadatak = function () {
    $scope.openModalNoviZadatak();
  };

  // Otvaranje korisnikovog profila -------------------------------------------------------------- >>>

  $scope.PrikaziUserProfile = function () {
    $scope.korisnik = JSON.parse(localStorage.getItem("korisnik"));
    $timeout(function () {
      $http({
        method: "GET",
        url:
          "json.php?korisnik_id=" +
          $scope.korisnik +
          "&json_id=dohvati_korisnika",
      }).then(
        function (response) {
          $scope.korisnik_info = response.data;
          //console.log($scope.korisnik_info[0].bio);
        },
        function (error) {
          console.log(error);
        }
      );
    }, 300);
  };

  $scope.DohvatiDrugogKorisnika = function (korisnik) {
    $http({
      method: "GET",
      url:
        "json.php?korisnik_username=" +
        korisnik +
        "&json_id=dohvati_id_drugog_korisnika",
    }).then(
      function (response) {
        $scope.drugi_korisnik_info = response.data;
        localStorage.setItem(
          "drugi_korisnik",
          JSON.stringify($scope.drugi_korisnik_info)
        );
        $location.path("/drugi_user");
      },
      function (error) {
        console.log(error);
      }
    );
  };
  $scope.PrikaziDrugiUserProfile = function () {
    $timeout(function () {
      $scope.drugi_korisnik = JSON.parse(
        localStorage.getItem("drugi_korisnik")
      );
    }, 400);
  };

  // Novi zadatak

  $scope.NoviZadatak = function () {
    $scope.alertMsg = true;
    if (
      $scope.regNaziv == undefined ||
      $scope.regDatumPocetka == undefined ||
      $scope.regDatumZavrsetka == undefined ||
      $scope.regIzvrsitelj == undefined ||
      $scope.regOpis == undefined
    ) {
      $scope.alertClass = "alert-danger";
      $scope.alertMessage = "Sva polja moraju biti popunjena!";
    } else {
      if ($scope.regDatumPocetka > $scope.regDatumZavrsetka) {
        $scope.alertClass = "alert-danger";
        $scope.alertMessage =
          "Datum početka ne smije biti veći od datuma završetka!";
      } else {
        // uzeti id od izvrsitelja umjesto korisnickog imena
        DajIdOdabranogKorisnika($scope.regIzvrsitelj);
        var oData = {
          action_id: "novi_zadatak",
          naziv: $scope.regNaziv,
          datum_pocetka: $scope.regDatumPocetka,
          datum_zavrsetka: $scope.regDatumZavrsetka,
          izvrsitelj: $scope.id_korisnika,
          kreator: $rootScope.korisnik,
          opis: $scope.regOpis,
        };
        $http.post("action.php", oData).then(function (response) {
          if (response.data == 1) {
            $scope.alertClass = "alert-success";
            $scope.alertMessage = "Uspješno dodan novi zadatak!";
            $timeout(uspjesnoDodavanje, 1200);
          } else {
            $scope.alertMessage = response.data;
          }
        });
      }
    }
  };
  function uspjesnoDodavanje() {
    $scope.closeModalNoviZadatak();
    $window.location.reload();
  }
  function DajIdOdabranogKorisnika(username) {
    var key_prev_iteracije;
    angular.forEach($scope.korisnici, function (value, key) {
      angular.forEach(value, function (valuetwo, keytwo) {
        if (keytwo == "korisnicko_ime") {
          if (valuetwo == username) {
            key_prev_iteracije = key;
          }
        }
      });
    });
    angular.forEach($scope.korisnici, function (value, key) {
      if (key == key_prev_iteracije) {
        angular.forEach(value, function (valuetwo, keytwo) {
          if (keytwo == "id") {
            $scope.id_korisnika = valuetwo;
          }
        });
      }
    });
  }

  // Brisanje zadatka

  $scope.openModalObrsiZadatak = function () {
    var modal_popup = angular.element("#modalObrsiZadatak");
    modal_popup.modal("show");
    $scope.naziv_zadatka = $scope.zadatak.naziv;
  };
  $scope.closeModalObrisiZadatak = function () {
    var modal_popup = angular.element("#modalObrsiZadatak");
    modal_popup.modal("hide");
  };
  $scope.ObrisiZadatakModal = function () {
    $scope.openModalObrsiZadatak();
  };

  $scope.ObrisiZadatak = function () {
    var oData = {
      action_id: "obrisi_zadatak",
      id: $scope.zadatak.id,
    };
    $http.post("action.php", oData).then(function (response) {
      if (response.data == 1) {
        alert("Uspješno ste obrisali zadatak!");
        $scope.closeModalObrisiZadatak();
        $timeout(function () {
          $location.path("/kreirani_zadaci");
        }, 500);
      } else {
        alert(response.data);
      }
    });
  };

  // Uredivanje zadatka

  $scope.openModalUrediZadatak = function () {
    var modal_popup = angular.element("#modalUrediZadatak");
    modal_popup.modal("show");

    $scope.edit_id = $scope.zadatak.id;
    $scope.edit_naziv = $scope.zadatak.naziv;
    $scope.edit_datum_pocetka = new Date($scope.zadatak.datum_pocetka);
    $scope.edit_datum_zavrsetka = new Date($scope.zadatak.datum_zavrsetka);
    $scope.edit_izvrsitelj = $scope.zadatak.izvrsitelj;
    $scope.edit_opis = $scope.zadatak.opis;
  };
  $scope.closeModalUrediZadatak = function () {
    var modal_popup = angular.element("#modalUrediZadatak");
    modal_popup.modal("hide");
  };
  $scope.UrediZadatakModal = function () {
    $scope.openModalUrediZadatak();
  };

  $scope.UrediZadatak = function () {
    $scope.alertMsg = true;
    if (
      $scope.edit_naziv == "" ||
      $scope.edit_izvrsitelj == "" ||
      $scope.edit_opis == ""
    ) {
      $scope.alertClass = "alert-danger";
      $scope.alertMessage = "Sva polja moraju biti popunjena!";
    } else {
      if ($scope.edit_datum_pocetka > $scope.edit_datum_zavrsetka) {
        $scope.alertClass = "alert-danger";
        $scope.alertMessage =
          "Datum početka ne smije biti veći od datuma završetka!";
      } else {
        DajIdOdabranogKorisnika($scope.edit_izvrsitelj);
        var oData = {
          action_id: "uredi_zadatak",
          id: $scope.edit_id,
          naziv: $scope.edit_naziv,
          datum_pocetka: $scope.edit_datum_pocetka,
          datum_zavrsetka: $scope.edit_datum_zavrsetka,
          izvrsitelj: $scope.id_korisnika,
          opis: $scope.edit_opis,
        };
        $http.post("action.php", oData).then(function (response) {
          if (response.data == 1) {
            alert("Uspješno uređen zadatak!");
            $scope.closeModalUrediZadatak();
            $timeout(function () {
              $window.location.reload();
            }, 500);
          } else {
            $scope.alertMessage = response.data;
          }
        });
      }
    }
  };

  // Oznacavanje zadatka kao dovrsenog

  $scope.openModalDovrsenZadatak = function () {
    var modal_popup = angular.element("#modalDovrsenZadatak");
    modal_popup.modal("show");
    $scope.naziv_zadatka = $scope.zadatak.naziv;
  };
  $scope.closeModalDovrsenZadatak = function () {
    var modal_popup = angular.element("#modalDovrsenZadatak");
    modal_popup.modal("hide");
  };
  $scope.DovrsenZadatakModal = function () {
    $scope.openModalDovrsenZadatak();
  };

  $scope.DovrsiZadatak = function () {
    var oData = {
      action_id: "dovrsi_zadatak",
      id: $scope.zadatak.id,
    };
    $http.post("action.php", oData).then(function (response) {
      if (response.data == 1) {
        alert("Uspješno ste dovršili zadatak!");
        $scope.closeModalDovrsenZadatak();
        $timeout(function () {
          $window.location.reload();
        }, 500);
      } else {
        alert(response.data);
      }
    });
  };

  // Dohvacanje mojih zadataka

  $scope.dohvatiMojeZadatke = function () {
    $timeout(function () {
      $http({
        method: "GET",
        url:
          "json.php?korisnik_id=" +
          $rootScope.korisnik +
          "&json_id=dohvati_moje_zadatke",
      }).then(
        function (response) {
          $scope.zadaci = response.data;
        },
        function (error) {
          console.log(error);
        }
      );
    }, 300);
  };

  // Dohvacanje kreiranih zadataka

  $scope.dohvatiKreiraneZadatke = function () {
    $timeout(function () {
      $http({
        method: "GET",
        url:
          "json.php?korisnik_id=" +
          $rootScope.korisnik +
          "&json_id=dohvati_kreirane_zadatke",
      }).then(
        function (response) {
          $scope.zadaci = response.data;
        },
        function (error) {
          console.log(error);
        }
      );
    }, 300);
  };

  // Otvaranje zasebnog zadatka

  $scope.OtvoriZasebniZadatak = function (id_zadatka) {
    localStorage.setItem("zadatak", JSON.stringify(id_zadatka));

    $location.path("/pregled_zadatka");
  };
  $scope.OtvoriZasebniKreiraniZadatak = function (id_zadatka) {
    localStorage.setItem("zadatak", JSON.stringify(id_zadatka));

    $location.path("/pregled_kreiranog_zadatka");
  };

  $scope.PrikaziZasebniKreiraniZadatak = function () {
    $scope.id_kreiranog_zadatka = JSON.parse(localStorage.getItem("zadatak"));
    $timeout(function () {
      $http({
        method: "GET",
        url:
          "json.php?korisnik_id=" +
          $rootScope.korisnik +
          "&json_id=dohvati_kreirani_zadatak&zadatak_id=" +
          $scope.id_kreiranog_zadatka,
      }).then(
        function (response) {
          $scope.zadatak_arr = response.data;
          $scope.zadatak = $scope.zadatak_arr[0];
          console.log($scope.zadatak);

          if ($scope.zadatak.stanje == 0) {
            $scope.zadatak_stanje = "Nedovršen"; // drugi scope staviti kako bi se na stranici mogao napraviti orderBy po stanju..
          } else {
            $scope.zadatak_stanje = "Dovršen";
          }
        },
        function (error) {
          console.log(error);
        }
      );
    }, 200);
  };
  $scope.PrikaziZasebniZadatak = function () {
    $scope.id_zadatka = JSON.parse(localStorage.getItem("zadatak"));
    $timeout(function () {
      $http({
        method: "GET",
        url:
          "json.php?korisnik_id=" +
          $rootScope.korisnik +
          "&json_id=dohvati_zadatak&zadatak_id=" +
          $scope.id_zadatka,
      }).then(
        function (response) {
          $scope.zadatak_arr = response.data;
          $scope.zadatak = $scope.zadatak_arr[0];
          console.log($scope.zadatak);

          if ($scope.zadatak.stanje == 0) {
            $scope.zadatak_stanje = "Nedovršen";
          } else {
            $scope.zadatak_stanje = "Dovršen";
          }
        },
        function (error) {
          console.log(error);
        }
      );
    }, 200);
  };

  // Dohvacanje korisnika

  $scope.DohvatiKorisnike = function () {
    var korisnicka_imena = [];
    $timeout(function () {
      $http({
        method: "GET",
        url: "json.php?json_id=dohvati_korisnike",
      }).then(
        function (response) {
          $scope.korisnici = response.data;
          angular.forEach($scope.korisnici, function (value, key) {
            angular.forEach(value, function (valuetwo, keytwo) {
              if (keytwo == "korisnicko_ime") {
                korisnicka_imena.push(valuetwo);
              }
            });
          });
          $scope.korisnicka_imena = korisnicka_imena;
        },
        function (error) {
          console.log(error);
        }
      );
    }, 300);
  };

  // Sortiranje

  $scope.orderProperty = "id";
  $scope.setOrderProperty = function (propertyName) {
    if ($scope.orderProperty === propertyName) {
      $scope.orderProperty = "-" + propertyName;
    } else if ($scope.orderProperty === "-" + propertyName) {
      $scope.orderProperty = propertyName;
    } else {
      $scope.orderProperty = propertyName;
    }
  };

  // Modali za postavke ------------------------------------------------------------------------------------>>

  // Avatar

  $scope.openModalAvatar = function () {
    var modal_popup = angular.element("#promjeni_avatar");
    modal_popup.modal("show");
    $scope.naziv_zadatka = $scope.zadatak.naziv;
  };
  $scope.closeModalAvatar = function () {
    var modal_popup = angular.element("#promjeni_avatar");
    modal_popup.modal("hide");
  };
  $scope.OdabirAvataraModal = function () {
    $scope.openModalAvatar();
  };
  /*
  $scope.SpremiAvatara = function () {
    var oData = {
      action_id: "dovrsi_zadatak",
      id: $scope.zadatak.id,
    };
    $http.post("action.php", oData).then(function (response) {
      if (response.data == 1) {
        alert("Uspješno ste dovršili zadatak!");
        $scope.closeModalDovrsenZadatak();
        $timeout(function () {
          $window.location.reload();
        }, 500);
      } else {
        alert(response.data);
      }
    });
  };
  */

  // Spol
  $scope.PromjeniSpol = function () {
    var modal_popup = angular.element("#promjeni_spol");
    $scope.new_spol = "";
    modal_popup.modal("show");
  };
  $scope.novi_spol = function (vrijednost) {
    console.log(vrijednost);
    $scope.new_spol = vrijednost;
  };
  $scope.SpremiSpol = function () {
    if ($scope.new_spol == "") {
      alert("Niste ništa označili..");
    } else {
      var oData = {
        action_id: "promjeni_spol",
        spol: $scope.new_spol,
        user_id: $scope.korisnik_info[0].id,
      };
      $http.post("action.php", oData).then(function (response) {
        if (response.data == 1) {
          alert("Uspješno promjenjen spol!");

          $window.location.reload();
        } else {
          alert(response.data);
        }
      });
    }
  };
});

oModul.filter("strLimit", [
  "$filter",
  function ($filter) {
    return function (input, limit) {
      if (!input) return;
      if (input.length <= limit) {
        return input;
      }

      return $filter("limitTo")(input, limit) + "...";
    };
  },
]);

oModul.filter("promjenaFormata", function () {
  return function (datum) {
    datum = datum.replace(/-/g, ".");

    return datum;
  };
});
