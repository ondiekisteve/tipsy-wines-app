<?php

namespace App\Http\Controllers\admin;

use App\Category;
use App\Conf\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function showCategories(Request $request)

    {
        $categories = Category::all();
        return view('admin.categories',['categories'=>$categories]);
    }
    public function showaddCategoryForm(Request $request)
    {
        return view('admin.add-category-form');
    }
    public function addCategory(Request $request)
    {
        try{
            $categoryName = $request->categoryName;
            Category::create([
                "name"=> $categoryName
            ]);
            $resp = array(
                "STATUS_CODE" => Config::SUCCESS_CODE,
                "STATUS_MESSAGE" => "Category Added successfully"
            );
        }catch (\Exception $e)
        {
            $resp = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => "Oops! error occurred during processing, please try again later "
            );
        }
        return json_encode($resp);
    }
}
