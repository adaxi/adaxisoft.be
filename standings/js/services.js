'use strict';

/* Services */

var services = angular.module('myApp.services', []);

services.factory('League', [ '$http', function($http) {
    var cache = {};

    return {
        'table' : function(country) {
            var promise = cache[country];
            if (!promise) {
                promise = $http.get('../api/sports/league/' + country, {});
                cache[country] = promise;
            }
            return promise.then(function(response) {
                return response.data;
            });
        }
    };

} ]);
