<?php
/**
 * Created by PhpStorm.
 * User: ibrahimradwan
 * Date: 2/27/17
 * Time: 2:14 PM
 */

namespace app\Services;

use App\Restaurant;
use DB;

class FoursquareApiFetcher
{
    public function fetchData()
    {
        $url = "https://api.foursquare.com/v2/venues/search?categoryId=4d4b7105d754a06374d81259&ll=30.0744852,31.2996525&intent=checkin&radius=500&client_id=4VGSQECE05IIHA1UJQAZPCINAMZYJODD4RNVMG4NSZRAH2ML&client_secret=JH5YXCQ1YGSJXJRCIGJETBNDAPFP5VE42GNM0WJFCLL0EMS5&v=20131118";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($curl);
        $this->syncWithDatabase($result);
    }

    public function syncWithDatabase($result)
    {
        $result = json_decode($result, true);
        foreach ($result["response"]["venues"] as $venus) {

            $rest = new Restaurant();
            $rest->foursquare_id = $venus["id"];
            $rest->name = $venus["name"];
            if (array_key_exists("formattedAddress", $venus["location"])) {
                $rest->location = implode(", ", $venus["location"]["formattedAddress"]);
            } else if (array_key_exists("address", $venus["location"])) {
                $rest->location = $venus["location"]["address"];
            } else {
                $rest->location = "Unkown";
            }
            if (array_key_exists("pluralName", $venus["categories"]))
                $rest->desc = $venus["categories"]["pluralName"];
            else $rest->desc = $rest->name;

            $rest->link = "foursquare.com";
            if (array_key_exists("phone", $venus["contact"])) {
                $rest->phone_number = $venus["contact"]["phone"];
            } else {
                $rest->phone_number = "Unkown";
            }
            $matchThese = array('foursquare_id' => $rest->foursquare_id);
            Restaurant::updateOrCreate($matchThese, $rest->toArray());
        }
    }
}