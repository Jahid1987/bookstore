<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with(['childrens'])->simplePaginate(5); //eager loadhing
        return view('admin.categories.index', compact('categories'));
    }


    //Showing restored data

    public function restoredTrashed(Category $category){
        $trashes = $category->onlyTrashed()->simplePaginate(5);
        // dd($trashes);
        return view('admin.categories.trashed', compact('trashes', $category));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.create', compact('categories')); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:5',
            'slug' => 'required|min:5|unique:categories',
        ]);  
        $categories = new Category();
        $categories->title = $request->title;
        $categories->slug = $request->slug;
        $categories->description = $request->description;
        $categories->save();
        // dd($request->parent_id);
        // $categories->childrens()->attach($request->parent_id);  
        $categories->parents()->attach($request->parent_id,['created_at'=>now(), 'updated_at'=>now()]);
        return back()->with('message', 'Category Added Properly!');

    }


    //Restoring trash data
    public function restore($id){

        $restored = Category::where('id', $id)->restore();
        if($restored){
            return back()->with('message', 'Data restored successfully!');
        }    
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        // dd($category->description);
    
        $categories = Category::where('id', '!=', $category->id)->get();
        // dd($categories->childrens);
        return view('admin.categories.edit', compact('category', 'categories'));



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // dd($category);
        $category->title = $request->title;
        $category->description = $request->description;
        $category->parents()->detach();
        $category->parents()->attach($request->parent_id);
        $save = $category->save();

        return redirect()->route('admin.category.index')->with('message', 'Updated properly!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)      // ****Using for softDelete
    {
        // dd($category->parents());
        $delete = $category->delete();
        // $category->parents()->detach();
        if($delete){
            return redirect()->route('admin.category.index')->with('message', 'Category Deleted successfully!');
        }
        else{
            return back()->with('message', 'Could not be deleted!');
        }
    }

    //Force Deletign data
    public function forceDelete(Category $category, $id){
        
        //***** First check whether the delete command comming form index page or not

        $ids = array_pluck(Category::all(), 'id');
        $test = in_array($id, $ids);

        if($test){
            $category = Category::where('id', $id)->first();
            $delete = $category->forceDelete();
            $delete = $category->parents()->detach();
            return back()->with('message', 'You have deleted your data permanently!');
        }
        else{
            $category = Category::onlyTrashed()->findOrFail($id);
            $delete = $category->forceDelete();
            $delete = $category->parents()->detach();
            return back()->with('message', 'You have deleted your data permanently!');
        }

    }
}
