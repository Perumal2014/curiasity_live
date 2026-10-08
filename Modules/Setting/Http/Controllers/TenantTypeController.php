<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Setting\Entities\TenantType;
use Brian2694\Toastr\Facades\Toastr;


class TenantTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenantTypes = TenantType::query()
            ->when(request('tenant_type'), function ($q) {
                $q->where('name', 'like', '%' . request('tenant_type') . '%');
            })
            ->paginate(20);

        return view('setting::tenant-type.index', compact('tenantTypes'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('setting::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }

        $request->validate([
            'tenant_type' => 'required',
        ]);

        try {
            DB::table('tenant_types')->insert([
                'name' => $request->tenant_type,
            ]);
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (\Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('setting::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        if (demoCheck()) {
            return redirect()->back();
        }

        $request->validate([
            'tenant_type' => 'required',
        ]);

        try {
            DB::table('tenant_types')
                ->where('id', $id)
                ->update([
                    'name' => $request->tenant_type,
                ]);
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (\Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function edit_modal(Request $request)
    {
        try {
            $tenantType = TenantType::where('id', $request->id)->first();

            return view('setting::tenant-type.edit_modal', [
                "tenantType" => $tenantType
            ]);
        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return false;
        }
    }
}
