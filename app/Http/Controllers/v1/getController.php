<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class getController extends Controller
{

    public function getCategories()
    {
        $data['data'] = DB::table('category')
            ->where('active', 1)
            ->get();

        foreach ($data['data'] as $item){
            $item->cover = asset($item->cover);
        }
        return json_encode($data);
        return response(Json::encode($data));
    }

    public function getBooks($id)
    {
        $validation = Validator::make(['id' => $id],
            [
                'id' => 'required|exists:category,id',
            ]);
        if ($validation->fails()) {
            return "seyed errror";
        }

        $data = DB::table('book')
            ->join('category' , 'category.id' , '=', 'book.category_id')
            ->select([
                'book.id as book_id',
                'book.category_id',
                'book.name as book_name',
                'book.cover as book_cover',
                'book.file as book_file',
                'category.name as category_name',
            ])
            ->where('category_id', $id)
            ->where('book.active', 1)
            ->paginate();

        foreach ($data as $item){
            $item->cover = asset($item->book_cover);
            $item->file = asset($item->book_file);
        }
        return response(Json::encode($data));
    }
}
