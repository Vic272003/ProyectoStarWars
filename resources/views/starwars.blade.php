<!DOCTYPE html>
<html ng-app="myApp">

<head>
    <title>Mi sitio web</title>
    <style>
        table {
            border-collapse: collapse;
            /* margin: auto; */
            width: 50%;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        body {
            display: flex;
        }
    </style>
</head>

<body>
    <div id="naves">
        <div ng-controller="starshipCtrl">
            <h1>Naves con sus respectivos pilotos y precios</h1>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Modelo</th>
                        <th>Precio</th>
                        <th>Pilotos</th>
                        <th>Añadir Piloto</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="starship in starships">
                        <td>@{{starship.name}} </td>
                        <td>@{{ starship.model }}</td>

                        <td ng-if="starship.cost_in_credits != 'unknown'">
                            @{{ priceToBase15(starship.cost_in_credits) }}
                        </td>
                        <td ng-if="starship.cost_in_credits == 'unknown'">
                            @{{priceToBase15(200)}}
                        </td>

                        <td>
                            <span ng-repeat="pilot in starship.pilots">
                                @{{pilot.name}},
                            </span>

                        </td>
                        <td class="aniadirPiloto" id="aniadirPiloto[seleccion]">
                            <select ng-model="selectedPilot[seleccion]">
                                <option ng-repeat="pilot in pilots" value="@{{pilot.id}}">@{{pilot.name}}</option>
                            </select>
                            <button class="btn-success" ng-click="addPilotToStarship(starship.id,selectedPilot[seleccion])">Añadir</button>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>

    </div>
    <div ng-controller="pilotCtrl" id="mostrarListadoPilotos">
        <h1>Pilotos que hay en la base de datos</h1>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="pilot in pilots">
                    <td>@{{pilot.name}} </td>
                    <td><button class="btn-success" ng-click="eliminarPiloto(pilot.id)">Eliminar</button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <script>
        var app = angular.module('myApp', []);

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
                $http.post("api/addPilotToStarship/" + starshipId, {
                        pilot_id: pilotId
                    })
                    .then(function(response) {

                        // Si la llamada HTTP fue exitosa, actualiza la lista de naves
                        $http.get("api/pilotosDeNaves")
                            .then(function(response) {
                                $rootScope.starships = response.data;
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
        });
        //Controlador de los pilotos
        app.controller('pilotCtrl', function($scope, $http, $rootScope) {
            //Hace el get a esa dirección
            $http.get("api/pilot")
                .then(function(response) {
                    $scope.pilots = response.data;
                });
            //Función que elimina un piloto tanto de la tabla piloto como sus relaciones
            $scope.eliminarPiloto = function(pilotoId) {
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
                            });
                    });
            };
        });
    </script>
</body>

</html>