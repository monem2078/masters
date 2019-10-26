<?php
/**
 * Created by PhpStorm.
 * User: amr.salah
 * Date: 4/22/2019
 * Time: 3:58 PM
 */

namespace Helpers;


class PackageHelper
{

    public static function preparePackageData($data , $masterId)
    {
        $packageData=[];

        if(isset($data['id'])){
            $packageData['id'] = $data['id'];
        }

        if (isset($data['category_id'])) {
            $packageData["category_id"]= $data["category_id"];
        }

        if(isset($data["title"])){
            $packageData["title"]= $data["title"];
        }

        if(isset($data["description"])){
            $packageData["description"]= $data["description"];
        }

        if(isset($data["price"])){
            $packageData["price"]= $data["price"];
        }

        if(isset($data["currency_id"])){
            $packageData["currency_id"]= $data["currency_id"];
        }

        $packageData["master_id"]= $masterId;

        return $packageData;
    }
}