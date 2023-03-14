<?php

namespace App\Http\Controllers;

use App\Models\UserTest;
use Illuminate\Http\Request;

class UserTestController extends Controller
{
    public function index()
    {
        return UserTest::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);

        return UserTest::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return UserTest::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = UserTest::find($id);
        $user->update($request->all());
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return UserTest::destroy($id);
    }

    /**
     * Search for a name
     *
     * @param str $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return UserTest::where('name', 'like', '%' . $name . '%')->get();
    }
}
