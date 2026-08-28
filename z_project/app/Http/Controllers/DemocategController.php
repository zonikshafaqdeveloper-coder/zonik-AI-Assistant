<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;

class DemocategController extends Controller
{
   

public function procat(Request $request)
{
    $result['categories']=DB::table('categories')->get();
   return view('web.demo.category',$result);
}


public function category(Request $request,$slug)
{   
    $sort="";
    $sort_txt="";
    $filter_price_start="";
    $filter_price_end="";
    $color_filter="";
    $colorFilterArr=[];
   
    if($request->get('sort')!==null)
    {
        $sort=$request->get('sort');
    }    
    
    $query=DB::table('products');
    $query=$query->leftJoin('categories','categories.id','=','products.category_id');
    $query=$query->leftJoin('subcategories','subcategories.id','=','products.subcategory_id');
   
    $query=$query->where(['categories.category_slug'=>$slug]);
    
   
    if($request->get('brand_filter')!==null){
        $brand_filter=$request->get('brand_filter');        
        $brandFilterArr=explode(":",$brand_filter);
        $brandFilterArr=array_filter($brandFilterArr);
       
        $query=$query->where(['products_attr._id'=>$request->get('brand_filter')]);
        
    }

    $query=$query->distinct()->select('products.*');
    $query=$query->get();
    $result['product']=$query;
    
    foreach($result['product'] as $list1){
       
        $query1=DB::table('products_attr');
        $query1=$query1->leftJoin('sizes','sizes.id','=','products_attr.size_id');
        $query1=$query1->leftJoin('colors','colors.id','=','products_attr.color_id');
        $query1=$query1->where(['products_attr.products_id'=>$list1->id]);
        $query1=$query1->get();
        $result['product_attr'][$list1->id]=$query1;
    }

    $result['colors']=DB::table('colors')
    ->where(['status'=>1])
    ->get();

    $result['categories_left']=DB::table('categories')->get();
    $cat=$result['categories_left'];


  
    $result['slug']=$slug;
    $result['sort']=$sort;
    $result['sort_txt']=$sort_txt;
    $result['filter_price_start']=$filter_price_start;
    $result['filter_price_end']=$filter_price_end;
    $result['color_filter']=$color_filter;
    $result['colorFilterArr']=$colorFilterArr;
    return view('front.category',$result);
}














}
















