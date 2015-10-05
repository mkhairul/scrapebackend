app.controller('QuoteController',
 ['$scope', '$alert', '$rootScope', '$http', 'quoteService',
  function($scope, $alert, $rootScope, $http, quoteService){
  $scope.quoteService = quoteService;
  $scope.quoteItems = quoteService.getItems();
  $scope.accounting = accounting;

  $scope.addNote = function(item, no)
  {
      if(item.note)
      {
          $scope.note = item.note;
      }
      else
      {
          $scope.note = {};
      }
      $scope.note.id = no;
      $scope.add_note = true;
  }
  $scope.closeNote = function()
  {
      $scope.add_note = false;
  }
  $scope.saveNote = function()
  {
      $scope.quoteService.saveNote($scope.note.id, $scope.note);
      $scope.add_note = false;
  }
  $scope.clearNote = function(index)
  {
      $scope.quoteService.clearNote(index);
  }
  
  $scope.viewQuoteAsText = function(){
      var text = '';
      var items = $scope.quoteItems;
      for(var i in items)
      {
          text += sprintf('%30s %-10s\n', (items[i].name + '(' + items[i].article_id + ')'), items[i].price);
          if(items[i].note)
          {
              text += sprintf('%30s\n', '*'+items[i].note.val);
          }
      }
      text += '-'.repeat(41) + '\n';
      text += sprintf('%30s %-10s\n', 'Shipping', $scope.quoteService.shipping_cost);
      text += '='.repeat(41) + '\n';
      text += sprintf('%30s %-10s\n', 'Total Price', $scope.quoteService.total_price);
      $scope.quoteText = text;
  }
  $scope.hideQuoteText = function(){
      delete $scope.quoteText;
  }
  
  $scope.viewShippingDetails = function(){
      var text = '';
      
  }
  
}]);