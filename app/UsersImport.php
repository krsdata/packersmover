<?php

namespace App;

use App\Employee;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel,WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        if(!empty($row['first_name'])){
          $user = Auth::user();
          $user_id = $user->id;
          $company_id = $user->company_id;
          $status="1";
          $employee = new Employee();
          $employee->company_id = $company_id;
          $employee->f_name = $row['first_name'];
          $employee->l_name = $row['last_name'];
          $employee->email = $row['email'];
          $employee->designation = $row['designation'];
          $employee->department = $row['department'];
          $employee->office_no = $row['office_no'];
          $employee->mobile_no = $row['mobile_no'];
          $employee->created_by = $user_id;
          $employee->status = $status;

          if ($employee->save()) {
            //print_r($employee); die();
            return $employee;
          }
        }

    }

    public function headingRow(): int
     {
         return 1;
     }
}
