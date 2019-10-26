<?php

namespace Helpers;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordLink;

class EmailHelper
{

    public static function sendResetPasswordLinkMail($email, $name ,$token,  $resetCode)
    {
        Mail::to($email)->send(new ResetPasswordLink($name ,$token , $resetCode));
    }
}