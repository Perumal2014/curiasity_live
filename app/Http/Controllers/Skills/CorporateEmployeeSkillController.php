<?php

namespace App\Http\Controllers\Skills;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CorporateSkill;
use Illuminate\Http\Request;
use App\Models\UserSkills;
use App\Models\Skills;

class CorporateEmployeeSkillController extends Controller
{
    public function index()
    {
        $employees = User::with('corporateSkills')->where('organization_id', auth()->user()->organization_id)->get();
        $skills = Skills::where('status', '1')->get();

        return view('skills.employee_profiles', compact('employees', 'skills'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'skill_id' => 'required',
            'level' => 'required',
        ]);

        $user = User::findOrFail($request->user_id);
        if ($user) {
            $skills = UserSkills::where('user_id', $request->user_id)->where('skill_id', $request->skill_id)->first();
            if (!$skills) {
                $skills = new UserSkills();
                $skills->user_id = $request->user_id;
                $skills->skill_id = $request->skill_id;
                $skills->level = $request->level;
                $skills->save();
            }
        }
       

        return redirect()->back()->with('success', 'Employee skill added successfully.');
    }

    public function remove($userId, $skillId)
    {
        $user = User::findOrFail($userId);
        $user->userSkills()->detach($skillId);

        return redirect()->back()->with('success', 'Skill removed from employee.');
    }
}
