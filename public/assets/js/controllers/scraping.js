app.controller('ScrapingController',
 ['$scope', '$alert', '$rootScope', '$http', '$timeout',
  function($scope, $alert, $rootScope, $http, $timeout){
  
	var poll = function(){
		$timeout(function(){
			$http.get($rootScope.url + 'scrape')
			.success(function(data){
					console.log(data);
					$scope.scrape_data = data;
					for(var i in data){
						if(data[i].status === "0"){
							poll();
							break;
						}
					}
			})
		}, 5000);
	}
	
	$http.get($rootScope.url + 'scrape')
	.success(function(data){
			console.log(data);
			$scope.scrape_data = data;
			for(var i in data){
				if(data[i].status === "0"){
					poll();
					break;
				}
			}
	})
	
	$scope.scrape = function(){
		$http.get($rootScope.url + 'scrape/start')
		.success(function(data){
				poll();
		})
	}
}]);