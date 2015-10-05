app.controller('QuoteController',
 ['$scope', '$alert', '$rootScope', '$http', 'quoteService',
  function($scope, $alert, $rootScope, $http, quoteService){
  $scope.quoteService = quoteService;
  $scope.quoteItems = quoteService.getItems();
}]);