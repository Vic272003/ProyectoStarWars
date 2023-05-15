<!DOCTYPE html>
<html ng-app="myApi">

<head>
    <title>StarWars</title>
    <style>
        /* Hacemos los import a los ficheros */
        @import "css/bootstrap.min.css";
        @import "css/app.css";
        @import "css/icofont/icofont.min.css";
    </style>
    <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>
    <a class="volver btn btn-warning btn-lg" href="/">Inicio<i class="fa fa-home ms-3"></i></a>
    <a class="siguiente btn btn-warning btn-lg" href="/pilots">Pilotos<i class="fa fa-arrow-right ms-3"></i></a>
    <div id="naves">
        <div ng-controller="starshipCtrl">

            <h1>Naves con sus respectivos pilotos y precios</h1>
            <div class="alert alert-success mt-0" role="alert" ng-if="aniadePiloto">
                Piloto añadido correctamente mi pequeño Padawan!
                <button type="button" class="btn-close ms-5" data-bs-dismiss="alert" aria-label="Close" ng-click="aniadePiloto= false"></button>
            </div>

            
            <div class="alert alert-success mt-0" role="alert" ng-if="pilotoBorrar">
            
                Piloto borrado de la nave correctamente mi pequeño Padawan!
                <button type="button" class="btn-close ms-5" data-bs-dismiss="alert" aria-label="Close" ng-click="pilotoBorrar= false"></button>
            </div>

            <div class="alert alert-danger mt-0" role="alert" ng-if="aniadePilotoFalse">
                Se ha intentado meter un piloto que ya está enlazado con la nave, por favor pruebe con otro!
                <button type="button" class="btn-close ms-5" data-bs-dismiss="alert" aria-label="Close" ng-click="aniadePilotoFalse = false"></button>
            </div>
            <img id="loader" src="{{ asset('css/imperialLoader.gif') }}" ng-show="loading">


            <div id="todasNaves" class="scroll">
                <div id="nave" ng-repeat="starship in starships">
                    <h3>@{{starship.name}}</h3>
                    <div>
                        <p><span>Modelo</span>: @{{starship.model}}</p>
                        <p ng-if="starship.cost_in_credits != 'unknown'">
                            <span>Precio</span>: @{{ priceToBase15(starship.cost_in_credits) }}
                        </p>
                        <p ng-if="starship.cost_in_credits == 'unknown'">
                            <span>Precio</span>: @{{priceToBase15(200)}}
                        </p>
                        <div id="pilotosNave">
                            <p><span>Pilotos<span>:</p>
                            <ul ng-if="starship.pilots==''">
                                <li>No hay pilotos en esta nave</li>
                            </ul>
                            <ul ng-repeat="pilot in starship.pilots">
                                <li id="modPiloto">@{{pilot.name}}<button type="button" ng-click="eliminar(starship.name,pilot.name)" class="btn-close bton-eliminar"></button></li>

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
        //definimos un módulo de AngularJS
        var app = angular.module('myApi', []);

        //Controlador de las naves que le pasamos el scope, el http y el rootScope para que el controlador de los pilotos vea la variable starships
        app.controller('starshipCtrl', function($scope, $http, $rootScope) {
            //Hace el get a esa dirección 
            $http.get("api/pilotosDeNaves")
                .then(function(response) {
                    $rootScope.starships = response.data;
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
                
                $scope.aniadePiloto = false;
                $scope.aniadePilotoFalse = false;
                $http.post("api/addPilotToStarship/" + starshipId, {
                        pilot_id: pilotId
                    })
                    .then(function(response) {
                        //$scope.aniadePiloto = true;
                        // Si la llamada HTTP fue exitosa, actualiza la lista de naves
                        $http.get("api/pilotosDeNaves")
                            .then(function(response) {
                                $rootScope.starships = response.data;

                                $scope.aniadePiloto = true;
                                $scope.loading = false;
                            });
                    })
                    .catch(function(data, status) {
                        $scope.aniadePiloto = false;
                        $scope.aniadePilotoFalse = true;
                        $scope.loading = false;
                    })
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
                $scope.pilotoBorrar = false;
                // Hacer la llamada DELETE a la API
                $http({
                    method: 'DELETE',
                    url: '/api/starships/' + starshipName + '/pilots/' + pilotName
                }).then(function(response) {
                    $scope.pilotoBorrar = true;
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</body>

</html>