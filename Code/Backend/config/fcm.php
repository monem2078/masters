<?php

return [
	'driver'      => env('FCM_PROTOCOL', 'http'),
	'log_enabled' => true,

	'http' => [
		'server_key'       => 'AAAA1r1rpZI:APA91bFXCNvpfAOA0YsZVRirwPv83wI0S4BnOjG3sMVXd5K7pvf9xaqjpWj324iH6HQ-TNse7jFLz3UUfkThxSjLFfzr1vsai5gT339vxuhXfdn_CKGoUp3n1CMN08CJXnFsCF2Bd4aB',//env('FCM_SERVER_KEY', 'Your FCM server key'),
		'sender_id'        => '922300949906',//env('FCM_SENDER_ID', 'Your sender id'),
		'server_send_url'  => 'https://fcm.googleapis.com/fcm/send',
		'server_group_url' => 'https://android.googleapis.com/gcm/notification',
		'timeout'          => 30.0, // in second
	]
];
