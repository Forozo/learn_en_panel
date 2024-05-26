<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{

    public  function saveInStorage($file, $id, $folder) {
        $name = "/storage/$folder/$id." . $file->getClientOriginalExtension();
        Storage::putFileAs("/$folder", $file, $name);
        return $name;
    }
}
