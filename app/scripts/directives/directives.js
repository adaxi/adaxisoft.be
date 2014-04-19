'use strict';

/* Directives */

var directives = angular.module('adaxisoft.be.directives', []);

directives.directive('appVersion', [function() {
	return function(scope, elm, attrs) {
		elm.text('Rock');
	};
}]);

directives.directive( 'menu', [ function() {
	return {
		restrict : 'E',
		replace : true,
		template : '<div ng-repeat="m in menu()" class="ui black inverted menu" style="margin-bottom: 10px">'
				+ '<a ng-repeat="item in m" class="item" href="{{item.url}}"><i class="{{item.classe}}"></i>{{item.name}}</a>'
				+ '</div>',

	};
}]);
