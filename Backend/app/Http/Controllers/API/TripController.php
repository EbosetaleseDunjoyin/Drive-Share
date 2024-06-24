<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Trip;
use App\Events\TripAccepted;
use Illuminate\Http\Request;
use App\Events\TripStatusChanged;
use Illuminate\Http\JsonResponse;
use App\Events\TripLocationChanged;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;

class TripController extends BaseController
{
    //
    public function show(Request $request, Trip $trip): JsonResponse
    {

        try {
            $user = $request->user();

            if(
                $trip->user->id !== $user->id || 
                (($trip->driver && $user->driver ) &&
                ($trip->driver->id !== $user->driver->id))
            ){
                return $this->sendError('Issue Occured', "Unauthorized User", 400);
            }

            return $this->sendResponse($trip, "Trip retrived successfully");

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError('Issue Occured', $e->getMessage(), 500);
        }


    }
    public function store(Request $request): JsonResponse
    {

        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'origin' => 'required',
                'destination' => 'required',
                'destination_name' => 'required',

            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }


            $trip = $user->trips()->create($request->only([
                'origin' ,
                'destination' ,
                'destination_name' ,
            ]));



            return $this->sendResponse($trip, "Trip created successfully");

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError('Issue Occured', $e->getMessage(), 400);
        }


    }
    public function accept(Request $request, Trip $trip): JsonResponse
    {

        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'driver_location' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }
            $trip->update([
                'driver_id' => $user->id,
                'driver_location' => $request->driver_location,
            ]);

            $trip->load("driver.user");

            TripAccepted::dispatch($trip,$user);

            return $this->sendResponse($trip, "Trip status updated");

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError('Issue Occured', $e->getMessage(), 400);
        }


    }
    public function status(Request $request, Trip $trip): JsonResponse
    {

        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:started,completed',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 400);
            }


            $trip->update($request->only([
                'status' 
            ]));

            $trip->load("driver.user");

            TripStatusChanged::dispatch($trip, $user);
            return $this->sendResponse($trip, "Trip status updated");

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError('Issue Occured', $e->getMessage(), 400);
        }


    }
    public function driverLocation(Request $request, Trip $trip): JsonResponse
    {

        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'driver_location' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 400);
            }

            $trip->update($request->only([
                'driver_location' 
            ]));

            TripLocationChanged::dispatch($trip, $user);
            
            return $this->sendResponse($trip, "Trip driver location updated");

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError('Issue Occured', $e->getMessage(), 400);
        }


    }
}
