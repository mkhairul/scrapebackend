/*jslint strict: true */

var app = angular.module('materialism', [
  'app.constants',

  'ngRoute',
  'ngAnimate',
  'ngSanitize',
  'ngPlaceholders',
  'ngTable',

  'angular-loading-bar',
  'satellizer',
  'angulartics',
  'angulartics.google.analytics',

  'uiGmapgoogle-maps',
  'ui.select',

  'gridshore.c3js.chart',
  'monospaced.elastic',     // resizable textarea
  'mgcrea.ngStrap',
  'jcs-autoValidate',
  'ngFileUpload',
  'textAngular',
  'fsm',                    // sticky header
  'smoothScroll',
  'LocalStorageModule'
]);

app.run(function($rootScope){
    $rootScope.url = 'http://localhost:8000/ikeabackend/public';
});