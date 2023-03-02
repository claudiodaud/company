<?php

namespace App\Http\Controllers;

use App\Models\Net;
use App\Http\Requests\StoreNetRequest;
use App\Http\Requests\UpdateNetRequest;

class NetController extends Controller
{
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
     * @param  \App\Http\Requests\StoreNetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNetRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Net  $net
     * @return \Illuminate\Http\Response
     */
    public function show(Net $net)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Net  $net
     * @return \Illuminate\Http\Response
     */
    public function edit(Net $net)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNetRequest  $request
     * @param  \App\Models\Net  $net
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNetRequest $request, Net $net)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Net  $net
     * @return \Illuminate\Http\Response
     */
    public function destroy(Net $net)
    {
        //
    }
}
