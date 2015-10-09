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
      var template = 'Total cost: RM%s. The breakdown list is as follow:\n\n' +
                     '%s\n' + 
                     'Delivery charges: RM%s\n' + 
                     'LazeeFee (includes shopping, packaging & handling fees): RM%s';
      var item_text = '';
      var items = $scope.quoteItems;
      for(var i in items)
      {
          item_text += sprintf('%s x %s: RM%s\n', items[i].quantity, items[i].name, (accounting.unformat(items[i].price)*items[i].quantity));
          item_text += sprintf('Article No.: %s\n', items[i].article_id);
          if(items[i].note)
          {
              item_text += sprintf('Note:\n', items[i].note.val);
          }
      }
      
      var delivery_text = sprintf('%s', $scope.quoteService.shipping_cost);
      var lazeefee_text = sprintf('%s', $scope.quoteService.lazeefee);
      
      $scope.quoteText = sprintf(template, 
                                 accounting.toFixed($scope.quoteService.total_price, 2),
                                 item_text,
                                 delivery_text,
                                 lazeefee_text);
  }
  $scope.hideQuoteText = function(){
      delete $scope.quoteText;
  }
  
  $scope.viewShippingDetails = function(){
      var text = '';
      
  }
  
}]);