<?php
require_once 'vendor/autoload.php';

use Goutte\Client;

/*
 retrieve categories
 for each categories, get product lists
 for each product, get product details
 product details
 - name
 - price
 - article no
 - total package 
   if "#packageInfo > div" > 2, only 1 package
     - article ID/No: preg_match_all('(\d+).(\d+).(\d+)', $str, $matches)
     - package dimensions, ".packageInfo2"
       - width
       - height
       - length
       - weight
   else
     - count ".rowContainerPackageNew"
  - availability
    XML http://www.ikea.com/my/en/iows/catalog/availability/50256753
*/
/*
$main_url = 'http://www.ikea.com';

$client = new Client();
print "Crawling url\n";
$crawler = $client->request('GET', $main_url.'/my/en/catalog/allproducts/department/');

$categories = $crawler->filter('.productCategoryContainer .textContainer a');
print "Total Categories: " . count($categories) . "\n";
for($i=0; $i<count($categories); $i++)
{
  print trim($categories->slice($i)->text()) . "\n";  
}
*/
    
    
class IkeaCrawler
{
    protected $client;
    protected $main_url;
    protected $cat_url;
    protected $cat = [];
    protected $products = [];
    
    protected $debug = 1;
    
    public function __construct($url='')
    {
        $this->client = new Client();
        $this->main_url = ($url) ? $url:'http://www.ikea.com';
    }
    
    public function getCategories($url='', $refresh = false)
    {
        if($this->debug == 1){ print "Retrieving categories\n"; }
        
        $this->cat_url = ($url) ? $url:$this->main_url.'/my/en/catalog/allproducts/department/';
        if(count($this->cat) > 0 && $refresh == false)
        {
            return $cat;
        }
        $crawler = $this->client->request('GET', $this->cat_url);
        $categories = $crawler->filter('.productCategoryContainer .textContainer a');
        for($i=0; $i<count($categories); $i++)
        {
            $this->cat[] = ['name' => $categories->slice($i)->text(),
                            'url'  => $categories->slice($i)->attr('href')];
        }
        
        return $this->cat;
    }
    
    public function getProductList($partialUrl = '', $categoryName = '')
    {
        if($this->debug == 1){ print "Retrieving product list\n"; }
        
        $url = $this->main_url . $partialUrl;
        $crawler = $this->client->request('GET', $url);
        $products = $crawler->filter('.productDetails');
        for($i=0; $i<count($products); $i++)
        {
            $this->products[] = ['name' => $products->filter('.productTitle')->slice(0)->text(),
                                 'url'  => $products->filter('a')->slice(0)->attr('href')];
        }
        return $this->products;
    }
    
    public function getProductDetails($partialUrl = '')
    {
        if($this->debug == 1){ print "Retrieving product details\n"; }
        
        $url = $this->main_url . $partialUrl;
        $crawler = $this->client->request('GET', $url);
        
        // Get the name and description
        $detail = ['name'  => trim($crawler->filter('#cartInfotd #productNameProdInfo')->slice(0)->text()),
                   'desc'  => trim($crawler->filter('#cartInfotd #productTypeProdInfo')->slice(0)->text()),
                   'price' => trim($crawler->filter('#cartInfotd #priceProdInfo')->slice(0)->text()),
                   'url'   => $url,
                  ];
        if(count($crawler->filter('#packageInfo > div')) > 1)
        {
            $detail['total_package'] = 1;
        }
        elseif(count($crawler->filter('#packageInfo > div')) == 1)
        {
            $detail['total_package'] = 2;
        }
        else
        {
            $detail['total_package'] = 0;
        }
        
        if($detail['total_package'] == 1)
        {
            $detail['package_detail'] = $crawler->filter('#packageInfo #packageInfo2')->slice(0)->text();
            print_r($crawler->filter('#package')->slice(0)->html());
        }
        
        return $detail;
    }
}

$crawler = new IkeaCrawler();
$categories = $crawler->getCategories();
//print_r($categories[0]);
//print_r($crawler->getProductList($categories[0]['url'], $categories[0]['name']));
$product_list = $crawler->getProductList($categories[0]['url'], $categories[0]['name']);
print_r($crawler->getProductDetails($product_list[0]['url']));

/*
for($i=0; $i<1; $i++)
{
    print $categories[$i]->text();
}
*/
/*
$crawler->filter('.productCategoryContainer .textContainer a')->each(function($node) use ($client){
    print trim($node->text()).", ".$node->attr('href')."\n";
    print "Retrieving product list";
    $product_list_crawl = $client->request('GET', $main_url.$node->attr('href'));
    $product_list_crawl->filter('.productDetails .productTitle')->each(function($node){
        print $node->text() . "\n";
    });
    return;
});
*/
