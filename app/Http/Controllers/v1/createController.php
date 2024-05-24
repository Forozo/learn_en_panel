<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class createController extends Controller
{
    public function addBook(Request $request)
    {
        $validation = Validator::make($request->all(),
            [
                'category_id' => 'required|exists:category,id',
                'name' => 'required|max:60',
                'file' => 'required|max:50000',
                'cover' => 'nullable|max:8196',
            ]);
        if ($validation->fails()) {
            return "seyed";
        }

        $id = DB::table('book')->insertGetId([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'type' => $request->file->extension(),
        ]);

        $name_file = $id . "." . $request->file->extension();
        Storage::putFileAs('/book_file', $request->file, $name_file);
        $name_cover = null;
        if (isset($request->cover)) {
            $name_cover = $id . "." . $request->cover->extension();
            Storage::putFileAs('/book_cover', $request->cover, $name_cover);
        }

        DB::table('book')->where('id', $id)->update([
            'file' => "/storage/book_file/$name_file",
            'cover' => "/storage/book_file/$name_cover",
        ]);

        return "saeed";
    }
    public function createCategory(Request $request)
    {
        $validation = Validator::make($request->all(),
            [
                'name' => 'required',
                'cover' => 'nullable|max:8196',
            ]);
        if ($validation->fails()) {
            return "seyed errror";
        }

        $id = DB::table('category')->insertGetId([
            'name' => $request->name,
        ]);

        if (isset($request->cover)) {
            $name_cover = $id . "." . $request->cover->extension();
            Storage::putFileAs('/category_cover', $request->cover, $name_cover);
            DB::table('category')->where('id', $id)->update([
                'cover' => "/storage/category_cover/$name_cover",
            ]);
        }


        return "saeed";
    }
}
