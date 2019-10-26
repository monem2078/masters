<?php

namespace App\Http\Controllers;

use Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Services\FavoriteService;
use Services\MasterRatingService;
use Services\MasterService;
use Services\NotificationService;
use Services\PackageService;
use Validator;
use Helpers\SecurityHelper;


class MasterRatingController extends Controller
{

    private $masterRatingService, $securityHelper;
    private $masterService;
    private $notificationHelper;
    private $notificationService;

    public function __construct(MasterRatingService $masterRatingService,
                                SecurityHelper $securityHelper,
                                MasterService $masterService ,
                                NotificationHelper $notificationHelper ,
                                NotificationService $notificationService)
    {
        $this->masterRatingService = $masterRatingService;
        $this->securityHelper = $securityHelper;
        $this->masterService = $masterService;
        $this->notificationHelper = $notificationHelper;
        $this->notificationService = $notificationService;
    }

    public function index($userId)
    {
    }

    public function show($userId, $ratingId)
    {

    }

    public function store(Request $request)
    {
        $user = $this->securityHelper->getCurrentUser();
        $userId = $user->id;
        $masterId = $request->master_id;
        $rating = $request->rating;
        $review = $request->review;
        $ratingExist = $this->masterRatingService->checkIfRatingExist($userId, $masterId);
        if ($ratingExist) {
            return response()->json(['message' => 'rating already exist'], 400);
        } else {
            $data['user_id'] = $userId;
            $data['master_id'] = $masterId;
            $data['rating'] = $rating;
            $data['review'] = $review;
            $masterRating = $this->masterRatingService->create($data);

            $averageRating = $this->masterRatingService->calculateOverallRating($masterId);
            $this->masterService->update($masterId, ['overall_rating' => $averageRating]);

            $notification = $this->notificationHelper
                ->createRatingCreatedNotification($userId , $masterId ,$masterRating->id);
            $this->notificationService->create($notification);

            $notificationData = [
                'notification_type_id' => config('NotificationType.newRating'),
                'user_id' => $userId ,
                'master_id' => $masterId,
                'master_rating_id' => $masterRating->id
            ];

            $master = $this->masterService->getById($masterId);
            $this->notificationHelper->sendNotification(array($master->user_id) , $notificationData);

            return response()->json(['message' => 'success'], 200);
        }
    }

    public function update(Request $request, $ratingId)
    {

        $user = $this->securityHelper->getCurrentUser();
        $rating = $this->masterRatingService->getById($ratingId);
        if ($rating) {

            if ($rating->user_id != $user->id) {
                return response()->json(['message' => 'Not Allowed'], 401);
            }

            $masterId = $rating->master_id;
            $rate = $request->rating;
            $review = $request->review;

            $data['rating'] = $rate;
            $data['review'] = $review;
            $this->masterRatingService->update($ratingId, $data);

            $averageRating = $this->masterRatingService->calculateOverallRating($masterId);
            $this->masterService->update($masterId, ['overall_rating' => $averageRating]);


            $notification = $this->notificationHelper
                ->createRatingUpdatedNotification($user->id , $masterId ,$ratingId);
            $this->notificationService->create($notification);

            $notificationData = [
                'notification_type_id' => config('NotificationType.updateRating'),
                'user_id' => $user->id ,
                'master_id' => $masterId,
                'master_rating_id' => $ratingId
            ];

            $master = $this->masterService->getById($masterId);
            $this->notificationHelper->sendNotification(array($master->user_id) , $notificationData);

            return response()->json(['message' => 'success'], 200);
        } else {
            return response()->json(['message' => 'rating does not exist'], 400);
        }

    }

    public function destroy($ratingId)
    {
        $rating = $this->masterRatingService->getById($ratingId);
        $user = $this->securityHelper->getCurrentUser();
        if ($rating) {
            if ($rating->user_id != $user->id) {
                return response()->json(['message' => 'Not Allowed'], 401);
            }

            $masterId = $rating->master_id;
            $this->masterRatingService->delete($ratingId);
            $averageRating = $this->masterRatingService->calculateOverallRating($masterId);
            $this->masterService->update($masterId, ['overall_rating' => $averageRating]);
            return response()->json(['message' => 'success'], 200);

        } else {
            return response()->json(['message' => 'rating does not exist'], 400);
        }
    }

}
