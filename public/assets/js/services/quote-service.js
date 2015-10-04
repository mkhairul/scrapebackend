app.factory('quoteService', ['$rootScope', function ($rootScope) {
    var obj = {
        'item': []
    };
    
    obj.add = function(item){
        obj.item.push(item);
    }
    
    obj.remove = function(index){
        obj.item.splice(index, 1);
    }
    
    obj.getItems = function(){
        return obj.item;
    }
    
    return obj;
}]);