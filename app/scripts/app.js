'use strict';

// Declare app level module which depends on filters, and services
var app = angular.module('adaxisoft.be',[
                                        'ngRoute',
                                        'adaxisoft.be.filters',
                                        'adaxisoft.be.services',
                                        'adaxisoft.be.directives',
                                        'adaxisoft.be.controllers'
                                        ]);


app.config([ '$routeProvider', function($routeProvider) {
    $routeProvider.when('/standings/:country', {
        templateUrl : 'partials/standings.html',
        controller : 'StandingsCtrl'
    });
    $routeProvider.when('/reset', {
        templateUrl : 'partials/reset.html',
        controller : 'ResetCtrl'
    });
    $routeProvider.otherwise({
        redirectTo : '/standings/portugal'
    });
} ]);


app.config(function(RestangularProvider){
    RestangularProvider.setBaseUrl('/api/');
    RestangularProvider.setResponseExtractor(function(response, operation) {
       
        //if (operation === "getList") {
        //    response.data = response.data || [];
        //} else {
        //    response.data = response.data || {};
        //}
        
        return response.data;
    });
});
