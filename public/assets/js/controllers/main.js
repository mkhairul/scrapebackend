app.controller('MainController',
  ['$scope', '$animate', 'localStorageService', 'todoService', '$alert', '$timeout', '$rootScope', 'PlaceholderTextService', 'ngTableParams', '$filter', '$http',
  function($scope, $animate, localStorageService, todoService, $alert, $timeout, $rootScope, PlaceholderTextService, ngTableParams, $filter, $http){

  $scope.theme_colors = [
    'pink','red','purple','indigo','blue',
    'light-blue','cyan','teal','green','light-green',
    'lime','yellow','amber','orange','deep-orange'
  ];

  // Add todoService to scope
  service = new todoService($scope);
  $scope.todosCount = service.count();
  $scope.$on('todos:count', function(event, count) {
    $scope.todosCount = count;
    element = angular.element('#todosCount');

    if ( !element.hasClass('animated') ){
      $animate.addClass(element, 'animated bounce', function() {
        $animate.removeClass(element, 'animated bounce');
      });
    }
  });

  $scope.fillinContent = function(){
    $scope.htmlContent = 'content content';
  };

  // theme changing
  $scope.changeColorTheme = function(cls){
    $rootScope.$broadcast('theme:change', 'Choose template');//@grep dev
    $scope.theme.color = cls;
  };

  $scope.changeTemplateTheme = function(cls){
    $rootScope.$broadcast('theme:change', 'Choose color');//@grep dev
    $scope.theme.template = cls;
  };

  if ( !localStorageService.get('theme') ) {
    theme = {
      color: 'theme-pink',
      template: 'theme-template-dark'
    };
    localStorageService.set('theme', theme);
  }
  localStorageService.bind($scope, 'theme');

  var introductionAlert = $alert({
    title: 'Welcome to Materialism',
    content: 'Stay a while and listen',
    placement: 'top-right',
    type: 'theme',
    container: '.alert-container-top-right',
    show: false,
    template: 'assets/tpl/partials/alert-introduction.html',
    animation: 'mat-grow-top-right'
  });

  if(!localStorageService.get('alert-introduction')) {
    $timeout(function(){
      $scope.showIntroduction();
      localStorageService.set('alert-introduction', 1);
    }, 2500);
  }

  $scope.showIntroduction = function(){
    introductionAlert.show();
  };


  var refererNotThemeforest = $alert({
    title: 'Hi there!',
    content: 'Testing.',
    placement: 'top-right',
    type: 'theme',
    container: '.alert-container-top-right',
    show: false,
    animation: 'mat-grow-top-right'
  });

  if (document.referrer === '' || document.referrer.indexOf('themeforest.net') !== 0){
    $timeout(function(){
      refererNotThemeforest.show();
    }, 1750);
  }
      
      
      
      
      
  // adding demo data
  var data = [];
  for (var i = 1; i <= 50; i++){
    data.push({
      icon: PlaceholderTextService.createIcon(),
      firstname: PlaceholderTextService.createFirstname(),
      lastname: PlaceholderTextService.createLastname(),
      paragraph: PlaceholderTextService.createSentence()
    });
  }
  $scope.data = data;

  $scope.tableParams = new ngTableParams({
    page: 1,            // show first page
    count: 10,
    sorting: {
      firstname: 'asc'     // initial sorting
    }
  }, {
    filterDelay: 50,
    total: data.length, // length of data
    getData: function($defer, params) {
      var searchStr = params.filter().search;
      var mydata = [];

      if(searchStr){
        searchStr = searchStr.toLowerCase();
        mydata = data.filter(function(item){
          return item.firstname.toLowerCase().indexOf(searchStr) > -1 || item.lastname.toLowerCase().indexOf(searchStr) > -1;
        });

      } else {
        mydata = data;
      }

      mydata = params.sorting() ? $filter('orderBy')(mydata, params.orderBy()) : mydata;
      $defer.resolve(mydata.slice((params.page() - 1) * params.count(), params.page() * params.count()));
    }
  });
  
  $scope.product = [];
  $scope.product_keyword = '';
  $scope.searchProduct = function(val)
  {
      $http.get($rootScope.url + '/product/' + val).
      success(function(data){
          if(data.status == 'error')
          {
              $scope.showError = true;
          }
          else
          {
              $scope.showError = false;
              $scope.product = data;
          }
      })
      .error(function(data){
      });
      console.log('enter!');
  }
  
  $scope.selectProduct = function(item){
      console.log(item);
      $scope.selected_product = item;
  }
  
  $scope.clearSelected = function(){ delete $scope.selected_product };
}]);
