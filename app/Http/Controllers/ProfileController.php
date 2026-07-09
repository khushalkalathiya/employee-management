<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function dashboard(Request $request): View
    {
        return view('dashboard.index');
    }

    public function editBasicInformation()
    {
        $user = Auth::user();
        $roles = Role::pluck('display_name','name');
        $section = 'basic-information';

        return view('profile.edit', compact('user', 'roles', 'section'));
    }

    public function editPersonalDetails()
    {
        $user = Auth::user();
        $user->ensureEmployeeExists();
        $user->load('employee');
        $departments = \App\Models\Department::pluck('name', 'id');
        $designations = \App\Models\Designation::pluck('name', 'id');
        $managers = User::where('id', '!=', $user->id)->get();
        $section = 'personal-details';

        return view('profile.edit', compact('user', 'departments', 'designations', 'managers', 'section'));
    }

    public function editFamilyInformation()
    {
        $user = Auth::user();
        $user->ensureEmployeeExists();
        $user->load('employee.familyInformation');
        $section = 'family-information';
        return view('profile.edit', compact('user', 'section'));
    }
    
    public function editBankAccount()
    {
        $user = Auth::user();
        $user->ensureEmployeeExists();
        $user->load('employee.bankAccount');
        $bankAccount = $user->employee ? $user->employee->bankAccount : null;
        $section = 'bank-account';
        return view('profile.edit', compact('user', 'bankAccount', 'section'));
    }
    
    public function editDocuments()
    {
        $user = Auth::user();
        $user->ensureEmployeeExists();
        $user->load('employee');
        $section = 'documents';
        return view('profile.edit', compact('user', 'section'));
    }
    
    public function editEducation()
    {
        $user = Auth::user();
        $user->ensureEmployeeExists();
        $user->load('employee');
        $section = 'education';
        return view('profile.edit', compact('user', 'section'));
    }
    
    public function editExperience()
    {
        $user = Auth::user();
        $user->ensureEmployeeExists();
        $user->load('employee');
        $experiences = $user->employee ? $user->employee->experiences : collect();
        $section = 'experience';
        return view('profile.edit', compact('user', 'experiences', 'section'));
    }
}
