<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;

class DriverController extends BaseController
{
    //

    public function show(Request $request): JsonResponse
    {

        try {

           

            $user = $request->user();
            $user->load("driver");

            if (!$user) {
                return $this->sendError('Issue Occured', "Unauthenticated User");
            }

            
            $success['user'] = $user;


            return $this->sendResponse($success, "User retrived");

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError('Issue Occured', $e->getMessage(), 500);
        }


    }

    public function update(Request $request): JsonResponse
    {

        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'year' => 'required|numeric',
                'make' => 'required',
                'model' => 'required',
                'color' => 'required|alpha',
                'license_plate' => 'required'
               
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 400);
            }

           
           

            $user->driver()->updateOrCreate($request->only([
                "year",
                "make",
                "model",
                "color",
                "license_plate"
            ]));
            


            return $this->sendResponse($user->load("driver"), "Driver data updated");

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError('Issue Occured', $e->getMessage(), 400);
        }


    }
}
