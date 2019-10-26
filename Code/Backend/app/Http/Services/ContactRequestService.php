<?php

namespace Services;

use Repositories\ContactRequestRepository;
use Illuminate\Database\DatabaseManager;
use \Illuminate\Database\Eloquent\Model;


class ContactRequestService extends BaseService
{

    public function __construct(DatabaseManager $database, ContactRequestRepository $repository)
    {
        $this->setDatabase($database);
        $this->setRepository($repository);
    }

    public function prepareCreate(array $data)
    {
        return $this->repository->create($data);
    }

    public function prepareUpdate(Model $model, array $data)
    {
        return $this->repository->update($data, $model->id);
    }

    public function prepareDelete(int $id)
    {
        return $this->repository->delete($id);
    }


    public function getMasterPendingRequests($masterId){
        return $this->repository->getMasterPendingRequests($masterId);
    }

    public function checkRequestBelongToMaster($masterId , $requestId){
        $connection = $this->repository->getRequestByMasterIdAndRequestId($masterId , $requestId);
        return (sizeof($connection) > 0);
    }

    public function checkRequestExist($userId , $masterId){
        $result['valid'] = true;

        $request = $this->repository->getRequestByMasterIdAndUserId($masterId , $userId);
        if($request){
            if($request->request_status_type_id == config('requestStatusTypes.accepted')){
                $result['valid'] = false;
                $result['error'] = "request_accepted";
            }

            if($request->request_status_type_id == config('requestStatusTypes.new')){
                $result['valid'] = false;
                $result['error'] = "request_exist";
            }
        }
        return $result;
    }

    public function checkUserAuthorizedContactInfo($userId , $masterId){
        $requests = $this->repository->getAcceptedRequestByUserAndMaster($masterId , $userId);
        return (sizeof($requests) > 0);
    }

    public function getContactRequestDetails($requestId){
        return $this->repository->getContactRequestDetails($requestId);
    }
}