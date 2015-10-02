//var page = require('webpage').create();
/*
page.open('http://www.ikea.com/my/en/catalog/products/70273704/', function(status){
    var packageInfo = page.evaluate(function(){
        // extract width, height and length
        var re = /(\d+)\s(cm)/g;
        var measurements = document.getElementById('packageInfo2').innerHTML.match(re);
        var tmp = measurements.join(',');
        var whl = tmp.match(/(\d+)/g);
        return {'width': whl[0], 'height': whl[1], 'length': whl[2]};
    });
    console.log(packageInfo.width);
    phantom.exit();
});
*/
var phantom = require('phantom');
var Promise = require('promise');

var ikeaCrawler = {
    'getCategories': function(url){
        var self = this;
        return new Promise(function(resolve, reject){
            page.open(url, function(status){
            var categories = page.evaluate(function(){
                var el = document.querySelectorAll('.productCategoryContainer .textContainer a');
                var results = [];
                for(i=0; i<el.length; i++)
                {
                    results.push(
                        { 'name': el[i].textContent.trim(),
                          'url' : el[i].href.trim()}
                    );
                }
                return results;
            })
            for(i=0; i<categories.length; i++)
            {
                //console.log(categories[i].name, categories[i].url);
            }
            //self.cat = categories;
            resolve(categories)
        });
      })
    }
};

ikeaCrawler.getCategories('http://www.ikea.com/my/en/catalog/allproducts/department/').then(function(){
    console.log('finish?');
    phantom.exit(1);
});