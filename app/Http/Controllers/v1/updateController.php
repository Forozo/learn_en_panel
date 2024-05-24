<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class updateController extends Controller
{
    public function updateBook(Request $request)
    {
        $validation = Validator::make($request->all(),
            [
                'book_id' => 'required|exists:book,id',
                'category_id' => 'nullable|exists:category,id',
                'name' => 'nullable|max:60',
                'file' => 'nullable|max:50000',
                'cover' => 'nullable|max:8196',
            ]);
        if ($validation->fails()) {
            return "seyed";
        }

        $id = DB::table('book')
            ->where('id', $request->book_id)
            ->update([
                'category_id' => $request->category_id ?? DB::raw('category_id'),
                'name' => $request->name ?? DB::raw('name'),
            ]);

        $type = null;
        $name_file = null;
        $name_cover = null;
        if (isset($request->file)) {
            $name_file = $id . "." . $request->file->extension();
            $type = $request->file->extension();
            $path = DB::table('book')->where('id', $request->book_id)->first('file')->file;
            Storage::delete($path);
            Storage::putFileAs('/book_file', $request->file, $name_file);
        }
        if (isset($request->cover)) {
            $name_cover = $id . "." . $request->cover->extension();
            $path = DB::table('book')->where('id', $request->book_id)->first('cover')->cover;
            Storage::delete($path);
            Storage::putFileAs('/book_cover', $request->cover, $name_cover);
        }

        DB::table('book')->where('id', $id)->update([
            'file' => "/storage/book_file/$name_file" ?? DB::raw('file'),
            'cover' => "/storage/book_file/$name_cover" ?? DB::raw('cover'),
            'type' => $type ?? DB::raw('type'),
        ]);

        return "saeed";
    }

    public function updateCategory(Request $request)
    {
        $validation = Validator::make($request->all(),
            [
                'id' => 'required|exists:category,id',
                'name' => 'nullable|max:60',
                'cover' => 'nullable|max:8196',
            ]);
        if ($validation->fails()) {
            return "seyed errror";
        }

        if (isset($request->name))
            $id = DB::table('category')->where('id', $request->id)->update([
                'name' => $request->name,
            ]);

        if (isset($request->cover)) {
            $name_cover = $id . "." . $request->cover->extension();
            $path = DB::table('category')->where('id', $request->id)->first('cover')->cover;
            Storage::delete($path);
            Storage::putFileAs('/category_cover', $request->cover, $name_cover);
            DB::table('category')->where('id', $id)->update([
                'cover' => "/storage/category_cover/$name_cover",
            ]);
        }
        return "saeed";
    }
    public function activeBook(Request $request)
    {
        $validation = Validator::make($request->all(),
            [
                'book_id' => 'required|exists:book,id',
            ]);
        if ($validation->fails()) {
            return "seyed";
        }

        DB::table('book')
            ->where('id', $request->book_id)
            ->update([
                'active' => DB::raw('!active'),
            ]);

        return "saeed";
    }

    public function activeCategory(Request $request)
    {
        $validation = Validator::make($request->all(),
            [
                'id' => 'required|exists:category,id',
            ]);
        if ($validation->fails()) {
            return "seyed errror";
        }

            DB::table('category')
                ->where('id', $request->id)
                ->update([
                'active' => DB::raw('!active'),
            ]);

        return "saeed";
    }
}
