<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\Http\Requests\StoreUserProfile;
use App\Profile;
use App\Role;
use App\State;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users = User::with('role', 'profile')->paginate(5);

        return view('admin.users.index', compact('users'));
    }
/**
 * Display Trashed listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */
    public function trash() {
        // $profile = Profile::onlyTrashed()->get();
        // dd($profile);
        $users = User::with('profile')->onlyTrashed()->paginate();
        // $users = $users->profile()->onlyTrashed()->paginate(3);
        // dd($users);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $roles = Role::all();
        $countries = Country::all();
        return view('admin.users.create', compact('roles', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserProfile $request) {
        // dd($request->all());
//Defining the image name
        if($request->hasFile('thumbnail')){
            $name = $request->name;
            $extension = $request->thumbnail->getClientOriginalExtension();
            $name = $name ."@". time() . "." . $extension;
            $fullpath = $request->thumbnail->storeAs('images/profile', $name, 'public');
            // dd($fullpath);   
        } 
        else{
            $fullpath = "images/profile/demo.jpg";
        }


        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->status,
        ]);
        if ($user) {
            $profile = Profile::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'thumbnail' => $fullpath,
                'address' => $request->address,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'phone' => $request->phone,
                'slug' => $request->slug,
            ]);
        }
        if ($user && $profile) {
            return redirect(route('admin.profile.index'))->with('message', 'User Created Successfully');
        } else {
            return back()->with('message', 'Error Inserting new User');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile) {
        // dd($profile->user_id);
        // $user = User::find($profile)->first();
        $user = User::where('id', $profile->user_id)->with('profile')->first();
        // dd($user->profile->country->name);
        $countries = Country::all();
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile) {
        // dd($request->all());

        if($request->hasFile('thumbnail')){
            $profile->thumbnail ? Storage::disk('public')->delete($profile->thumbnail) : null;
            $extension = $request->thumbnail->getClientOriginalExtension();
            $name = $request->name;
            $name = $name.'.'.$extension;
            $fullpath = $request->thumbnail->storeAs('images/profile', $name, 'public');
            // dd($fullpath);   
        } 
        else{
            $fullpath = $profile->thumbnail;
            // dd($fullpath);
        }

        $profile->name = $request->name;
        $profile->thumbnail = $fullpath;
        $profile->address = $request->address;
        $profile->country_id = $request->country_id;
        $profile->state_id = $request->state_id;
        $profile->city_id = $request->city_id;
        $profile->phone = $request->phone;
        $profile->user->email = $request->email;
        $profile->user->status = $request->status;
        // dd($profile->user);
        if ($profile->user->save() && $profile->save()) {
            return redirect(route('admin.profile.index'))->with('message', 'User Updated Successfully');
        } else {
            return back()->with('message', 'Error Updating new User');
        }


    }


    public function recoverProfile($id) {
        $user = User::with('profile')->onlyTrashed()->findOrFail($id);
        $profile = Profile::where('user_id', $id)->onlyTrashed()->first();
        // dd($profile);
        if ($user->restore() && $profile->restore()) {
            return back()->with('message', 'User Successfully Restored!');
        } else {
            return back()->with('message', 'Error Restoring User');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile) {
        $deleteProfile = $profile->forceDelete();
        $deleteUser = $profile->user->forceDelete();
        // dd($profile->user);
        if($deleteProfile && $deleteUser){
            Storage::disk('public')->delete($profile->thumbnail);
            return back()->with('message', 'User Profile Successfully deleted!');
        }
        else{
            return back()->with('message', 'Error Deleting User Profile!');
        }
    }

    //Remove trashe products
    public function destroytrash($id){
        $user = User::onlyTrashed()->findOrFail($id);
        $profile = Profile::where('user_id', $id)->onlyTrashed()->first();
        $deleteProfile = $profile->forceDelete();
        $deleteUser = $user->forceDelete();
        // dd($profile->user);
        if($deleteProfile && $deleteUser){
            Storage::disk('public')->delete($profile->thumbnail);
            return back()->with('message', 'User Profile Successfully deleted!');
        }
        else{
            return back()->with('message', 'Error Deleting User Profile!');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function remove(Profile $profile) {
        $userDelete = $profile->user->delete();
        $profileDelete = $profile->delete();
        // dd($user);
        if ($userDelete && $profileDelete) {
            return back()->with('message', 'User and profile Successfully Trashed!');
        } else {
            return back()->with('message', 'Error Trashing User and Profile');
        }
    }

    public function getStates(Request $request, $id) {
        if ($request->ajax()) {
            return State::where('country_id', $id)->get();
        } else {
            return 0;
        }
    }
    public function getCities(Request $request, $id) {
        if ($request->ajax()) {
            return City::where('state_id', $id)->get();
        } else {
            return 0;
        }
    }
}
