var page = require('webpage').create();
var args = require('system').args;
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
    })
    /*
    for(i=0; i<categories.length; i++)
    {
        console.log(categories[i].name, categories[i].url);
    }
    */
    //console.log('Total Categories:' + categories.length);
    console.log(JSON.stringify(categories));
    phantom.exit();
});