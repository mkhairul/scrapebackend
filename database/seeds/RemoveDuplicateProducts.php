<?php

use Illuminate\Database\Seeder;

class RemoveDuplicateProducts extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all duplicate products, exclude products which does not have article_id (probably got some error or something)
        $products = DB::table('product')
                        ->select(DB::raw('COUNT(*) as total_duplicate'), 'article_id')
                        ->groupBy('article_id')
                        ->having('total_duplicate', '>', 1)
                        ->having('article_id', '<>', '')
                        ->get();
        $this->command->info('Total products: ' . count($products));
        foreach($products as $product)
        {
            // Get product by article ID, list it by product_id DESC
            // Get all the categories
            // Get the first product_id
            $categories = [];
            $first_product = '';
            $product_categories = DB::table('product')
                                    ->where('article_id', $product->article_id)
                                    ->orderBy('id', 'DESC')->get();
            foreach($product_categories as $product_cat)
            {
                if($first_product == '')
                {
                    $first_product = $product_cat->id;
                }
                $categories[] = $product_cat->category_id;
            }
            
            // Save all the categories with relationship to the first product_id
            foreach($categories as $cat)
            {
                DB::table('category_product')->insert([
                        'category_id'   => $cat,
                        'product_id'    => $first_product
                    ]);
            }
            
            $this->command->info('Duplicate: ' . $product->total_duplicate);
            $this->command->info('Deleting other duplicates for: ' . $product->article_id);
            DB::table('product')->where('id', '<>', $first_product)
                                ->where('article_id', '=', $product->article_id)
                                ->delete();
        }
    }
}
