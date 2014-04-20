'use strict';

/* Controllers */

var controllers = angular.module('adaxisoft.be.controllers', ['restangular']);

controllers.controller('MenuCtrl', ['$scope', 'menu', function($scope, menu) {
	$scope.menu = menu.getStructure;
}]);

controllers.controller('StandingsCtrl', ['$scope', '$routeParams', 'football', 'rss', 'menu',
function($scope, $routeParams, football, rss, menu) {

	menu.setStructure([[{
		url : '#/standings/portugal',
		classe : 'flag-pt icon',
		name : 'Portugal'
	},{
		url : '#/standings/spain',
		classe : 'flag-es icon',
		name : 'Spain'
	},{
		url : '#/standings/england',
		classe : 'flag-england icon',
		name : 'England'
	},{
		url : '#/standings/germany',
		classe : 'flag-de icon',
		name : 'Germany'
	},{
		url : '#/standings/italy',
		classe : 'flag-it icon',
		name : 'Italy'
	},{
		url : '#/standings/france',
		classe : 'flag-fr icon',
		name : 'France'
	},{
		url : '#/standings/belgium',
		classe : 'flag-be icon',
		name : 'Belgium'
	}]]);

	football.table($routeParams.country, 1).then(function(data) {
		$scope.league = data;
	});

	football.table($routeParams.country, 2).then(function(data) {
		$scope.league2 = data;
	});

	football.rss($routeParams.country, 1).then(function(data) {
		if (data.feed) {
			rss.parseFeed(data.feed).then(function(res) {
				$scope.feeds = res.data.responseData.feed.entries;
			});
		}
	});

	football.rss($routeParams.country, 2).then(function(data) {
		if (data.feed) {
			rss.parseFeed(data.feed).then(function(res) {
				$scope.feeds2 = res.data.responseData.feed.entries;
			});
		}
	});

}]);

controllers.controller('HomeCtrl', ['$scope', 'menu',
function($scope, menu) {
	menu.clear();
}]);

controllers.controller('ResetCtrl', ['$scope', '$routeParams', 'menu', 'Restangular',
function($scope, $routeParams, menu, Restangular) {
	menu.clear();
	
	Restangular.all("reset/website").getList().then(function(sites) {
		$scope.sites = sites;	
	});
	
	$scope.addWebsite = function(site) {
		$scope.sites.post(site).then(function() {
			$scope.sites[$scope.sites.length] = site;
		}, function() {
			// TODO Display a message to the user
			console.log("TODO display error message");
		});
		
		$scope.site = { importance : 10 };
	}
	
	$scope.deleteWebsite = function (id) {
		var site = _.find($scope.sites, function(site) {
			return site.id = id;	
		});
		site.remove().then(function(){
			$scope.sites = _.without($scope.sites, site);	
		}, function() {
			// TODO Display a message to the user
			console.log("TODO display error message");
		});
	}
	
	$scope.markToUpdate = function (site) {
		$scope.site = site;
	}
	
	$scope.updateWebsite = function (site) {		
		site.put();
		$scope.site = { importance : 10 };
	}
	
}]);


