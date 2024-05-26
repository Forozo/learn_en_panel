<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Utill\DefJson;
use App\Http\Utill\DefValidator;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{

    public function addBook(Request $request) {
        $validation = Validator::make($request->all(),
            [
                'key' => DefValidator::$key,
                'category_id' => 'required|exists:category,id',
                'name' => 'required|max:60',
                'file' => 'required|max:50000',
                'cover' => 'nullable|max:8196',
            ]);

        if ($validation->fails()) return DefJson::error();


        $bookId = DB::table('book')->insertGetId([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'type' => $request->file->getClientOriginalExtension(),
        ]);

        $fileName = $this->saveInStorage(
            file: $request->file,
            id: $bookId,
            folder: "book_file"
        );

        $coverName = null;
        if ($request->hasFile('cover')) {
            $coverName = $this->saveInStorage(
                file: $request->cover,
                id: $bookId,
                folder: "book_cover"
            );
        }

        DB::table('book')->where('id', $bookId)->update(
            [
                'file' => $fileName,
                'cover' => $coverName,
            ]
        );

        return DefJson::success();
    }

    public function getBooks(Request $request) {

        $validation = Validator::make(['id' => $request->id],
            ['id' => 'required|exists:category,id']);

        if ($validation->fails()) return DefJson::error();

        $data = DB::table('book')
            ->join('category', 'category.id', '=', 'book.category_id')
            ->select([
                'book.id as book_id',
                'book.category_id',
                'book.name as name',
                'book.cover as cover',
                'book.file as file',
                'book.type as type'
            ])
            ->where('category_id', $request->id)
            ->where('book.active', 1)
            ->paginate();

        foreach ($data as $item) {
            $item->cover = asset($item->book_cover);
            $item->file = asset($item->book_file);
        }
        return Json::encode($data);
    }

    public function updateBook(Request $request) {

        $validation = Validator::make($request->all(),
            [
                'book_id' => 'required|exists:book,id',
                'category_id' => 'nullable|exists:category,id',
                'name' => 'nullable|max:60',
                'file' => 'nullable|max:50000',
                'cover' => 'nullable|max:8196',
            ]);

        if ($validation->fails()) return DefJson::error();


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
            $name_file = $id . "." . $request->file->getClientOriginalExtension();
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


    public function activeBook(Request $request) {
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

    public function clearBooks() {
        DB::table('book')->delete();
        return DefJson::success();
    }

}
