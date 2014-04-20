'use strict';

/* Filters */

var filters = angular.module('adaxisoft.be.filters', []);

filters.filter('interpolate', ['version', function(version) {
    return function(text) {
      return String(text).replace(/\%VERSION\%/mg, version);
    }
}]);

filters.filter('importance', [ function() {
    return function(importance) {
      var color = 'red';
      var text = 'HIGH';
      if (importance > 10 && importance <= 20) {
        color = 'yellow';
        text = 'MEDIUM';
      } else if (importance > 20) {
        color = 'red';
        text = 'LOW';
      }      
      return '<div class="ui label ' + color + '">' + text + '</div>';
    }
}]);
