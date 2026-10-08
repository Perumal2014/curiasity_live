<?php

namespace App\Http\Controllers\Skills;

use App\Http\Controllers\Controller;
use App\Models\CorporateSkill;

class CorporateTrainingController extends Controller
{
    public function index()
    {
        $skills = CorporateSkill::where('status', 'Active')->get();

        // This can later pull from a 'trainings' table
        $trainings = [
            ['title' => 'Advanced SQL', 'target' => 'Intermediate learners'],
            ['title' => 'Leadership Mastery', 'target' => 'Beginner → Expert transition'],
        ];

        return view('skills.training', compact('skills', 'trainings'));
    }
}
