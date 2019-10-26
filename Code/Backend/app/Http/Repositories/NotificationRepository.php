<?php

namespace Repositories;

/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
class NotificationRepository extends BaseRepository
{

    /**
     * Determine the model of the repository
     *
     */
    public function model()
    {
        return 'Models\Notification';
    }

    public function readUserNotifications($userId){
        return $this->model->where('user_id' , $userId)->update(['is_read' => 1]);
    }

    public function getNumberOfUnreadNotifications($userId){
        return $this->model
            ->selectRaw('count(id) as count')
            ->where('user_id' , $userId)
            ->where('is_read' , 0)
            ->first();

    }

    public function getUserNotifications($userId){
        return $this->model
            ->selectRaw('notification.* , notification_types.id as notification_type_id , notification.user_id ,
             (case when notification_types.id = '.config("NotificationType.contactRequest").' then userImage.image_url else notificationImage.image_url end) as image_url')
            ->join('notification_types' , 'notification_types.id' , 'notification.notification_type_id')
            ->leftjoin('users' , 'users.id' , 'notification.action_user_id')
            ->leftjoin('images as notificationImage' , 'notification_types.image_id' , 'notificationImage.id')
            ->leftJoin('images as userImage' , 'users.profile_image_id' , 'userImage.id')
            ->where('notification.user_id' , $userId)
            ->orderBy('notification.id' , 'desc')
            ->get();
    }

}