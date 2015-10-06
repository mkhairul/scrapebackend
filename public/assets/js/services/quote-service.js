app.factory('quoteService', 
    ['localStorageService', '$rootScope', '$http', 
    function (localStorageService, $rootScope, $http) {
    var obj = {
        'item': [],
        'weight': [],
        'total_weight': 0,
        'country': 'Malaysia',
        'price_per_unit': 0,
        'selected_courier': {},
        'shipping_cost': 0,
        'total_price': 0 // total price of the quote (including the shipping cost)
    };
        
    obj.countries = [
      { name: 'Malaysia', code: 'MYS' },
      { name: 'Brunei', code: 'BRN' },
      { name: 'Vietnam', code: 'VNM' }
    ]
        
    if(localStorageService.get('quote'))
    {
        obj.item = localStorageService.get('quote');
        console.log(obj.item);
    }
    
    obj.add = function(item){
        
        // Check if item already exists
        var exists = false;
        var exists_index;
        for(var i in obj.item)
        {
            if(obj.item[i].article_id == item.article_id)
            {
                exists = true;
                exists_index = i;
            }
        }
        
        if(exists == true)
        {
            obj.item[i].quantity += 1;
        }
        else
        {
            item.quantity = 1;
            obj.item.push(item);
        }
        // Calculate shipping
        obj.calculateShipping();
        obj.saveItem();
    }
    
    obj.remove = function(index){
        if(obj.item[index].quantity > 1)
        {
            obj.item[index].quantity -= 1;
        }
        else
        {
            obj.item.splice(index, 1);
        }
        obj.calculateShipping();
        obj.saveItem();
    }
    
    obj.calculateShipping = function(){
        obj.weight = [];
        obj.total_weight = 0;
        obj.selected_courier = {};
        obj.shipping_cost = 0;
        var weight = 0;
        for(var i in obj.item)
        {
            var package_weight = 0;
            for(var j in obj.item[i].packages)
            {
                if(obj.item[i].packages[j].width != "" &&
                   obj.item[i].packages[j].height != "" &&
                   obj.item[i].packages[j].length != "")
                {
                    weight = obj.item[i].packages[j].width
                             * obj.item[i].packages[j].height
                             * obj.item[i].packages[j].length;
                    if(parseFloat(weight) < parseFloat(obj.item[i].packages[j].weight))
                    {
                       weight = obj.item[i].packages[j].weight;
                    }
                }
                else
                {
                    weight = obj.item[i].packages[j].weight;
                }
                
                if(package_weight)
                {
                    package_weight = package_weight + parseFloat(weight);
                }
                else
                {
                    package_weight = weight;
                }
            }
            if(obj.item[i].quantity)
            {
                package_weight = package_weight * obj.item[i].quantity;
            }
            obj.weight.push(package_weight);
        }
        
        for(var i in obj.weight)
        {
            obj.total_weight = obj.total_weight + parseFloat(obj.weight[i]);
        }
        
        // Get the shipping details
        var couriers = [];
        $http.get($rootScope.url + 'logistic')
        .success(function(data){
            for(var i in data)
            {
              data[i].conditions = JSON.parse(data[i].conditions);
            }
            couriers = data;
            
            obj.price_per_unit = 0;
            // Check which condition is right
            for(var i in couriers)
            {
                for(var j in couriers[i].conditions)
                {
                    if(couriers[i].conditions[j].country == obj.country)
                    {
                        if(eval(obj.total_weight + ' ' + 
                                couriers[i].conditions[j].compare + ' ' + 
                                couriers[i].conditions[j].weight))
                        {
                            obj.price_per_unit = couriers[i].price_per_unit;
                            obj.selected_courier = couriers[i];
                            break;
                        }
                    }
                }
                if(obj.price_per_unit != 0)
                {
                    break;
                }
            }
            
            if(obj.price_per_unit)
            {
                obj.shipping_cost = obj.total_weight * obj.price_per_unit;
            }
            
            obj.calculateTotal();

        })
    }
    obj.calculateTotal = function(){
        obj.total_price = 0;
        for(var i in obj.item)
        {
            obj.total_price += accounting.unformat(obj.item[i].price) * obj.item[i].quantity;
        }
        obj.total_price += accounting.unformat(obj.shipping_cost);
        
        // Lazee Fee
        obj.lazeefee = accounting.toFixed(obj.total_price * 0.1, 2);
        
        obj.total_price += accounting.unformat(obj.lazeefee);
        
        console.log(obj);
    }
    obj.changeCountry = function(country){
        if(country)
        {
            obj.country = country;
            obj.calculateShipping();
        }
        console.log(obj);
        console.log(country);
    }
    obj.saveNote = function(index, note){
        obj.item[index].note = note;
        obj.saveItem();
    }
    obj.clearNote = function(index){
        delete obj.item[index].note;
        obj.saveItem();
    } 
    obj.saveItem = function(){
        localStorageService.set('quote', obj.item);
    } 
    obj.getCountry = function(){
        return obj;
    }
    obj.getItems = function(){
        obj.calculateShipping();
        return obj.item;
    }
    obj.clear = function(){
        delete obj.item;
        obj.item = [];
        obj.saveItem();
    }
    
    return obj;
}]);