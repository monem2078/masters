<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\NotificationService;
use Validator;
use Helpers\SecurityHelper;
use Models\Notification;

class NotificationController extends Controller
{

    private $notificationService, $securityHelper;

    public function __construct(NotificationService $notificationService, SecurityHelper $securityHelper)
    {
        $this->notificationService = $notificationService;
        $this->securityHelper = $securityHelper;
    }


    public function index()
    {
        return response()->json($this->notificationService->getAll(), 200);
    }

    public function show($id)
    {
        return response()->json($this->notificationService->getById($id), 200);
    }


    public function store(Request $request, $userId)
    {
        $data = $request->all();
        $validator = Validator::make($data, Notification::rules('save'));

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->notificationService->create($data), 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, Notification::rules('update'));

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->notificationService->update($id, $data), 200);
    }

    public function destroy($id)
    {

    }


    public function readUserNotifications(Request $request)
    {
        $user = $this->securityHelper->getCurrentUser();
        $this->notificationService->readAllUserNotifications($user->id);

        return response()->json(['message' => 'success']);
    }

    public function getUserNotifications()
    {
        $user = $this->securityHelper->getCurrentUser();
        $result['notifications'] = $this->notificationService->getUserNotifications($user->id);

        for ($i = 0; $i < sizeof($result['notifications']); $i++) {
            $result['notifications'][$i]['parameters'] = unserialize($result['notifications'][$i]['parameters']);
            if (!$result['notifications'][$i]['parameters'])
                $result['notifications'][$i]['parameters'] = null;
        }

        $result['unread_notifications_count'] = $this->notificationService->getNumberOfUnreadNotifications($user->id);
        return response()->json($result, 200);
    }
}
