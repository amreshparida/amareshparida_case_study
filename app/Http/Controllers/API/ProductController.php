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
                    ],);
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
}
