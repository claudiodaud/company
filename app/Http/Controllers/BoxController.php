<?php

namespace App\Http\Controllers;

use App\Models\boxes;
use App\Http\Requests\StoreboxesRequest;
use App\Http\Requests\UpdateboxesRequest;

class BoxController extends Controller
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
     * @param  \App\Http\Requests\StoreboxesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreboxesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\boxes  $boxes
     * @return \Illuminate\Http\Response
     */
    public function show(boxes $boxes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\boxes  $boxes
     * @return \Illuminate\Http\Response
     */
    public function edit(boxes $boxes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateboxesRequest  $request
     * @param  \App\Models\boxes  $boxes
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateboxesRequest $request, boxes $boxes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\boxes  $boxes
     * @return \Illuminate\Http\Response
     */
    public function destroy(boxes $boxes)
    {
        //
    }
}
