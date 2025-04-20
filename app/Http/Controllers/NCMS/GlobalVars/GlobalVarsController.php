<?php

namespace App\Http\Controllers\NCMS\GlobalVars;

use App\Http\Controllers\Controller;
use App\Http\Requests\NCMS\GlobalVars\StoreGlobalVarsRequest;
use App\Http\Requests\NCMS\GlobalVars\UpdateGlobalVarsRequest;
use App\Models\NCMS\GlobalVars;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GlobalVarsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $globalVars = GlobalVars::all();

        return Inertia::render('ncms/globalvars/GlobalVars', ['globalvars' => $globalVars]);
    }

    /**
     * Display a listing of the resource.
     */
    public function findById(int $id)
    {
        $globalVars = GlobalVars::find($id);

        if (!$globalVars) {
            return response()->json(['message' => 'Global variable not found.'], 404);
        }

        return response()->json($globalVars);
    }

    /**
     * Display a listing of the resource.
     */
    public function findByName(string $name)
    {
        $globalVars = GlobalVars::findByName($name);

        if (!$globalVars) {
            return response()->json(['message' => 'Global variable not found.'], 404);
        }

        return response()->json($globalVars);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('ncms/globalvars/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGlobalVarsRequest $request)
    {
        try {
            GlobalVars::create($request->validated());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating global variable: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Global variable created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(GlobalVars $globalVars)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $globalVarId)
    {
        $globalVar = GlobalVars::find($globalVarId);

        return Inertia::render('ncms/globalvars/Edit', ['globalvar' => $globalVar]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGlobalVarsRequest $request, GlobalVars $globalvar)
    {
        try {
            $globalvar->update($request->validated());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating global variable: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Global variable updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GlobalVars $globalvar)
    {
        $globalvar->delete();

        return response()->json([
            'message' => 'Global variable deleted successfully.'
        ]);
    }

    public function updateParameter(Request $request, GlobalVars $globalvar, string $parameter)
    {
        $globalvar->$parameter = $request->input($parameter);
        $globalvar->save();

        return response()->json([
            "message" => "Global variable updated successfully.",
        ]);
    }

    public function updateIsReadOnly(Request $request, GlobalVars $globalvar)
    {
        // $globalvar->is_read_only = $request->input('is_read_only');
        // $globalvar->save();

        // return response()->json([
        //     "message" => "Global variable updated successfully.",
        // ]);
        return $this->updateParameter($request, $globalvar, 'is_read_only');
    }

    public function updateIsProtected(Request $request, GlobalVars $globalvar)
    {
        // $globalvar->is_protected = $request->input('is_protected');
        // $globalvar->save();

        // return response()->json([
        //     "message" => "Global variable updated successfully.",
        // ]);
        return $this->updateParameter($request, $globalvar, 'is_protected');
    }

    public function updateName(Request $request, GlobalVars $globalvar)
    {
        // $globalvar->name = $request->input('name');
        // $globalvar->save();

        // return response()->json([
        //     "message" => "Global variable updated successfully.",
        // ]);
        return $this->updateParameter($request, $globalvar, 'name');
    }
}
