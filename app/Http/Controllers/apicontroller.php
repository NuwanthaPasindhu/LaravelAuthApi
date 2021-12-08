<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Boolean;

class apicontroller extends Controller
{

    /**
     * Get user by the Token
     * Take the Token
     * @param Request $request
     * @return user $user
     */
    public function user(Request $request)
    {
        return response()->json(['user'=>$request->user()]);
    }

    /**
     *  User Register
     * @param Request $request
     * @return Boolean $return
     */
        public function registar(Request $request)
        {
          $validate = Validator::make($request->all(),[
              'name' => 'required',
              'email' => 'required|email',
              'password' => 'required|min:8'
          ]);
                if ($validate->fails()) {
                    return response()->json([
                        'status' => '400',
                        'massage' => 'error'
                    ],400);

                }

                $user = new User();

                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->password = bcrypt($request->input('password'));
                $user->roles = ['user'];
                if($user->save()){
                    return response()->json([
                        'status' => '200',
                        'massage' => 'Registerd Successfully'
                    ],200);

               }


            }


    /**
     *  User Login
     * @param Request $request
     * @return user $user with tokan
     */
        public function login(Request $request)
        {
          $validate = Validator::make($request->all(),[
              'email' => 'required|email',
              'password' => 'required'
          ]);

                if ($validate->fails()) {
                    return response()->json([
                        'status' => '400',
                        'massage' => 'error'
                    ],400);


                }

                if (Auth::attempt($request->only('email','password'))) {
                    $user = User::where("email", $request->email)->select('id','name','email','roles')->first();
                   $token= $user->createToken('User Token',$user->roles)->plainTextToken;
                    Arr::add($user,'token',$token);
                    return response()->json($user);
                } else {
                    return response()->json([
                        'status' => '401',
                        'massage' => 'UnAuthorized'
                    ],401);
                }





            }

            /**
             * Logout
             * @return bool
             */
            public function logout(Request $request)
            {
                $user = $request->user();
                $user->currentAccessToken()->delete();
                return response()->json([
                    'status' => '200',
                    'massage' => 'Logout Successfully'
                ],200);
            }



}
