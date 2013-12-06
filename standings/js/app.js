'use strict';

// Declare app level module which depends on filters, and services
angular.module('myApp',
        [ 'ngRoute', 'myApp.filters', 'myApp.services', 'myApp.directives',
            'myApp.controllers' ]).config([ '$routeProvider',
    function($routeProvider) {
        $routeProvider.when('/standings/:country', {
            templateUrl : 'partials/standings.html',
            controller : 'StandingsCtrl'
        });
        $routeProvider.otherwise({
            redirectTo : '/standings/portugal'
        });
    } ]);
