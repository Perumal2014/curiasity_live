<?php

namespace App\Http\Controllers\Skills;

use App\Http\Controllers\Controller;
use App\Models\CorporateSkill;
use Modules\RolePermission\Entities\Role;
use Illuminate\Http\Request;

class CorporateRoleSkillController extends Controller
{
    public function index()
    {
        $roles = Role::with('requiredSkills')->get();
        $skills = CorporateSkill::where('status', 'Active')->get();
        return view('skills.role_requirements', compact('roles', 'skills'));
    }

    public function assignSkill(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'skill_id' => 'required|exists:corporate_skills,id',
            'required_level' => 'required|string',
        ]);

        $role = Role::findOrFail($request->role_id);
        $role->requiredSkills()->syncWithoutDetaching([
            $request->skill_id => ['required_level' => $request->required_level],
        ]);

        return redirect()->back()->with('success', 'Skill assigned to role successfully.');
    }

    public function removeSkill($roleId, $skillId)
    {
        $role = Role::findOrFail($roleId);
        $role->requiredSkills()->detach($skillId);

        return redirect()->back()->with('success', 'Skill requirement removed.');
    }
}
