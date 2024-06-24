<?php

namespace App\Http\Controllers\API\Auth;

use App\Notifications\LoginNeedsVerification;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;

class LoginController extends BaseController
{
    //
   
    public function login(Request $request): JsonResponse
    {

        try{

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required|numeric|min:10',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(),422);
            }

            $user = User::firstOrCreate([
                "phone" => $request->phone
            ],[

                "name" => $request->name
            ]
            );

            if(!$user){
                return $this->sendError('Issue Occured',"User creation could not be processesd");
            }

            $user->notify(new LoginNeedsVerification());


            return $this->sendResponse([],"Login code sent successfully");

        }catch(Exception $e){
            Log::error($e->getMessage());
            return $this->sendError('Issue Occured', $e->getMessage(),500);
        }


    }

    public function verify(Request $request): JsonResponse
    {

        try {

            $validator = Validator::make($request->all(), [
                'phone' => 'required|numeric|min:10',
                'login_code' => 'required|numeric|between:111111,999999'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }

            $user = User::where("phone", $request->phone)
                ->where("login_code", $request->login_code)
                ->first();

            if (!$user) {
                return $this->sendError('Issue Occured', "Invalid Verification code");
            }

            $user->update([
                "login_code" => null
            ]);
            $success['token'] = $user->createToken($request->login_code)->plainTextToken;


            return $this->sendResponse($success, "Verification successfuly");

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError('Issue Occured', $e->getMessage(), 500);
        }


    }
}
