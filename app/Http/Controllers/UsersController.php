<?php

namespace App\Http\Controllers;

use App\Models\Spicialize;
use App\Http\Requests\StoreSpicializeRequest;
use App\Http\Requests\UpdateSpicializeRequest;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSpicializeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSpicializeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Spicialize  $spicialize
     * @return \Illuminate\Http\Response
     */
    public function show(Spicialize $spicialize)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Spicialize  $spicialize
     * @return \Illuminate\Http\Response
     */
    public function edit(Spicialize $spicialize)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSpicializeRequest  $request
     * @param  \App\Models\Spicialize  $spicialize
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSpicializeRequest $request, Spicialize $spicialize)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Spicialize  $spicialize
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spicialize $spicialize)
    {
        //
    }
}
