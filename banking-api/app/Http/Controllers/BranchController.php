<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        return response()->json($branches);
    }

    public function show($id)
    {
        $branch = Branch::find($id);
        return response()->json($branch);
    }

    public function store(Request $request)
    {
        // validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $branch = new Branch;
        $branch->branch_name = $request->name;
        $branch->branch_code = $request->code;
        $branch->save();
        return response()->json($branch);
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::find($id);
        $branch->name = $request->name;
        $branch->code = $request->code;
        $branch->save();
        return response()->json($branch);
    }

    public function destroy($id)
    {
        $branch = Branch::find($id);
        $branch->delete();
        return response()->json('Branch deleted!');
    }
    
}
