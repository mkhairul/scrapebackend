app.controller('LogisticController',
 ['$scope', '$alert', '$rootScope', '$http',
  function($scope, $alert, $rootScope, $http){
  
  
  $scope.couriers = [];    
  $scope.courier = {};
  $scope.newCourier = function(){
      $scope.courier = {};
      $scope.courier.action = 'new';
  }
  $scope.hideNewCourier = function(){ $scope.courier.action = '' };
  $scope.newCondition = function(){
      if($scope.courier.conditions == undefined)
      {
          $scope.courier.conditions = [];
      }
      $scope.courier.conditions.push({});
      console.log($scope.courier);
  }
  $scope.clearCondition = function(index){
      $scope.courier.conditions.splice(index, 1);
  }
  
  $scope.countries = [
      { name: 'Malaysia', code: 'MYS' },
      { name: 'Brunei', code: 'BRN' },
      { name: 'Vietnam', code: 'VNM' }
  ]
  
  $scope.compare = ['>', '>=', '<', '<='];
      
  $http.get($rootScope.url + 'logistic')
  .success(function(data){
      for(var i in data)
      {
          data[i].conditions = JSON.parse(data[i].conditions);
      }
      $scope.couriers = data;
  })
  
  $scope.selectCourier = function(obj)
  {
      $scope.courier = obj;
      $scope.courier.action = 'update';
  }
      
  $scope.saveCourier = function(){
      var data = $scope.courier;
      var action = '';
      var post = {};
      if(data.action == 'new')
      {
          action = 'create';
          post = { 'name':data.name, 'price_per_unit':data.price_per_unit, 
                   'conditions':JSON.stringify(data.conditions) };
      }
      else if(data.action == 'update')
      {
          action = 'update';
          post = { 'id': data.id, 'name':data.name, 
                   'price_per_unit':data.price_per_unit, 
                   'conditions':JSON.stringify(data.conditions) }
      }
      $http.post($rootScope.url + 'logistic/' + action, post)
      .success(function(data){
          if(data.action == 'new')
          {
            $scope.couriers.push($scope.courier);
          }
          $scope.courier = {};
      })
      .error(function(data){
      });
  }
}]);