<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        if (!session()->has('user_id')) {
            return redirect('/employee');
        }

        return view('employee.dashboard');
    }

    public function mood()
    {
        if (!session()->has('user_id')) {
            return redirect('/employee');
        }

        return view('employee.mood');
    }
}