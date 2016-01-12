var page = require('webpage').create();
var args = require('system').args;
var d = {}
url = (args.length > 1) ? args[1]:'';

page.open(url, function(status){
    var product_detail = page.evaluate(function(){
        if(document.querySelector('#cartInfotd #productNameProdInfo') === null)
        {
            return {'status':'error'};
        }
        
        var require_assembly = 0;
        if(document.querySelector('#requiresAssembly .subTxt') === null)
        {
            require_assembly = 0;
        }
        else
        {
            require_assembly = 1;
        }
        
        var detail = {
            'name'      : document.querySelector('#cartInfotd #productNameProdInfo').textContent.trim(),
            'desc'      : document.querySelector('#cartInfotd #productTypeProdInfo').textContent.trim(),
            'price'     : document.querySelector('#cartInfotd #priceProdInfo').textContent.trim(),
            'article_id': document.querySelector('#itemNumber').textContent.trim().match(/(\d+)\.?(\d+)\.?(\d+)/g)[0].split('.').join(''),
            'main_img'  : document.querySelector('#productImg').src.trim(),
            'assembly'  : require_assembly
        };
        // Check total packages
        detail['total_package'] = function(){
            if(document.querySelectorAll('#packageInfo > div').length > 1)
            {
                //var total_package = (document.querySelector('#packageInfo #package_text')) ? document.querySelector('#packageInfo #package_text').innerHTML.trim().match(/(\d+)/g)[0]:1;
                return 1;
            }
            else if(document.querySelectorAll('#packageInfo > div').length == 1)
            {
                var total_package = parseInt((document.querySelector('#packageInfo #package_text')) ? document.querySelector('#packageInfo #package_text').innerHTML.trim().match(/(\d+)/g)[0]:2);
                return total_package;
            }
            else
            {
                return 0;
            }
        }();
        
        detail['package'] = [];
        if(detail['total_package'] === 1)
        {
            detail['debug'] = 'total package = 1 ?';
            var haveWidth = 1;
            var haveHeight = 1;
            var haveLength = 1;
            var haveDiameter = 1;
            // some products doesnt have width and height, e.g. GLANSVIDE (mattress)
            if(document.querySelector('#packageInfo2').innerHTML.trim().match(/(Width:).{6}[-+]?[0-9]*\.?[0-9]+/) === null)
            {
                haveWidth = 0;
            }
            if(document.querySelector('#packageInfo2').innerHTML.trim().match(/(Height:).{6}[-+]?[0-9]*\.?[0-9]+/) === null)
            {
                haveHeight = 0;
            }
            if(document.querySelector('#packageInfo2').innerHTML.trim().match(/(Length:).{6}[-+]?[0-9]*\.?[0-9]+/) === null)
            {
                haveLength = 0;
            }
            if(document.querySelector('#packageInfo2').innerHTML.trim().match(/(Diameter:).{6}[-+]?[0-9]*\.?[0-9]+/) === null)
            {
                haveDiameter = 0;
            }
            
            detail['package'].push(
                {
                    'total'     : 1,
                    'article_id': document.querySelector('#packageInfo1').textContent.trim().match(/(\d+)\.?(\d+)\.?(\d+)/g)[0].split('.').join(''),
                    'width'     : (haveWidth === 1) ? document.querySelector('#packageInfo2').innerHTML.trim().match(/(Width:).{6}[-+]?[0-9]*\.?[0-9]+/)[0].match(/[-+]?[0-9]*\.?[0-9]+/g)[0]:'',
                    'height'    : (haveHeight === 1) ? document.querySelector('#packageInfo2').innerHTML.trim().match(/(Height:).{6}[-+]?[0-9]*\.?[0-9]+/)[0].match(/[-+]?[0-9]*\.?[0-9]+/g)[0]:'',
                    'length'    : (haveLength === 1) ? document.querySelector('#packageInfo2').innerHTML.trim().match(/(Length:).{6}[-+]?[0-9]*\.?[0-9]+/)[0].match(/[-+]?[0-9]*\.?[0-9]+/g)[0]:'',
                    'weight'    : document.querySelector('#packageInfo2').innerHTML.trim().match(/(Weight:).{6}[-+]?[0-9]*\.?[0-9]+/)[0].match(/[-+]?[0-9]*\.?[0-9]+/g)[0],
                    'diameter'  : (haveDiameter === 1) ? document.querySelector('#packageInfo2').innerHTML.trim().match(/(Diameter:).{6}[-+]?[0-9]*\.?[0-9]+/)[0].match(/[-+]?[0-9]*\.?[0-9]+/g)[0]:'',
                }
            );
        }
        
        if(detail['total_package'] > 1)
        {
            detail['debug'] = 'total package > 1 ?;';
            
            // Have to click the link first.
            var link_package = document.querySelector('#morePackages');
            var event = document.createEvent('MouseEvents');
            event.initMouseEvent('click');
            link_package.dispatchEvent(event);
            
            var packages = document.querySelectorAll('#dynamicRows > div.rowContainerPackageNew');
            var index_start = 1;
            // Check if the total packages is the same
            // Example: http://www.ikea.com/my/en/catalog/products/60250166/
            if((packages.length - 1) != detail['total_package'])
            {
                var article_id = packages[1].querySelector('.colArticleNew').textContent.trim().split('.').join('');
                if((typeof parseInt(article_id)) === 'number')
                {
                    // Get the article ID.
                    var packages = document.querySelectorAll('#dynamicRows > div.rowContainerPackageNew');
                    var index_start = 1;
                }
            }
            
            for(var i=index_start; i<packages.length; i++) // skip the first row headers
            {
                var subpackages = packages[i].querySelectorAll('.colCollectedNew');
                var haveWidth = 1;
                var haveHeight = 1;
                var haveLength = 1;
                var haveDiameter = 1;
                var haveArticleID = 1;
                var haveWeight = 1;
                
                for(var j = 0; j<subpackages.length; j++)
                {
                    // some products doesnt have width and height, e.g. GLANSVIDE (mattress)
                    if(subpackages[j].querySelector('.colWidthNew').textContent.trim().match(/[-+]?[0-9]*\.?[0-9]+/g) === null)
                    {
                        haveWidth = 0;
                    }
                    if(subpackages[j].querySelector('.colHeightNew').textContent.trim().match(/[-+]?[0-9]*\.?[0-9]+/g) === null)
                    {
                        haveHeight = 0;
                    }
                    if(subpackages[j].querySelector('.colLengthNew').textContent.trim().match(/[-+]?[0-9]*\.?[0-9]+/g) === null)
                    {
                        haveLength = 0;
                    }
                    if(subpackages[j].querySelector('.colDiameterNew').textContent.trim().match(/[-+]?[0-9]*\.?[0-9]+/g) === null)
                    {
                        haveDiameter = 0;
                    }
                    if(subpackages[j].querySelector('.colWeightNew').textContent.trim().match(/[-+]?[0-9]*\.?[0-9]+/g) === null)
                    {
                        haveWeight = 0;
                    }
                    if(packages[i].querySelector('.colArticleNew') === null)
                    {
                        haveArticleID = 0;

                    }

                    detail['package'].push(
                        {
                            'total'     : subpackages[j].querySelector('.colPackNew').textContent.trim(),
                            'article_id': (haveArticleID === 1) ? packages[i].querySelector('.colArticleNew').textContent.trim().split('.').join(''):((article_id) ? article_id:''),
                            'width'     : (haveWidth === 1) ? subpackages[j].querySelector('.colWidthNew').textContent.trim().match(/[-+]?[0-9]*\.?[0-9]+/g)[0]:'',
                            'height'    : (haveHeight === 1) ? subpackages[j].querySelector('.colHeightNew').textContent.trim().match(/[-+]?[0-9]*\.?[0-9]+/g)[0]:'',
                            'length'    : (haveLength === 1) ? subpackages[j].querySelector('.colLengthNew').textContent.trim().match(/[-+]?[0-9]*\.?[0-9]+/g)[0]:'',
                            'weight'    : (haveWeight === 1) ? subpackages[j].querySelector('.colWeightNew').textContent.trim().match(/[-+]?[0-9]*\.?[0-9]+/g)[0]:'',
                            'diameter'  : (haveDiameter === 1) ? subpackages[j].querySelector('.colDiameterNew').textContent.trim().match(/[-+]?[0-9]*\.?[0-9]+/g)[0]:''
                        }
                    );
                }
            }
        }
                                  
        return detail;
    })
    console.log(JSON.stringify(product_detail));
    phantom.exit();
});
