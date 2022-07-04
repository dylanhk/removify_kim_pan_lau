<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(12);    // display 12 records per page

        return view('user/index', compact(['users']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // display the create user page
        return view('user/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate values before process, return if validation failed

        try {
            $user = User::create(
                [
                'user_id' => $request->user_id,
                'name' => $request->name,
                'description' => $request->description,
                'age' => $request->age,
                ]
            );

            Session::flash('message', 'User created successfully!');
        
            // may remove below line to return back to User Index page
            return view('user/show', ['user' => $user]);
        } catch (\Exception $e) {
            // may log exception here
            abort(500);
        }

        return view('user/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = User::find($id);

        // abort if user not found
        if (!$user) {
            abort(404);
        }

        return view('user/show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        // abort if user not found
        if (!$user) {
            abort(404);
        }

        return view('user/edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate values before process, return if validation failed

        try {
            $user = User::find($id);

            $user->update(
                [
                'user_id'       => $request->user_id,
                'name'          => $request->name,
                'description'   => $request->description,
                'age'           => $request->age,
                ]
            );

            Session::flash('message', 'User data updated!');
        } catch (\Exceiption $e) {
            // may log exception here
            abort(500);
        }

        return view('user/index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);

            $user->delete();

            Session::flash('message', 'User removed!');
        } catch (\Expcetion $e) {
            // may log exception here
            abort(500);
        }
           
        return view('user/index');
    }
}
