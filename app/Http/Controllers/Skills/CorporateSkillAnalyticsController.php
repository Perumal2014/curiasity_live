<?php

namespace App\Http\Controllers\Skills;

use App\Http\Controllers\Controller;
use App\Models\CorporateSkill;
use App\Models\CorporateUserSkill;
use App\Models\CorporateRoleSkillRequirement;

class CorporateSkillAnalyticsController extends Controller
{
    public function index()
    {
        $skills = CorporateSkill::withCount('users')->get();

        // Optional: Aggregate skill gaps by comparing user vs required levels
        $analytics = CorporateRoleSkillRequirement::select('skill_id', 'required_level')
            ->with('skill')
            ->get()
            ->groupBy('skill_id');

        return view('skills.analytics', compact('skills', 'analytics'));
    }
}
