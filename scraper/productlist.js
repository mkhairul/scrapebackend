var page = require('webpage').create();
var args = require('system').args;
var d = {}
url = (args.length > 1) ? args[1]:'';
//url = 'http://www.ikea.com/my/en/catalog/categories/departments/kitchen/24255/';
//url = 'http://google.com';
page.open(url, function(status){
		
    var products = page.evaluate(function(){
        var el = document.querySelectorAll('div > .productDetails');
        var results = [];
        for(i=0; i<el.length; i++)
        {
            if(!el[i].querySelector('a')){ continue; }
            results.push(
                { 'name': el[i].querySelector('.productTitle').textContent.trim(),
                  'desc': el[i].querySelector('.productDesp').textContent.trim(),
                  'url' : el[i].querySelector('a').href.trim()}
            );
        }
        return results;
    })
    console.log(JSON.stringify(products));
    phantom.exit();
});