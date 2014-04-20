'use strict';

/* Services */

var services = angular.module('adaxisoft.be.services', ['restangular']);

services.factory('football', [ 'Restangular', function(Restangular) {
	var cache = {};

	return {
		'table' : function(country, division) {
			country = country || "portugal";
			division = division || 1;
			var id = 'table..' + country + '..' + division;
			var promise = cache[id];
			if (!promise) {
				promise = Restangular.oneUrl('football', '/api/sports/league/' + country + '/' + division).get();				
				cache[id] = promise;
			}
			return promise.then(function(response) {
				return response;
			});
		},
		'rss' : function(country, division) {
			country = country || "portugal";
			division = division || 1;
			var id = 'rss..' + country + ".." + division;
			var promise = cache[id];
			if (!promise) {
				promise = Restangular.oneUrl('football', '/api/sports/news/' + country + '/' + division).get();
				cache[id] = promise;
			}
			return promise.then(function(response) {
				return response;
			});
		}
	};

}]);

services.factory( 'rss', [ '$http',
 function($http) {
	 return {
		 parseFeed : function(url) {
			 return $http
			 .jsonp('//ajax.googleapis.com/ajax/services/feed/load'
					 + '?v=1.0&num=50&callback=JSON_CALLBACK&q='
					 + encodeURIComponent(url));
		 }
	 };
 }]);

services.factory('menu', [function() {
	var menu = {
			structure : {},
			getStructure : function() {
				return menu.structure;
			},
			setStructure : function(structure) {
				menu.structure = structure;
			},
			clear: function() {
				menu.structure = [[]];
			}
	};
	return menu;
}]);
