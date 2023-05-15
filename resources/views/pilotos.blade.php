<!DOCTYPE html>
<html ng-app="myApp">

<head>
    <title>StarWars</title>
    <style>
        @import "css/bootstrap.min.css";
        @import "css/app.css";
        @import "css/icofont/icofont.min.css";
    </style>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css" integrity="sha384-QYIZto+st3yW+o8+5OHfT6S482Zsvz2WfOzpFSXMF9zqeLcFV0/wlZpMtyFcZALm" crossorigin="anonymous"> -->
</head>

<body>
    <a class="volver btn btn-warning btn-lg" href="/">Volver</a>
    <div id="pilotos">
        <div ng-controller="pilotCtrl">

            <h1>Pilotos</h1>
            <img id="loader" src="{{ asset('css/loader.gif') }}" ng-show="loading">


            <div id="todosPilotos">
                <div id="piloto" ng-repeat="pilot in pilots">
                    <h3>@{{pilot.name}}</h3>
                    <div>
                        <p>Peso: @{{pilot.height}}</p>
                        <p>Color de pelo: @{{pilot.hair_color}}</p>
                    </div>
                    <div><button class="btn btn-danger" ng-click="eliminarPiloto(pilot.id)">Eliminar</button></div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <script>
        var app = angular.module('myApp', []);
        //Controlador de los pilotos
        app.controller('pilotCtrl', function($scope, $http, $rootScope) {
            //Hace el get a esa dirección
            $http.get("api/pilot")
                .then(function(response) {
                    $scope.pilots = response.data;
                });
            //Función que elimina un piloto tanto de la tabla piloto como sus relaciones
            $scope.eliminarPiloto = function(pilotoId) {
                $scope.loading = true;
                //Coge el post y lo lleva a esa dirección
                $http.post("api/deletePilot/" + pilotoId)
                    .then(function(response) {
                        //Cuando entre aquí lista otra vez los pilotos y las naves
                        $http.get("api/pilot")
                            .then(function(response) {
                                $scope.pilots = response.data;
                            });
                        $http.get("api/pilotosDeNaves")
                            .then(function(response) {
                                $rootScope.starships = response.data;
                                $scope.loading = false;
                            });
                    });
            };
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>