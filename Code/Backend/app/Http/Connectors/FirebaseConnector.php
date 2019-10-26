<?php

namespace Connectors;

use Illuminate\Support\Facades\Config;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\OptionsPriorities;
use FCM;

/**
 * Low-level client for Firebase
 *
 * @author eman.mohamed
 */
class FirebaseConnector
{

    public function __construct()
    {

    }

    public function sendNotification($tokens, $title, $body, $data)
    {
        $option = $this->buildOptions();
        $dataPayload = $this->buildDataPayload($data);
        $notification = $this->buildNotificationPayload($title, $body);

        if(sizeof($tokens) > 0){
            $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $dataPayload);
            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
            $downstreamResponse->numberModification();
            $downstreamResponse->tokensToDelete();
            $downstreamResponse->tokensToModify();
            $downstreamResponse->tokensToRetry();
            return $downstreamResponse;
        }
        return null;
    }

    public function buildNotificationPayload($title, $body)
    {

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound('default')
            ->setIcon("notification_logo")
            ->setTag(null)
            ->setClickAction(null);

        $notification = $notificationBuilder->build();

        return $notification;
    }

    public function buildOptions()
    {
        $optionBuiler = new OptionsBuilder();
        $optionBuiler->setTimeToLive(60 * 20);
        $optionBuiler->setPriority(OptionsPriorities::high);
        $option = $optionBuiler->build();
        return $option;
    }

    public function buildDataPayload($data)
    {
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($data);
        $data = $dataBuilder->build();
        return $data;
    }
}