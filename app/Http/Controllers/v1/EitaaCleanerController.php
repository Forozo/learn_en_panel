<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Utill\DefJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EitaaCleanerController extends Controller
{
    public function showMyAd() {
        return ["url" => "https://www.alirezasn80.ir/ad.png"];
    }

}
