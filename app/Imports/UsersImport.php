<?php
namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UsersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            // Skip header
            if ($index == 0) continue;

            $employeeId = $row[1];

            $user = User::where([
                    ['employee_id', '=', $employeeId],
                    ['organization_id', '=', Auth::user()->organization_id],
                    ['status', '=', ACTIVE],
                ])->first();

            if ($user) {
                $user->update(['status' => 2]); // WAITING
            }

            // if not found → skip silently ✅
        }
    }
}