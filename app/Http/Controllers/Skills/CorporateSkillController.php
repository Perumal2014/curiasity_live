<?php

namespace App\Http\Controllers\Skills;

use App\Http\Controllers\Controller;
use App\Models\CorporateSkill;
use Illuminate\Http\Request;
use App\Models\Skills;
use Illuminate\Support\Facades\Auth;


class CorporateSkillController extends Controller
{
    public function index()
    {
        $skills = Skills::where('org_id', Auth::user()->organization_id)->latest()->get();

        return view('skills.index', compact('skills'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        $skillName = trim($request->name);

        $exists = Skills::where('org_id', Auth::user()->organization_id)
            ->where(function ($query) use ($skillName) {
                $query->where('name', 'like', "%{$skillName}%")
                    ->orWhereRaw('? LIKE CONCAT("%", name, "%")', [$skillName]);
            })
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'A similar skill already exists.'
            ]);
        }

        Skills::create([
            'name' => $skillName,
            'type' => 0,
            'status' => $request->status,
            'org_id' => Auth::user()->organization_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Skill added successfully.'
        ]);
    }

    public function edit($id)
    {
        $skill = Skills::findOrFail($id);
        return response()->json($skill);
    }

    public function update(Request $request)
    {
        // print_r($request->all()); exit;
        $skill = Skills::findOrFail($request->id);

        if ($skill->name !== $request->name) {
            $request->validate([
                'name' => 'required|string|max:255',
                'status' => 'required|in:0,1',
            ]);

            $skillName = trim($request->name);

            $exists = Skills::where('id', '!=', $skill->id)
                ->where(function ($query) use ($skillName) {
                    $query->where('name', 'LIKE', "%{$skillName}%")
                        ->orWhereRaw('? LIKE CONCAT("%", name, "%")', [$skillName]);
                })
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'A similar skill already exists.'
                ]);
            }
        }
        $skill->update([
            'name' => $request->name,
            'category' => 0,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Skill updated successfully'
        ]);
    }

    public function destroy($id)
    {
        CorporateSkill::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Skill deleted successfully.');
    }
}
