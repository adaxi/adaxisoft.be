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

directives.directive('importance', [ function() {
	return {
		restrict : 'A',
		replace : true,
		template : '<div class="ui label {{color}}">{{text}}</div>',
		link: function(scope, element, attrs) {
			
			if (attrs.importance <= 10) {
				scope.color = 'orange';
				scope.text = 'HIGH';
			} else if (attrs.importance > 10 && attrs.importance <= 20) {
				scope.color = 'black';
				scope.text = 'MEDIUM';
			} else if (attrs.importance > 20) {
				scope.color = 'green';
				scope.text = 'LOW';
			}			
		}
	}
}]);
