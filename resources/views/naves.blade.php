<!DOCTYPE html>
<html ng-app="myApi">

<head>
    <title>StarWars</title>
    <style>
        @import "css/bootstrap.min.css";
        @import "css/app.css";
        @import "css/icofont/icofont.min.css";
    </style>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css" integrity="sha384-QYIZto+st3yW+o8+5OHfT6S482Zsvz2WfOzpFSXMF9zqeLcFV0/wlZpMtyFcZALm" crossorigin="anonymous"> -->
</head>

<body>
    <a class="volver btn btn-warning btn-lg" href="/">Volver</a>
    <div id="naves">
        <div ng-controller="starshipCtrl">

            <h1>Naves con sus respectivos pilotos y precios</h1>
            <img id="loader" src="{{ asset('css/loader.gif') }}" ng-show="loading">
            

            <div id="todasNaves">
                <div id="nave" ng-repeat="starship in starships">
                    <h3>@{{starship.name}}</h3>
                    <div>
                        <p>Modelo: @{{starship.model}}</p>
                        <p ng-if="starship.cost_in_credits != 'unknown'">
                            Precio: @{{ priceToBase15(starship.cost_in_credits) }}
                        </p>
                        <p ng-if="starship.cost_in_credits == 'unknown'">
                            Precio: @{{priceToBase15(200)}}
                        </p>
                        <div id="pilotosNave">
                            <p>Pilotos:</p>
                            <ul ng-repeat="pilot in starship.pilots">
                                <li id="modPiloto">@{{pilot.name}}<button type="button" ng-click="eliminar(starship.name,pilot.name)" class="btn-close"></button></li>

                            </ul>
                        </div>
                    </div>
                    <p class="aniadirPiloto" id="aniadirPiloto[seleccion]">
                        <select class=" form-select-sm " ng-model="selectedPilot[seleccion]">
                            <option ng-repeat="pilot in pilots" value="@{{pilot.id}}">@{{pilot.name}}</option>

                        </select>
                        <button class="btn btn-warning" ng-click="addPilotToStarship(starship.id,selectedPilot[seleccion])">Añadir</button>
                    </p>
                </div>
            </div>
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <script>
        var app = angular.module('myApi', []);

        //Controlador de las naves que le pasamos el scope, el http y el rootScope para que el controlador de los pilotos vea la variable starships
        app.controller('starshipCtrl', function($scope, $http, $rootScope) {
            //Hace el get a esa dirección 
            $http.get("api/pilotosDeNaves")
                .then(function(response) {
                    $rootScope.starships = response.data;
                    console.log($rootScope.starships);
                });
            //Hace el get a esa dirección para mostrar los pilotos en el desplegable
            $http.get("api/pilot")
                .then(function(response) {
                    $scope.pilots = response.data;
                });
            $scope.seleccion = 0; // Declarar la variable seleccion

            //Función que añade un piloto a una nave
            $scope.addPilotToStarship = function(starshipId, pilotId) {
                $scope.loading = true;
                $http.post("api/addPilotToStarship/" + starshipId, {
                        pilot_id: pilotId
                    })
                    .then(function(response) {

                        // Si la llamada HTTP fue exitosa, actualiza la lista de naves
                        $http.get("api/pilotosDeNaves")
                            .then(function(response) {
                                $rootScope.starships = response.data;
                                $scope.loading = false;
                            });
                    });
            };

            //Función que convierte el precio de la nave a base 15
            $scope.priceToBase15 = function(price) {

                var base15 = "";
                var symbols = "0123456789ßÞ¢μ¶";
                while (price > 0) {
                    var remainder = price % 15;
                    base15 = symbols.charAt(remainder) + base15;
                    price = Math.floor(price / 15);
                }
                return base15;
            };
            $scope.eliminar = function(starshipName, pilotName) {
                $scope.loading = true;
                // Hacer la llamada DELETE a la API
                $http({
                    method: 'DELETE',
                    url: '/api/starships/' + starshipName + '/pilots/' + pilotName
                }).then(function(response) {
                    // Si la eliminación fue exitosa, eliminar el piloto de la lista en la vista
                    // por ejemplo:
                    var starship = $scope.starships.find(function(s) {
                        return s.name === starshipName;
                    });
                    if (starship) {
                        var index = starship.pilots.findIndex(function(p) {
                            return p.name === pilotName;
                        });
                        if (index !== -1) {
                            starship.pilots.splice(index, 1);
                        }
                    }
                    $scope.loading = false;
                }, function(error) {
                    // Si hay un error, manejarlo aquí
                });
            };
        });
    </script>
    <!-- <script src="../../public/css/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    
</body>

</html>