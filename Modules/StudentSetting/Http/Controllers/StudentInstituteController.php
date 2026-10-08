<?php

namespace Modules\StudentSetting\Http\Controllers;


use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Modules\StudentSetting\Entities\Institute;
use Illuminate\Support\Facades\Auth;

class StudentInstituteController extends Controller
{

    public function index()
    {
        try {
            $institutes = Institute::where('organization_id', Auth::user()->organization_id)->get();
            return view('studentsetting::institutes', compact('institutes'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
        ];

        $this->validate($request, $rules, validationMessage($rules));


        try {
            Institute::create([
                'name' => $request->name,
                'address' => $request->address,
                'organization_id' => Auth::user()->organization_id,
            ]);


            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function edit($id)
    {
        $edit = Institute::find($id);
        $institutes = Institute::get();
        return view('studentsetting::institutes', compact('institutes','edit'));
    }

    public function update(Request $request,$id)
    {
        $rules = [
            'name' => 'required|max:255',
        ];

        $this->validate($request, $rules, validationMessage($rules));

        try {
            Institute::where('id',$id)->update([
                'name' => $request->name,
                'address' => $request->address,
                'organization_id' => Auth::user()->organization_id,
            ]);


            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->route('student.institute.index');
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function delete($id)
    {
        try {
        Institute::find($id)->delete();
        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->route('student.institute.index');
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }

    }
}
