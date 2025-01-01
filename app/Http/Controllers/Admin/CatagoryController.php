<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catagory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CatagoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'catagories' => Catagory::paginate(4),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /* handle the validation and the errors */
        $validatedData = Validator::make($request->only('name'), [
            'name' => 'required|string|min:5|max:50|unique:catagories,name'
        ])->validate();

        /* store in db */
        $catagory = Catagory::create($validatedData);
        
        /* response */
        return response()->json([
            'message' => 'Catagory created successfully.',
            'catagory' => $catagory,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Catagory $catagory)
    {
        return response()->json([
            'catagory' => $catagory,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Catagory $catagory)
    {
        /* handle the validation and the errors */
        $validatedData = Validator::make($request->only('name'), [
            'name' => 'required|string|min:5|max:50|unique:catagories,name'
        ])->validate();

        /* update in db */
        $catagory->update($validatedData);
        
        /* response */
        return response()->json([
            'message' => 'Catagory updated successfully.',
            'catagory' => $catagory,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Catagory $catagory)
    {
        $catagory->delete();
        return response()->noContent();
    }
}
