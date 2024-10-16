<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{

    // public function __construct(){
    //     $this->middleware = [];
    // }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request = request();
        // $query = Category::query();  // returns query builder for category

        $categories = Category::with('parent')
        /*leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            ->select([
                'categories.*',
                'parents.name as parent_name',
            ])*/
            // ->select('categories.*')
            // ->selectRaw('(select count(*) from `products` where `categories`.`id` = `products`.`category_id`) as products_count')
            ->withCount('products')
            ->filter($request->all())
            ->orderBy('categories.id')
            ->paginate(5);
        return view('dashboard.categories.index', compact('categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Category::all();
        $category = new Category();
        // dd($category);
        return view('dashboard.categories.create', compact('category', 'parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate(Category::rules());
        $request->merge([
            'slug' => Str::slug($request->name)
        ]);
        $data = $request->except('image');
        $data['image'] = Category::uploadImage($request);



        $category = Category::create($data);
        return Redirect::route('dashboard.categories.index')->with('success', 'category add');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('dashboard.categories.show',compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $category = Category::findOrFail($id);
        } catch (Exception $e) {
            return Redirect::route('dashboard.categories.index')
                ->with('info', 'record not found');
        }

        $parents = Category::where('id', '<>', $id)
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id')
                    ->orwhere('parent_id', '<>', $id);
            })->get();

        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $id)
    {
        // $request->validate(Category::rules($id));
        $category = Category::findOrFail($id);
        $old_image = $category->image;

        $data = $request->except('image');

        $new_image = Category::uploadImage($request);
        if ($new_image) {
            $data['image'] = $new_image;
        }
        $category->update($data);


        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        }


        return Redirect::route('dashboard.categories.index')
            ->with('success', 'category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        

        $category = Category::findOrFail($id);
        $category->delete();
        return Redirect::route('dashboard.categories.index')
            ->with('success', 'category deleted successfully');
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->paginate();
        return view('dashboard.categories.trash', compact('categories'));
    }
    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return Redirect::route('dashboard.categories.trash')
            ->with('success', 'category restored successfully');
    }
    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $old_image = $category->image;
        $category->forceDelete();
        if ($old_image) {
            Storage::disk('public')->delete($old_image);
        }
        return Redirect::route('dashboard.categories.trash')
            ->with('success', 'category deleted forever successfully');
    }
}
