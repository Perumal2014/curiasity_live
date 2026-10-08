<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LearningModel;

class LearningTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $learningTypes = LearningModel::query()
            ->when(request('learning_type'), function ($q) {
                $q->where('name', 'like', '%' . request('learning_type') . '%');
            })
            ->paginate(20);
        //print_R($learningTypes);exit;
        return view('setting::learning-type.index', compact('learningTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
