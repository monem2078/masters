<?php
/**
 * Created by PhpStorm.
 * User: amr.salah
 * Date: 3/27/2019
 * Time: 2:06 PM
 */

namespace Helpers;


use Connectors\FirebaseConnector;
use Repositories\UserFcmTokenRepository;
use Services\MasterService;
use Services\UserService;

class NotificationHelper
{

    private $masterService;
    private $userService;
    private $firebaseConnector;
    private $userFcmTokenRepository;

    public function __construct(MasterService $masterService,
                                UserService $userService,
                                FirebaseConnector $firebaseConnector,
                                UserFcmTokenRepository $userFcmTokenRepository)
    {
        $this->masterService = $masterService;
        $this->userService = $userService;
        $this->firebaseConnector = $firebaseConnector;
        $this->userFcmTokenRepository = $userFcmTokenRepository;
    }

    public function createAcceptedNotification($userId, $masterId)
    {

        $master = $this->masterService->getById($masterId);
        $masterName = $master->user->name;
        $notification['user_id'] = $userId;
        $notification['notification_title'] = "Contact request Accepted";
        $notification['notification_title_ar'] = "قبول طلب تواصل";
        $notification['notification_text'] = "Your request for " . $masterName . "'s contacts has been approved";
        $notification['notification_text_ar'] = $masterName . "تم قبول طلبك لبيانات التواصل مع ";
        $notification['notification_type_id'] = config('NotificationType.requestAccepted');
        $notification['parameters'] = "";
        return $notification;
    }


    public function createRejectedNotification($userId, $masterId)
    {

        $master = $this->masterService->getById($masterId);
        $masterName = $master->user->name;

        $notification['user_id'] = $userId;
        $notification['notification_title'] = "Contact request Rejected";
        $notification['notification_title_ar'] = "رفض طلب تواصل";
        $notification['notification_text'] = "Your request for " . $masterName . "'s contacts has been rejected";
        $notification['notification_text_ar'] = $masterName . "تم رفض طلبك لبيانات التواصل مع ";
        $notification['notification_type_id'] = config('NotificationType.requestRejected');
        $notification['parameters'] = "";

        return $notification;
    }

    public function createRequestNotification($senderUserId, $masterId, $contactRequestId)
    {
        $user = $this->userService->getById($senderUserId);
        $userName = $user->name;
        $master = $this->masterService->getById($masterId);
        $notification['user_id'] = $master->user_id;
        $notification['notification_title'] = "Contact request";
        $notification['notification_title_ar'] = "طلب تواصل";
        $notification['notification_text'] = $userName . " requested your contacts";
        $notification['notification_text_ar'] = "طلب بياناتك للتواصل " . $userName;
        $notification['notification_type_id'] = config('NotificationType.contactRequest');
        $notification['action_user_id'] = $senderUserId;
        $notification['parameters'] = serialize(array(
                'sender_id' => $senderUserId,
                'master_id' => $masterId,
                'contact_request_id' => $contactRequestId,
                'sender_name' => $userName
            )
        );

        return $notification;
    }


    public function createRatingCreatedNotification($userId, $masterId, $masterRatingId)
    {
        $user = $this->userService->getById($userId);
        $userName = $user->name;
        $master = $this->masterService->getById($masterId);
        $notification['user_id'] = $master->user_id;
        $notification['notification_title'] = "New Rating";
        $notification['notification_title_ar'] = " تقييم جديد";
        $notification['notification_text'] = $userName . " gave you new rating";
        $notification['notification_text_ar'] = $userName . " أعطاك تقييم جديد";
        $notification['notification_type_id'] = config('NotificationType.newRating');
        $notification['action_user_id'] = $userId;


        $notification['parameters'] = serialize(array(
                'sender_id' => $userId,
                'master_id' => $masterId,
                'master_rating_id' => $masterRatingId,
                'sender_name' => $userName
            )
        );

        return $notification;
    }


    public function createRatingUpdatedNotification($userId, $masterId, $masterRatingId)
    {
        $user = $this->userService->getById($userId);
        $userName = $user->name;
        $userGender = $user->gender_id;
        $master = $this->masterService->getById($masterId);
        $notification['user_id'] = $master->user_id;
        $notification['notification_title'] = "Update Rating";
        $notification['notification_title_ar'] = "تحديث تقييم";

        if ($userGender == config('gender.male')) {
            $notification['notification_text'] = $userName . " has updated his rating";
            $notification['notification_text_ar'] = $userName . " حدث تقييمه";
        }else if($userGender == config('gender.female')){
            $notification['notification_text'] = $userName . " has updated her rating";
            $notification['notification_text_ar'] = $userName . " حدثت تقييمها";
        }else{
            $notification['notification_text'] = $userName . " has updated his/her rating";
            $notification['notification_text_ar'] = $userName . " حدث تقييمه";
        }

        $notification['notification_type_id'] = config('NotificationType.updateRating');
        $notification['action_user_id'] = $userId;

        $notification['parameters'] = serialize(array(
                'sender_id' => $userId,
                'master_id' => $masterId,
                'master_rating_id' => $masterRatingId,
                'sender_name' => $userName
            )
        );

        return $notification;
    }


    public function sendNotification($userIds, $data)
    {
        $englishUsers = $this->userFcmTokenRepository->getEnglishFcmTokens($userIds);
        $arabicUsers = $this->userFcmTokenRepository->getArabicFcmTokens($userIds);
        $englishFcmTokens = array_column($englishUsers->toArray(), 'fcm_token');
        $arabicFcmTokens = array_column($arabicUsers->toArray(), 'fcm_token');

        $englishNotification = $this->getEnglishNotification($data);
        $arabicNotification = $this->getArabicNotification($data);


        $this->firebaseConnector->sendNotification($englishFcmTokens, $englishNotification['title'],
            $englishNotification['body'], $data);

        $this->firebaseConnector->sendNotification($arabicFcmTokens, $arabicNotification['title'],
            $arabicNotification['body'], $data);
    }


    private function getEnglishNotification($data)
    {
        $notification = [];
        $notification['title'] = "english";
        $notification['body'] = "english";

        if (isset($data['notification_type_id'])) {
            if ($data['notification_type_id'] == config('NotificationType.contactRequest')) {
                $requestNotification = $this->createRequestNotification($data['user_id'],
                    $data['master_id'], $data['contact_request_id']);
                $notification['title'] = $requestNotification['notification_title'];
                $notification['body'] = $requestNotification['notification_text'];
            }

            if ($data['notification_type_id'] == config('NotificationType.requestAccepted')) {
                $acceptedNotification = $this->createAcceptedNotification($data['user_id'], $data['master_id']);
                $notification['title'] = $acceptedNotification['notification_title'];
                $notification['body'] = $acceptedNotification['notification_text'];
            }

            if ($data['notification_type_id'] == config('NotificationType.requestRejected')) {
                $rejectedNotification = $this->createRejectedNotification($data['user_id'], $data['master_id']);
                $notification['title'] = $rejectedNotification['notification_title'];
                $notification['body'] = $rejectedNotification['notification_text'];
            }

            if ($data['notification_type_id'] == config('NotificationType.newRating')) {
                $ratingNotification = $this->createRatingCreatedNotification($data['user_id'],
                    $data['master_id'], $data['master_rating_id']);

                $notification['title'] = $ratingNotification['notification_title'];
                $notification['body'] = $ratingNotification['notification_text'];
            }


            if ($data['notification_type_id'] == config('NotificationType.updateRating')) {
                $ratingNotification = $this->createRatingUpdatedNotification($data['user_id'],
                    $data['master_id'], $data['master_rating_id']);

                $notification['title'] = $ratingNotification['notification_title'];
                $notification['body'] = $ratingNotification['notification_text'];
            }
        }
        return $notification;
    }


    private function getArabicNotification($data)
    {
        $notification = [];
        $notification['title'] = "arabic";
        $notification['body'] = "arabic";

        if (isset($data['notification_type_id'])) {
            if ($data['notification_type_id'] == config('NotificationType.contactRequest')) {
                $requestNotification = $this->createRequestNotification($data['user_id'],
                    $data['master_id'], $data['contact_request_id']);
                $notification['title'] = $requestNotification['notification_title_ar'];
                $notification['body'] = $requestNotification['notification_text_ar'];
            }

            if ($data['notification_type_id'] == config('NotificationType.requestAccepted')) {
                $acceptedNotification = $this->createAcceptedNotification($data['user_id'], $data['master_id']);
                $notification['title'] = $acceptedNotification['notification_title_ar'];
                $notification['body'] = $acceptedNotification['notification_text_ar'];
            }

            if ($data['notification_type_id'] == config('NotificationType.requestRejected')) {
                $rejectedNotification = $this->createRejectedNotification($data['user_id'], $data['master_id']);
                $notification['title'] = $rejectedNotification['notification_title_ar'];
                $notification['body'] = $rejectedNotification['notification_text_ar'];
            }

            if ($data['notification_type_id'] == config('NotificationType.newRating')) {
                $ratingNotification = $this->createRatingCreatedNotification($data['user_id'],
                    $data['master_id'], $data['master_rating_id']);

                $notification['title'] = $ratingNotification['notification_title_ar'];
                $notification['body'] = $ratingNotification['notification_text_ar'];
            }


            if ($data['notification_type_id'] == config('NotificationType.updateRating')) {
                $ratingNotification = $this->createRatingUpdatedNotification($data['user_id'],
                    $data['master_id'], $data['master_rating_id']);

                $notification['title'] = $ratingNotification['notification_title_ar'];
                $notification['body'] = $ratingNotification['notification_text_ar'];
            }
        }


        return $notification;
    }
}