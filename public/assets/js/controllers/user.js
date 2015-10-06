app.controller('UserController',
 ['$scope', '$alert', '$rootScope', '$http',
  function($scope, $alert, $rootScope, $http){
 
 $scope.user = {};
 $scope.users = [];
  
 $http.get($rootScope.url + '/users')
 .success(function(data){
     $scope.users = data;
 })
 
 $scope.newUser = function(){
     $scope.user = {};
     $scope.user.action = 'new';
 }
 
 $scope.hideNewUser = function(){
     delete $scope.user.action;
 }
 
 $scope.selectUser = function(item){
     $scope.user = item;
     $scope.user.action = 'update';
 }
 
 $scope.saveUser = function(){
     var action = '';
     var user_data = $scope.user;
     if(user_data.action == 'new')
     {
         action = 'create';
     }
     else
     {
         action = 'update';
     }
     $http.post($rootScope.url + 'user/' + action, $scope.user)
     .success(function(data){
         if(user_data.action == 'new')
         {
            console.log('push?');
            $scope.users.push($scope.user);
         }
         delete $scope.user.action;
         delete $scope.error;
     })
     .error(function(data){
         $scope.error = []
         $scope.error.message = data.message;
     });
 }
      
}]);