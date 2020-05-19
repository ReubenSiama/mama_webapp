<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\SubCategory;
use App\ManufacturerDetail;
use App\brand;
use App\supplier_price;

class SupplierController extends Controller
{
    public function Pricetobrands(Request $request){

         $cat = Category::All();
         $supplier= ManufacturerDetail::All();
        return view('/marketing/Pricetobrands',['supplier'=>$supplier,'cat'=>$cat ]);
    }

    public function get_cat(Request $request){
        $cat_id=$request->cat_id;
        $name = Category::where('id',$cat_id)->pluck('category_name')->first();
        $brand= brand::where('category_id',$cat_id)->get(); 
        $sub_cat= SubCategory::where('category_id',$cat_id)->get(); 
        $supplier= ManufacturerDetail::where('category',$name)->get();
       return  response()->json(['new' =>$sub_cat, 'old' => $brand,'sp'=>$supplier]);
   }

   public function addpricetobrand(Request $request){
    
    $cat = $request->cat_id;
    $sub_cat_id=$request->sub_cat_id;
    $brand_id=$request->brand_id;
    $supplier_id=$request->supplier_id;
     $brands_price = new supplier_price;
       $brands_price->supplier_id =$supplier_id;
       $brands_price->brand_id =$brand_id;
        $brands_price->sub_cat_id =$sub_cat_id;
        $brands_price->cat_id =$cat;
        $brands_price->price =$request->pricebrand;
        $brands_price->date =$request->date;
     $brands_price->save();
   return back();
}

public function fetch_today_price(Request $request){
  
 return view('/today_product_price');
}
  

}
