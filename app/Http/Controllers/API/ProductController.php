<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Using product model
use App\Models\Product;
//Using category model
use App\Models\Category;
// Using validator
use Validator;

class ProductController extends Controller
{
    //

    
// Store method to create new products from POST: /products
    public function store(Request $request){

         //Implement try catch
         try{

            //using validator for request fields validations all required except description and avatr, if avatar is null usinf public/images/deafult.png mentioned in product migration
            $validator = Validator::make($request->all(),[
                'name' => 'required|min:2|max:100',
                'price' => 'required',
                'category' => 'required|min:2|max:100',
                'description' => 'nullable',
                'avatar' => 'nullable|image'
            ]);

            if($validator->fails()){
                // If validation fails - get the type of validation failed 
                $error = $validator->errors()->all()[0];
                return response()->json([
                    'success' => false,
                    'message' => $error,
                    'error_code' => 422
                ],422);

            }else{

                //product object
               $product = new Product;
               // category object
               $category = new Category;
               //assigning values to create a product
               $product->name = $request->name;
               $product->price = $request->price;
               
               //Using firstOrCreate to create new category if not existed and getting its id (existing/new)
                $product->category =$category->firstOrCreate(['name'=>$request->category])->id;


               $product->description = $request->description;

                //checking is avatar field has some input
               if($request->avatar && $request->avatar->isValid()){
                //avatar field exist with inputs
                    //generating a unique and new file name for avatar file using time and rand function with post file extension
                    $file_name = time().'_'.rand(111111,999999).'_'.rand(111111,999999).'.'.$request->avatar->extension();
                   //Moving the tmp file to public/images directory with created file name
                    $request->avatar->move(public_path('images'),$file_name);
                    //assigning path to store in db
                    $path = "public/images/".$file_name;
                    $product->avatar = $path;
               }
               
               if($product->save())
               {
                    //product added successfully now returning success response with product id
                    return response()->json([
                    'success' => true,
                    'message' => 'Product added successfully',
                    'data' =>   [
                                    'id' => $product->id
                                ]
                    ],200);
               }
               else{
                //product insertion failed
                return response()->json([
                    'success' => false,
                    'message' => 'Failled! Something went wrong',
                    'error_code' => 401
                    ], 401);
               }



            }


        } catch (\Exception $e){
            //catching exception and returning response
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'error_code' => 500
                ], 500
            );
        }

    }

// list method to fetch product list with pagination feature
    public function list(Request $request){

        //Implement try catch
        try{

           //using validator for request fields validations 
           $validator = Validator::make($request->all(),[
               'perPage' => 'nullable|integer',
               'page' => 'nullable|integer'
           ]);

           if($validator->fails()){
               // If validation fails - get the type of validation failed 
               $error = $validator->errors()->all()[0];
               return response()->json([
                   'success' => false,
                   'message' => $error,
                   'error_code' => 422
               ],422);

           }else{

               //product object
              $product = new Product;

              if($request->perPage)
              {
                //if perPage is present in GET request 
                $perPage = $request->perPage;
              }
              else
              {
                //if perPage is not present in GET request
                $perPage = 5;
              }

              $product_list = $product->with(['category'])->paginate($perPage);
             
           
              
             
                   //product fetched successfully now returning success response with products data
                   return response()->json([
                   'success' => true,
                   'message' => 'Products list',
                   'data' =>   [
                                   'data' => $product_list
                               ]
                   ],200);
             
          
              
             



           }


       } catch (\Exception $e){
           //catching exception and returning response
           return response()->json(
               [
                   'success' => false,
                   'message' => $e->getMessage(),
                   'error_code' => 500
               ], 500
           );
       }

   }

// getProduct to fetch product details
public function getProduct($id){

    //Implement try catch
    try{

        //fetching product details  with category details by product id which this method get as parameter from route get request
        $product =  Product::with(['category'])->find($id);
        if(is_null( $product))
        {
           //product details in response if null means not found
            return response()->json([
             'success' => false,
             'error_code' => 404,
             'message' => 'Product not found'
             ],404);
             }
        else{
                //product details in response if found
                return response()->json([
                 'success' => true,
                 'message' => 'Products detail',
                 'data' =>   [
                                 'data' =>  $product
                             ]
                 ],200);
        }
       


   } catch (\Exception $e){
       //catching exception and returning response
       return response()->json(
           [
               'success' => false,
               'message' => $e->getMessage(),
               'error_code' => 500
           ], 500
       );
   }

}
   
// delProduct method to delete product details
public function delProduct($id){

    //Implement try catch
    try{

        //fetching product details by product id which this method get as parameter from route get request
        $product =  Product::find($id);
        if(is_null( $product))
        {
           //product details in response if null means not found
            return response()->json([
             'success' => false,
             'error_code' => 404,
             'message' => 'Product not found'
             ],404);
             }
        else{
                //product deleting using softdelete
                $product->delete();
                return response()->json([
                 'success' => true,
                 'message' => 'Products deleted successfully',
                
                 ],200);
        }
       


   } catch (\Exception $e){
       //catching exception and returning response
       return response()->json(
           [
               'success' => false,
               'message' => $e->getMessage(),
               'error_code' => 500
           ], 500
       );
   }

}


}
