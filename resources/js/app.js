import './bootstrap';
var app = angular.module('shipsApp', []);
app.controller('shipsCtrl', function($scope, $http) {
    $http.get('/ships').then(function(response) {
        $scope.ships = response.data;
    });
});
