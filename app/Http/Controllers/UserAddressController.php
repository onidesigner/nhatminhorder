<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\UserAddress;
use App\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAddressController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addNewUserAddress(Request $request){
        $send_data = $request->all();

        $validator = Validator::make($send_data, [
            'province_id' => 'required',
            'district_id' => 'required',
            'detail' => 'required',
            'reciver_name' => 'required',
            'reciver_phone' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return Response::json(array('success' => false, 'message' => implode('<br>', $errors) ));
        }

        $location = new Location();
        if(!$location->checkProvinceContainDistrict($send_data['province_id'], $send_data['district_id'])):
            return Response::json(array('success' => false, 'message' => "Du lieu Thanh pho hoac Quan Huyen khong chinh xac!" ));
        endif;

        $current_user_id = Auth::user()->id;
        $send_data['user_id'] = $current_user_id;

        $user_address = new UserAddress();

        if(!$send_data['user_address_id'] && !$user_address->checkMaxUserAddress($current_user_id)):
            return Response::json(array('success' => false, 'message' => sprintf('Ban chi co the them toi da %s dia chi!', $user_address->user_address_max) ));
        endif;

        $user_address->addNewUserAddress($send_data);

        return Response::json(array('success' => true));
    }

    public function setDefaultUserAddress(Request $request){
        $id = $request->input('id');
        $current_user_id = Auth::user()->id;

        $user_address = new UserAddress();
        $user_address->setDefaultUserAddress($id, $current_user_id);

        return Response::json(array('success' => true));
    }

    public function deleteUserAddress(Request $request){
        $id = $request->input('id');
        $action = $request->input('action');
        $current_user_id = Auth::user()->id;

        switch ($action):
            case "delete":

                $user_address = new UserAddress();
                $user_address->deleteUserAddress($id, $current_user_id);

                break;
            case "update":
                break;
        endswitch;

        return Response::json(array('success' => true));
    }
}
