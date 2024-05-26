<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Utill\DefJson;
use App\Http\Utill\DefValidator;
use App\Http\Utill\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function createCategory(Request $request) {

        $validator = Validator::make(
            $request->all(),
            [
                'key' => DefValidator::$key,
                'name' => 'required',
                'cover' => 'nullable|max:8196',
                'tag' => 'nullable'
            ]
        );

        if ($validator->fails()) return DefJson::error();

        $categoryId = DB::table('category')
            ->insertGetId(
                [
                    'name' => $request->name,
                    'tag' => $request->tag ?? null
                ]
            );


        if ($request->hasFile('cover')) {
            $coverName = $this->saveInStorage($request->cover, $categoryId, "category_cover");
            DB::table('category')->where('id', $categoryId)->update(['cover' => $coverName]);
        }

        return DefJson::success();
    }

    public function getCategories() {
        $categories['categories'] = DB::table('category')
            ->where('active', 1)
            ->get();

        foreach ($categories['categories'] as $item) {
            $item->cover = asset($item->cover);
        }

        return json_encode($categories);
    }

    public function updateCategory(Request $request) {
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

    public function activeCategory(Request $request) {
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

    public function clearCategories() {
        DB::table('category')->delete();
        return DefJson::success();
    }


}
