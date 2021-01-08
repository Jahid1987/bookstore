<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Session;
use App\Product;
use App\Category;
use App\Cart;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $products = Product::with('categories')->paginate(2);
        return view('admin.products.index', compact('products'));
    }

//Indexing trash products

    public function trash(){
        $products = Product::with('categories')->onlyTrashed()->paginate(2);
        // dd($products);
        return view('admin.products.index', compact('products'));
    }

//Cart:: Adding product to the cart

    public function addToCart(Product $product, Request $request){
        // dd($product);
        // dd(Session::get('cart'));
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $qty = $request->qty ? $request->qty : 1;
        
        $cart = new Cart($oldCart);
        $cart->addProduct($product, $qty);
        Session::put('cart', $cart);

        return back()->with('message', "Product $product->title has been successfully added to Cart");
    }

//Cart: view

    public function cart(){
        if(!Session::get('cart')){
            return view('products.cart');
        }
        $cart = Session::get('cart');
        // dd($cart->getContents());
        return view('products.cart', compact('cart'));
    }

//Cart: removeProduct from the cart

    public function removeProduct(Product $product){
        // dd($product);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        // dd($cart);
        $cart->removeProduct($product);
        Session::put('cart', $cart);
        return back()->with('message', "This $product->title has been successfully removed from the cart.");
    }
//Cart: update product in the cart

    public function updateProduct(Product $product, Request $request){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->updateProduct($product, $request->qty);
        Session::put('cart', $cart);
        return back()->with('message', "This $product->title has been updated successfully. ");
        
    }


/****************Cart Ends hera**********
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProduct $request)
    {
//Validation 
        // $validation = $request->validate([

        //     'title' => 'required|max:50',
        //     'slug' => 'required|unique:products',
        //     'description' => 'max:200',
        //     'price' => 'required|max:1024',
        //     'discount_price' => 'max:100',
        //     'status' => 'required',
        //     'thumbnail' => 'max:1024|mimes:jpg,png,jpeg,bmp,PNG',

        // ]);
 
 //Definign the Optons
        $extras = array('option' => $request->option, 'values' => $request->values, 'prices' => $request->prices);  

//Defining the image name
        if($request->hasFile('thumbnail')){
            $name = $request->title;
            $extension = $request->thumbnail->getClientOriginalExtension();
            $name = $name ."@". time() . "." . $extension;
            $fullpath = $request->thumbnail->storeAs('images/produtcs', $name, 'public');
            // dd($fullpath);   
        } 
        else{
            $fullpath = "images/products/demo.jpg";
        }

//Storing the data to the products table 
        $product = new Product;     
        $product->title = $request->title;
        $product->slug = $request->slug;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount_price = ($request->discount_price) ? $request->discount_price : 0;
        $product->status = $request->status;
        $product->thumbnail = $fullpath;
        $product->featured = ($request->featured) ? $request->featured : 0;
        $product->options = (isset($extras) ? json_encode($extras) : null);
        $p = $product->save();   
        //**** Note: the attach mathod must be called after saving the data otherwise product_id will be missing in pivot table. 
        $product->categories()->attach($request->category_id); 
        if($p){
            return back()->with('message', 'Product Added successfully!');
        }
        else{
            return back()->with('message', 'Oop! Something went wrong.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $products = Product::paginate(5);
        return view('products.all', compact('products'));
    }


//Showing single product here

    public function single(Product $product){
        // dd($product);
      return view('products.single', compact('product'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        // dd($product);
        $categories = Category::with('childrens')->get();
        return view('admin.products.create', compact('categories', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {

        // dd($request->featured);
        //Defining the image name

        if($request->hasFile('thumbnail')){
            $product->thumbnail ? Storage::disk('public')->delete($product->thumbnail) : null;
            $extension = $request->thumbnail->getClientOriginalExtension();
            $name = $request->title;
            $name = $name.'.'.$extension;
            $fullpath = $request->thumbnail->storeAs('images/produtcs', $name, 'public');
            // dd($fullpath);   
        } 
        else{
            $fullpath = $product->thumbnail;
            // dd($fullpath);
        }
        $product->title = $request->title;
        // $product->slug = $request->slug;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount_price = ($request->discount_price) ? $request->discount_price : 0;
        $product->status = $request->status;
        $product->thumbnail = $fullpath;
        $product->featured = ($request->featured) ? $request->featured : 0;
        $product->options = (isset($extras) ? json_encode($extras) : null);
        $product->categories()->detach();

        if($product->save()){
            $product->categories()->attach($request->category_id);
            return redirect(route('admin.product.index'))->with('message', 'Product updated successfully!');
        }
        else{
            return back()->with('message', 'Product could not updated properly!');
        }

    }



//Recover product

    public function recoverProduct($id){
        
        $product = Product::with('categories')->onlyTrashed()->findOrFail($id);
        if($product->restore()){
            return back()->with('message', 'Product restored successfully.');
        }
        else{
            return back()->with('message', 'Error Product restoring.');
        }
        // dd($product);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product) 
    {
        if($product->categories()->detach() && $product->forceDelete()){
            Storage::disk('public')->delete($product->thumbnail);
            return back()->with('message', 'Product successfully Deleted.');
        }
        else{
            return back()->with('message', "Error Deleting Product.");
        }
    }

//Remove trashe products
    public function destroytrash($id){
        $product = Product::with('categories')->onlyTrashed()->findOrFail($id);
        // dd($product->thumbnail);
        if($product->categories()->detach() && $product->forceDelete()){
            Storage::disk('public')->delete($product->thumbnail);
            return back()->with('message', 'Product successfully Deleted.');
        }
        else{
            return back()->with('message', "Error Deleting Product.");
        }
        // dd($product);
    }

    //Remove product to trash

    public function remove(Product $product){
        // dd($product->slug);
        if($product->delete()){
            return back()->with('message', 'product removed successfully.');
        }
        else{
            return back()->with('message', 'Error removing product.');
        }
    }

}
