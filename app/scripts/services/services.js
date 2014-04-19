'use strict';

/* Services */

var services = angular.module('adaxisoft.be.services', []);

services.factory('football', [ '$http', function($http) {
	var cache = {};

	return {
		'table' : function(country, division) {
			country = country || "portugal";
			division = division || 1;
			var id = 'table..' + country + '..' + division;
			var promise = cache[id];
			if (!promise) {
				promise = $http.get('../api/sports/league/' + country + '/' + division, {});
				cache[id] = promise;
			}
			return promise.then(function(response) {
				return response.data;
			});
		},
		'rss' : function(country, division) {
			country = country || "portugal";
			division = division || 1;
			var id = 'rss..' + country + ".." + division;
			var promise = cache[id];
			if (!promise) {
				promise = $http.get('../api/sports/news/' + country + '/' + division, {});
				cache[id] = promise;
			}
			return promise.then(function(response) {
				return response.data;
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
			}
	};
	return menu;
}]);
