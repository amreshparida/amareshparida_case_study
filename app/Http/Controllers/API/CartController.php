<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Using product model
use App\Models\Product;
//Using category model
use App\Models\Category;
//Using cart model
use App\Models\Cart;
// Using validator
use Validator;

class CartController extends Controller
{
    //
    // addToCart method to store requests from POST: /cart
    public function  addToCart(Request $request)
    {

        //Implement try catch
        try {

            //using validator for request fields validations all required except description and avatr, if avatar is null usinf public/images/deafult.png mentioned in product migration
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|integer',
                'session_id' => 'required',
                'qty' => 'bail|required|numeric|gt:0'
            ]);

            if ($validator->fails()) {
                // If validation fails - get the type of validation failed 
                $error = $validator->errors()->all()[0];
                return response()->json([
                    'success' => false,
                    'message' => $error,
                    'error_code' => 422
                ], 422);
            } else {

                $cart =  Cart::where(["session_id" => $request->session_id, "product_id" => $request->product_id])->first();
                if (is_null($cart)) {

                    // cart object
                    $cart = new Cart;

                    //assigning values to create a cart item
                    $cart->session_id = $request->session_id;
                    $session_id = $cart->session_id;
                    $cart->product_id = $request->product_id;
                    $cart->qty = $request->qty;

                    if (auth('api')->user()) {
                        $cart->user_id = auth('api')->user()->id;
                    }

                    if ($cart->save()) {
                        //item added to cart
                        //Now update if access token is present to map session id
                        if (auth('api')->user()) {
                            $cart->where('session_id', $session_id)
                                ->update([
                                    'user_id' => auth('api')->user()->id
                                ]);
                        }

                        return response()->json([
                            'success' => true,
                            'message' => 'Item added to cart successfully',
                            'data' =>   [
                                'session_id' => $session_id
                            ]
                        ], 200);
                    } else {
                        //product insertion failed
                        return response()->json([
                            'success' => false,
                            'message' => 'Failled! Something went wrong',
                            'error_code' => 401
                        ], 401);
                    }
                } else {

                    $cart->qty = $cart->qty + $request->qty;
                    if ($cart->update()) {
                        //item added to cart
                        //Now update if access token is present to map session id
                        if (auth('api')->user()) {
                            $cart->where('session_id', $request->session_id)
                                ->update([
                                    'user_id' => auth('api')->user()->id
                                ]);
                        }

                        return response()->json([
                            'success' => true,
                            'message' => 'Item added to cart successfully',
                            'data' =>   [
                                'session_id' =>  $request->session_id
                            ]
                        ], 200);
                    } else {
                        //product insertion failed
                        return response()->json([
                            'success' => false,
                            'message' => 'Failled! Something went wrong',
                            'error_code' => 401
                        ], 401);
                    }
                }
            }
        } catch (\Exception $e) {
            //catching exception and returning response
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'error_code' => 500
                ],
                500
            );
        }
    }


    // updateCart method to update cart by requests from PUT: /cart
    public function  updateCart($id, Request $request)
    {

        //Implement try catch
        try {

            //using validator for request fields validations all required except description and avatr, if avatar is null usinf public/images/deafult.png mentioned in product migration
            $validator = Validator::make($request->all(), [
                'session_id' => 'required',
                'qty' => 'bail|required|numeric|gt:0'
            ]);

            if ($validator->fails()) {
                // If validation fails - get the type of validation failed 
                $error = $validator->errors()->all()[0];
                return response()->json([
                    'success' => false,
                    'message' => $error,
                    'error_code' => 422
                ], 422);
            } else {


                $cart =  Cart::where(["session_id" => $request->session_id, "product_id" => $id])->first();
                if (is_null($cart)) {

                    //Now update if access token is present to map session id
                    $cart = new Cart;
                    if (auth('api')->user()) {

                        $cart->where('session_id', $request->session_id)
                            ->update([
                                'user_id' => auth('api')->user()->id
                            ]);
                    }

                    return response()->json([
                        'success' => false,
                        'message' => 'Product item not found in cart',
                        'error_code' => 404
                    ], 404);
                } else {

                    $cart->qty = $request->qty;

                    if (!is_null($cart->user_id) && !auth('api')->user()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Authorization required',
                            'error_code' => 401
                        ], 401);
                    }



                    if ($cart->update()) {
                        //item added to cart
                        //Now update if access token is present to map session id
                        if (auth('api')->user()) {
                            $cart->where('session_id', $request->session_id)
                                ->update([
                                    'user_id' => auth('api')->user()->id
                                ]);
                        }

                        return response()->json([
                            'success' => true,
                            'message' => 'Item updated to cart successfully',
                            'data' =>   [
                                'session_id' =>  $request->session_id
                            ]
                        ], 200);
                    } else {
                        //product insertion failed
                        return response()->json([
                            'success' => false,
                            'message' => 'Failled! Something went wrong',
                            'error_code' => 401
                        ], 401);
                    }
                }
            }
        } catch (\Exception $e) {
            //catching exception and returning response
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'error_code' => 500
                ],
                500
            );
        }
    }


        // deleteCart method to delete cart items by requests from DEL: /cart
        public function  deleteCart($id, Request $request)
        {
    
            //Implement try catch
            try {
    
                //using validator for request fields validations all required except description and avatr, if avatar is null usinf public/images/deafult.png mentioned in product migration
                $validator = Validator::make($request->all(), [
                    'session_id' => 'required'                
                ]);
    
                if ($validator->fails()) {
                    // If validation fails - get the type of validation failed 
                    $error = $validator->errors()->all()[0];
                    return response()->json([
                        'success' => false,
                        'message' => $error,
                        'error_code' => 422
                    ], 422);
                } else {
    
    
                    $cart =  Cart::where(["session_id" => $request->session_id, "product_id" => $id])->first();
                    if (is_null($cart)) {
    
                        //Now update if access token is present to map session id
                        $cart = new Cart;
                        if (auth('api')->user()) {
    
                            $cart->where('session_id', $request->session_id)
                                ->update([
                                    'user_id' => auth('api')->user()->id
                                ]);
                        }
    
                        return response()->json([
                            'success' => false,
                            'message' => 'Product item not found in cart',
                            'error_code' => 404
                        ], 404);
                    } else {
    
                       
    
                        if (!is_null($cart->user_id) && !auth('api')->user()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Authorization required',
                                'error_code' => 401
                            ], 401);
                        }
    
    
    
                        if ($cart->delete()) {
                            //item added to cart
                            //Now update if access token is present to map session id
                            if (auth('api')->user()) {
                                $cart->where('session_id', $request->session_id)
                                    ->update([
                                        'user_id' => auth('api')->user()->id
                                    ]);
                            }
    
                            return response()->json([
                                'success' => true,
                                'message' => 'Item deleted from cart successfully',
                                'data' =>   [
                                    'session_id' =>  $request->session_id
                                ]
                            ], 200);
                        } else {
                            //product insertion failed
                            return response()->json([
                                'success' => false,
                                'message' => 'Failled! Something went wrong',
                                'error_code' => 401
                            ], 401);
                        }
                    }
                }
            } catch (\Exception $e) {
                //catching exception and returning response
                return response()->json(
                    [
                        'success' => false,
                        'message' => $e->getMessage(),
                        'error_code' => 500
                    ],
                    500
                );
            }
        }


             // viewCart method to delete cart items by requests from GET: /cart
             public function  viewCart(Request $request)
             {
         
                 //Implement try catch
                 try {
         
                     //using validator for request fields validations all required except description and avatr, if avatar is null usinf public/images/deafult.png mentioned in product migration
                     $validator = Validator::make($request->all(), [
                         'session_id' => 'required'                
                     ]);
         
                     if ($validator->fails()) {
                         // If validation fails - get the type of validation failed 
                         $error = $validator->errors()->all()[0];
                         return response()->json([
                             'success' => false,
                             'message' => $error,
                             'error_code' => 422
                         ], 422);
                     } else {
         
         
                         $cart_data =  Cart::where(["session_id" => $request->session_id])->get();
                         if (auth('api')->user()) {
                            $cart_data =  Cart::where(["session_id" => $request->session_id, "user_id"=> auth('api')->user()->id])->get();
                         }
         
                             //Now update if access token is present to map session id
                             $cart = new Cart;
                             if (auth('api')->user()) {
         
                                 $cart->where('session_id', $request->session_id)
                                     ->update([
                                         'user_id' => auth('api')->user()->id
                                     ]);
                             }
         
                             return response()->json([
                                 'success' => true,
                                 'message' => 'Cart Data',
                                 'data' => [
                                    'data' =>  $cart_data
                                 ]
                             ], 200);
                       
                         
                        
                     }
                 } catch (\Exception $e) {
                     //catching exception and returning response
                     return response()->json(
                         [
                             'success' => false,
                             'message' => $e->getMessage(),
                             'error_code' => 500
                         ],
                         500
                     );
                 }
             }   



}
