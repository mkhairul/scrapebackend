var page = require('webpage').create();
var args = require('system').args;
d = {}
url = (args.length > 1) ? args[1]:'';
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
    }, d)
    console.log(JSON.stringify(categories));
    phantom.exit();
});