<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BannerModel;

class BannerController extends Controller
{

    public function createBanner(Request $request){
        $query = "INSERT INTO banner(idBanner,URL) VALUES(null,\"$request->link\");";
        $db = mysqli_connect('localhost', 'root', '', 'laraveldb');
        $result = $db->query($query);
        return $result;
    }

    public function getBanner(){
        $query = "SELECT * from banner";
        $db = mysqli_connect('localhost', 'root', '', 'laraveldb');
        $result = mysqli_fetch_all($db->query($query), MYSQLI_ASSOC);
        return $result;
    }
}
