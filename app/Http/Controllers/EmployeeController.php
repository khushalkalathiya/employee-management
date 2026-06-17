<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Actions\Employee\CreateEmployeeAction;
use App\Actions\Employee\UpdateEmployeeAction;
use App\Actions\Employee\DeleteEmployeeAction;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateBasicInformationRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employees.index');
    }

    public function create()
    {
        $roles = Role::pluck('display_name','name');
        return view('employees.create', compact('roles'));
    }

    public function store(StoreEmployeeRequest $request, CreateEmployeeAction $action ) {
        try {
            $action->handle($request->validated());
            return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(User $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function destroy(User $employee, DeleteEmployeeAction $action): JsonResponse {
        try {
            $action->handle($employee);
            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function editBasicInformation()
    {
        $user = auth()->user();
        $roles = Role::pluck('display_name','name');
        $section = 'basic-information';

        return view('profile.edit', compact('user', 'roles', 'section'));
    }

    public function updateBasicInformation(UpdateBasicInformationRequest $request, User $user, \App\Actions\Employee\UpdateBasicInformationAction $action) {
        try {
            $action->handle($user, $request->validated());
            return redirect()->route('employees.basic-information.edit', $user->id)->with('success', 'Basic information updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editPersonalDetails(User $user)
    {
        $user->load('employee');
        $departments = \App\Models\Department::pluck('name', 'id');
        $designations = \App\Models\Designation::pluck('name', 'id');
        $managers = User::where('id', '!=', $user->id)->get();
        $section = 'personal-details';

        return view('employees.edit', compact('user', 'departments', 'designations', 'managers', 'section'));
    }

    public function updatePersonalDetails(\App\Http\Requests\Employee\UpdatePersonalDetailsRequest $request, User $user, \App\Actions\Employee\UpdatePersonalDetailsAction $action)
    {
        try {
            $action->handle($user, $request->validated());
            return redirect()->route('employees.personal-details.edit', $user->id)->with('success', 'Personal details updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editFamilyInformation(User $user)
    {
        $user->load('employee.familyInformation');
        $section = 'family-information';
        return view('employees.edit', compact('user', 'section'));
    }
  
    public function updateFamilyInformation(\App\Http\Requests\Employee\UpdateFamilyInformationRequest $request, User $user, \App\Actions\Employee\UpdateFamilyInformationAction $action)
    {
        try {
            $action->handle($user, $request->validated());
            return redirect()->route('employees.family-information.edit', $user->id)->with('success', 'Family information updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editBankAccount(User $user)
    {
        $user->load('employee.bankAccount');
        $bankAccount = $user->employee ? $user->employee->bankAccount : null;
        $section = 'bank-account';
        return view('employees.edit', compact('user', 'bankAccount', 'section'));
    }

    public function updateBankAccount(\App\Http\Requests\Employee\UpdateBankAccountRequest $request, User $user, \App\Actions\Employee\UpdateBankAccountAction $action)
    {
        try {
            $action->handle($user, $request->validated());
            return redirect()->route('employees.bank-account.edit', $user->id)->with('success', 'Bank account details updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editDocuments(User $user)
    {
        $user->load('employee');
        $section = 'documents';
        return view('employees.edit', compact('user', 'section'));
    }

    public function storeDocument(\App\Http\Requests\Employee\StoreEmployeeDocumentRequest $request, User $user, \App\Actions\Employee\CreateEmployeeDocumentAction $action)
    {
        try {
            $action->handle($user, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateDocument(\App\Http\Requests\Employee\UpdateEmployeeDocumentRequest $request, User $user, \App\Models\Document $document, \App\Actions\Employee\UpdateEmployeeDocumentAction $action)
    {
        try {
            $action->handle($document, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Document updated successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroyDocument(User $user, \App\Models\Document $document, \App\Actions\Employee\DeleteEmployeeDocumentAction $action)
    {
        try {
            $action->handle($document);
            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function downloadDocument(User $user, \App\Models\Document $document)
    {
        $media = $document->getFirstMedia('file');
        if ($media) {
            return response()->download($media->getPath(), $media->file_name);
        }
        return back()->with('error', 'File not found.');
    }

    public function editEducation(User $user)
    {
        $user->load('employee');
        $section = 'education';
        return view('employees.edit', compact('user', 'section'));
    }

    public function storeEducation(\App\Http\Requests\Employee\StoreEducationRequest $request, User $user, \App\Actions\Employee\CreateEducationAction $action)
    {
        try {
            $action->handle($user, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Education details added successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateEducation(\App\Http\Requests\Employee\UpdateEducationRequest $request, User $user, \App\Models\Education $education, \App\Actions\Employee\UpdateEducationAction $action)
    {
        try {
            $action->handle($education, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Education details updated successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroyEducation(User $user, \App\Models\Education $education, \App\Actions\Employee\DeleteEducationAction $action)
    {
        try {
            $action->handle($education);
            return response()->json([
                'success' => true,
                'message' => 'Education deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function editExperience(User $user)
    {
        $user->load('employee');
        $experiences = $user->employee ? $user->employee->experiences : collect();
        $section = 'experience';
        return view('employees.edit', compact('user', 'experiences', 'section'));
    }

    public function storeExperience(\App\Http\Requests\Employee\StoreExperienceRequest $request, User $user, \App\Actions\Employee\CreateExperienceAction $action)
    {
        try {
            $action->handle($user, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Experience record added successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateExperience(\App\Http\Requests\Employee\UpdateExperienceRequest $request, User $user, \App\Models\Experience $experience, \App\Actions\Employee\UpdateExperienceAction $action)
    {
        try {
            $action->handle($experience, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Experience record updated successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroyExperience(User $user, \App\Models\Experience $experience, \App\Actions\Employee\DeleteExperienceAction $action)
    {
        try {
            $action->handle($experience);
            return response()->json([
                'success' => true,
                'message' => 'Experience deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function editAssets(User $user)
    {
        $user->load('employee');
        $assets = $user->employee ? $user->employee->assets : collect();
        $section = 'assets';
        return view('employees.edit', compact('user', 'assets', 'section'));
    }

    public function storeAsset(\App\Http\Requests\Employee\StoreAssetRequest $request, User $user, \App\Actions\Employee\CreateAssetAction $action)
    {
        try {
            $action->handle($user, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Asset recorded successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateAsset(\App\Http\Requests\Employee\UpdateAssetRequest $request, User $user, \App\Models\Asset $asset, \App\Actions\Employee\UpdateAssetAction $action)
    {
        try {
            $action->handle($asset, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Asset record updated successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroyAsset(User $user, \App\Models\Asset $asset, \App\Actions\Employee\DeleteAssetAction $action)
    {
        try {
            $action->handle($asset);
            return response()->json([
                'success' => true,
                'message' => 'Asset deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
