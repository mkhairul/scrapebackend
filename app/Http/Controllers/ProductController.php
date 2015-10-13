<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;

use App\Courier;
use App\Category;
use App\Product;

class ProductController extends Controller
{
    public function __construct()
    {
    }
    
    public function productCategories(){
        $result = Category::get();
        return response()->json($result);
    }
    
    public function availability(Request $request){
        $article_id = ($request->input('id')) ? $request->input('id'):'30298792';
        //$contents = file_get_contents('http://www.ikea.com/my/en/iows/catalog/availability/' . $article_id);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'http://www.ikea.com/my/en/iows/catalog/availability/' . $article_id);
        curl_setopt($ch, CURLOPT_FAILONERROR,1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $contents = curl_exec($ch);
        curl_close($ch);

        $xml = simplexml_load_string($contents) or die("Error: Cannot create object");
        return response()->json($xml->availability->localStore);
    }
    
    public function search(Request $request){
        $keyword = $request->input('keyword');
        $result = Product::with('packages')
                            ->where('article_id', 'LIKE', $keyword)
                            ->orWhere('name', 'LIKE', $keyword)
                            ->get();
        if(!$result){
            return response()->json(['status' => 'error', 'message' => 'not found'], 500);
        }
        return response()->json($result);
    }
    
    public function searchGet($keyword){
        $keyword = explode('.', $keyword);
        $keyword = implode('%', $keyword);
        $result = Product::with('packages')->where('article_id', 'LIKE', $keyword)->orWhere('name', 'LIKE', $keyword)->get();
        if(!$result){
            return response()->json(['status' => 'error', 'message' => 'not found']);
        }
        return response()->json($result);
    }
}
