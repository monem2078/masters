<?php

namespace App\Http\Controllers;

use Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Services\ContactRequestService;
use Validator;
use Helpers\SecurityHelper;
use Models\ContactRequest;
use Services\NotificationService;
use Services\UserService;
use Services\MasterService;
use Illuminate\Support\Facades\Config;

class ContactRequestController extends Controller {

    private $contactRequestService, $securityHelper;
    private $notificationService;
    private $userService;
    private $masterService;
    private $notificationHelper;

    public function __construct(ContactRequestService $contactRequestService, 
        SecurityHelper $securityHelper,
        NotificationService $notificationService,
        UserService $userService,
        MasterService $masterService ,
        NotificationHelper $notificationHelper) {
        $this->contactRequestService = $contactRequestService;
        $this->securityHelper = $securityHelper;
        $this->notificationService = $notificationService;
        $this->userService = $userService;
        $this->masterService = $masterService;
        $this->notificationHelper = $notificationHelper;
    }




    public function index($masterId){
        $requests = $this->contactRequestService->getMasterPendingRequests($masterId);
        return response()->json($requests, 200);
    }

    public function show($masterId , $requestId) {
        return response()->json($this->contactRequestService->getContactRequestDetails($requestId),200);
    }


    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, ContactRequest::rules('save'));

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->contactRequestService->create($data),200);
    }

    public function update(Request $request, $id) {

    }

    public function destroy($id) {

    }

    public function acceptContactRequest(Request $request,$requestId) {
        $data = $request->all();
        $data['request_status_type_id'] = config('requestStatusTypes.accepted');
        $user = $this->securityHelper->getCurrentUser();

        $masterId = $user->master->id;

        $validator = Validator::make($data, ContactRequest::rules('update'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $contactRequest = $this->contactRequestService->getById($requestId);
        if(!$contactRequest){
            return response()->json(['message' => 'request does not exist'] , 400);
        }


        $authorized = $this->contactRequestService->checkRequestBelongToMaster($masterId,  $requestId);

        if(!$authorized){
            return response()->json(['message' => "Not Allowed"], 401);
        }

        if($contactRequest->request_status_type_id != config('requestStatusTypes.new')){
            return response()->json(['error_key' => "request_not_pending"], 400);
        }

        $this->contactRequestService->update($requestId , $data);
        $notification = $this->notificationHelper->createAcceptedNotification($contactRequest->user_id , $masterId);
        $this->notificationService->create($notification);

        $notificationData = [
            'notification_type_id' => config('NotificationType.requestAccepted'),
            'user_id' => $contactRequest->user_id ,
            'master_id' => $masterId
            ];

        $this->notificationHelper->sendNotification(array($contactRequest->user_id) , $notificationData);

        return response()->json(['message' => 'success'],200);
    }

    public function rejectContactRequest(Request $request, $requestId) {
        $data = $request->all();
        $data['request_status_type_id'] = config('requestStatusTypes.rejected');
        $user = $this->securityHelper->getCurrentUser();

        $masterId = $user->master->id;

        $validator = Validator::make($data, ContactRequest::rules('update'));

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $contactRequest = $this->contactRequestService->getById($requestId);
        if(!$contactRequest){
            return response()->json(['message' => 'request does not exist'] , 400);
        }


        $authorized = $this->contactRequestService->checkRequestBelongToMaster($masterId,  $requestId);

        if(!$authorized){
            return response()->json(['message' => "Not Allowed"], 401);
        }

        if($contactRequest->request_status_type_id != config('requestStatusTypes.new')){
            return response()->json(['error_key' => "request_not_pending"], 400);
        }

        $this->contactRequestService->update($requestId , $data);
        $notification = $this->notificationHelper->createRejectedNotification($contactRequest->user_id , $masterId);
        $this->notificationService->create($notification);

        $notificationData = [
            'notification_type_id' => config('NotificationType.requestRejected'),
            'user_id' => $contactRequest->user_id ,
            'master_id' => $masterId
        ];

        $this->notificationHelper->sendNotification(array($contactRequest->user_id) , $notificationData);

        return response()->json(['message' => 'success'],200);
    }

    public function requestContact(Request $request) {
        $data = $request->all();
        $data['request_status_type_id'] = Config::get('requestStatusTypes.new');
        $user = $this->securityHelper->getCurrentUser();
        $data['user_id'] = $user->id;
        $validator = Validator::make($data, ContactRequest::rules('save'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $master = $this->masterService->getById($data['master_id']);

        if(!$master){
            return response()->json(['message' => 'master does not exist']);
        }

        $result = $this->contactRequestService->checkRequestExist($user->id , $data['master_id']);
        if(!$result['valid']){
            return response()->json(['error_key' => $result['error']]);
        }

        $contactRequest = $this->contactRequestService->create($data);
        $notification = $this->notificationHelper
            ->createRequestNotification($user->id , $data['master_id'] , $contactRequest->id);
        $this->notificationService->create($notification);

        $notificationData = [
            'notification_type_id' => config('NotificationType.contactRequest'),
            'user_id' => $contactRequest->user_id ,
            'master_id' => $data['master_id'],
            'contact_request_id' => $contactRequest->id
        ];

        $this->notificationHelper->sendNotification(array($master->user_id) , $notificationData);

        return response()->json($contactRequest , 200);
    }

}
