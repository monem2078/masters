<?php

namespace Repositories;

/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
class ContactRequestRepository extends BaseRepository
{

    /**
     * Determine the model of the repository
     *
     */
    public function model()
    {
        return 'Models\ContactRequest';
    }

    public function getRequestByMasterIdAndRequestId($masterId , $requestId){
        return $this->model->where('master_id' , $masterId)->where('id' , $requestId)->get();
    }

    public function getRequestByMasterIdAndUserId($masterId , $userId){
        return $this->model->where('master_id' , $masterId)
            ->where('user_id' , $userId)
            ->orderBy('request_status_type_id' , 'asc')
            ->first();
    }

    public function getAcceptedRequestByUserAndMaster($masterId , $userId){
        return $this->model->where('request_status_type_id' , config('requestStatusTypes.accepted'))
            ->where('user_id' , $userId)
            ->where('master_id' , $masterId)
            ->get();
    }


    public function getMasterPendingRequests($masterId){
        return $this->model
            ->selectRaw('users.id as user_id , users.name,images.image_url , contact_requests.id , 
            contact_requests.created_at')
            ->join('users' , 'user_id' , 'users.id')
            ->leftJoin('images' , 'users.profile_image_id' , 'images.id')
            ->where('master_id' , $masterId)
            ->where('request_status_type_id' , config('requestStatusTypes.new'))
            ->orderBy('contact_requests.id' , 'desc')
            ->get();
    }

    public function getContactRequestDetails($requestId){
        return $this->model
            ->selectRaw('countries.* , cities.* , contact_requests.* , users.name , images.image_url')
            ->join('users' , 'users.id' , 'contact_requests.user_id')
            ->leftjoin('countries' , 'users.country_id' , 'countries.id')
            ->leftjoin('cities' , 'users.city_id' , 'cities.id')
            ->leftjoin('images' , 'images.id' , 'users.profile_image_id')
            ->where('contact_requests.id' , $requestId)
            ->first();

    }
}