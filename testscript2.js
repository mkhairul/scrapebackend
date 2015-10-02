var casper = require('casper').create();

casper.start('http://www.ikea.com/my/en/catalog/allproducts/department/', function(){
    casper.echo('Reading category list');
    
});
casper.then(function(){
    categories = this.evaluate(function(){
        casper.echo('test');
        var el = document.querySelectorAll('.productCategoryContainer .textContainer a');
        var results = [];
        for(i=0; i<el.length; i++)
        {
            results.push(
                { 'name': el[i].textContent.trim(),
                  'url' : el[i].href.trim()}
            );
            casper.echo(el[i].textContent.trim());
        }
        return el;
    });
    casper.echo(categories)
})

casper.run();
/*
var ikeaCrawler = function(){
    var phantom;
    return {
        'getCategories': function(url){
            var self = this;
            console.log('getCategories');
            return phridge.spawn()
            .then(function(ph){
              self.phantom = ph;
              return self.phantom.openPage(url)
            })
            .then(function(page){
                //return page.run(function(){
                    
                //})
                console.log('something happening');
                self.phantom.dispose();
            })
        }
    }
}();
            
ikeaCrawler.getCategories('http://www.ikea.com/my/en/catalog/allproducts/department/');
*/