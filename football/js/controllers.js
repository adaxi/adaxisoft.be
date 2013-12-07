'use strict';

/* Controllers */

var controllers = angular.module('myApp.controllers', []);

controllers.controller('StandingsCtrl', [ '$scope', '$routeParams', 'League',
    function($scope, $routeParams, League) {
        League.table($routeParams.country).then(function(data) {
            $scope.league = data;
        });

    } ]);
