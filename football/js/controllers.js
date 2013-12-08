'use strict';

/* Controllers */

var controllers = angular.module('myApp.controllers', []);

controllers.controller('StandingsCtrl', [ '$scope', '$routeParams', 'League',
    'RSS', function($scope, $routeParams, League, RSS) {
        League.table($routeParams.country, 1).then(function(data) {
            if (data.result && data.result == 'fail') {
                return;
            }
            $scope.league = data;
        });
        League.table($routeParams.country, 2).then(function(data) {
            if (data.result && data.result == 'fail') {
                return;
            }
            $scope.league2 = data;
        });

        League.rss($routeParams.country, 1).then(function(data) {
            if (data.feed) {
                RSS.parseFeed(data.feed).then(function(res) {
                    $scope.feeds = res.data.responseData.feed.entries;
                });
            }
        });

        League.rss($routeParams.country, 2).then(function(data) {
            if (data.feed) {
                RSS.parseFeed(data.feed).then(function(res) {
                    $scope.feeds2 = res.data.responseData.feed.entries;
                });
            }
        });

    } ]);
