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

    public function editBasicInformation(User $employee)
    {
        $roles = Role::pluck('display_name','name');
        $section = 'basic-information';

        return view('employees.edit', compact('employee', 'roles', 'section'));
    }

    public function updateBasicInformation(UpdateBasicInformationRequest $request, User $employee, \App\Actions\Employee\UpdateBasicInformationAction $action) {
        try {
            $action->handle($employee, $request->validated());
            return redirect()->route('employees.basic-information.edit', $employee->id)->with('success', 'Basic information updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editPersonalDetails(User $employee)
    {
        $employee->load('employee');
        $departments = \App\Models\Department::pluck('name', 'id');
        $designations = \App\Models\Designation::pluck('name', 'id');
        $managers = User::where('id', '!=', $employee->id)->get();
        $section = 'personal-details';

        return view('employees.edit', compact('employee', 'departments', 'designations', 'managers', 'section'));
    }

    public function updatePersonalDetails(\App\Http\Requests\Employee\UpdatePersonalDetailsRequest $request, User $employee, \App\Actions\Employee\UpdatePersonalDetailsAction $action)
    {
        try {
            $action->handle($employee, $request->validated());
            return redirect()->route('employees.personal-details.edit', $employee->id)->with('success', 'Personal details updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editFamilyInformation(User $employee)
    {
        $employee->load('employee.familyInformation');
        $section = 'family-information';
        return view('employees.edit', compact('employee', 'section'));
    }
  
    public function updateFamilyInformation(\App\Http\Requests\Employee\UpdateFamilyInformationRequest $request, User $employee, \App\Actions\Employee\UpdateFamilyInformationAction $action)
    {
        try {
            $action->handle($employee, $request->validated());
            return redirect()->route('employees.family-information.edit', $employee->id)->with('success', 'Family information updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editBankAccount(User $employee)
    {
        $employee->load('employee.bankAccount');
        $bankAccount = $employee->employee ? $employee->employee->bankAccount : null;
        $section = 'bank-account';
        return view('employees.edit', compact('employee', 'bankAccount', 'section'));
    }

    public function updateBankAccount(\App\Http\Requests\Employee\UpdateBankAccountRequest $request, User $employee, \App\Actions\Employee\UpdateBankAccountAction $action)
    {
        try {
            $action->handle($employee, $request->validated());
            return redirect()->route('employees.bank-account.edit', $employee->id)->with('success', 'Bank account details updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editDocuments(User $employee)
    {
        $employee->load('employee.documents.media');
        $documents = $employee->employee ? $employee->employee->documents : collect();
        $section = 'documents';
        return view('employees.edit', compact('employee', 'documents', 'section'));
    }

    public function storeDocument(\App\Http\Requests\Employee\StoreEmployeeDocumentRequest $request, User $employee, \App\Actions\Employee\CreateEmployeeDocumentAction $action)
    {
        try {
            $action->handle($employee, $request->validated());
            return redirect()->route('employees.documents.edit', $employee->id)->with('success', 'Document uploaded successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function updateDocument(\App\Http\Requests\Employee\UpdateEmployeeDocumentRequest $request, User $employee, \App\Models\Document $document, \App\Actions\Employee\UpdateEmployeeDocumentAction $action)
    {
        try {
            $action->handle($document, $request->validated());
            return redirect()->route('employees.documents.edit', $employee->id)->with('success', 'Document updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroyDocument(User $employee, \App\Models\Document $document, \App\Actions\Employee\DeleteEmployeeDocumentAction $action)
    {
        try {
            $action->handle($document);
            return redirect()->route('employees.documents.edit', $employee->id)->with('success', 'Document deleted successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function downloadDocument(User $employee, \App\Models\Document $document)
    {
        $media = $document->getFirstMedia('file');
        if ($media) {
            return response()->download($media->getPath(), $media->file_name);
        }
        return back()->with('error', 'File not found.');
    }

    public function editEducation(User $employee)
    {
        $employee->load('employee.education');
        $educationList = $employee->employee ? $employee->employee->education : collect();
        $section = 'education';
        return view('employees.edit', compact('employee', 'educationList', 'section'));
    }

    public function storeEducation(\App\Http\Requests\Employee\StoreEducationRequest $request, User $employee, \App\Actions\Employee\CreateEducationAction $action)
    {
        try {
            $action->handle($employee, $request->validated());
            return redirect()->route('employees.education.edit', $employee->id)->with('success', 'Education details added successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function updateEducation(\App\Http\Requests\Employee\UpdateEducationRequest $request, User $employee, \App\Models\Education $education, \App\Actions\Employee\UpdateEducationAction $action)
    {
        try {
            $action->handle($education, $request->validated());
            return redirect()->route('employees.education.edit', $employee->id)->with('success', 'Education details updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroyEducation(User $employee, \App\Models\Education $education, \App\Actions\Employee\DeleteEducationAction $action)
    {
        try {
            $action->handle($education);
            return redirect()->route('employees.education.edit', $employee->id)->with('success', 'Education record deleted successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function editExperience(User $employee)
    {
        $employee->load('employee.experiences');
        $experiences = $employee->employee ? $employee->employee->experiences : collect();
        $section = 'experience';
        return view('employees.edit', compact('employee', 'experiences', 'section'));
    }

    public function storeExperience(\App\Http\Requests\Employee\StoreExperienceRequest $request, User $employee, \App\Actions\Employee\CreateExperienceAction $action)
    {
        try {
            $action->handle($employee, $request->validated());
            return redirect()->route('employees.experience.edit', $employee->id)->with('success', 'Experience record added successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function updateExperience(\App\Http\Requests\Employee\UpdateExperienceRequest $request, User $employee, \App\Models\Experience $experience, \App\Actions\Employee\UpdateExperienceAction $action)
    {
        try {
            $action->handle($experience, $request->validated());
            return redirect()->route('employees.experience.edit', $employee->id)->with('success', 'Experience record updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroyExperience(User $employee, \App\Models\Experience $experience, \App\Actions\Employee\DeleteExperienceAction $action)
    {
        try {
            $action->handle($experience);
            return redirect()->route('employees.experience.edit', $employee->id)->with('success', 'Experience record deleted successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function editAssets(User $employee)
    {
        $employee->load('employee.assets');
        $assets = $employee->employee ? $employee->employee->assets : collect();
        $section = 'assets';
        return view('employees.edit', compact('employee', 'assets', 'section'));
    }

    public function storeAsset(\App\Http\Requests\Employee\StoreAssetRequest $request, User $employee, \App\Actions\Employee\CreateAssetAction $action)
    {
        try {
            $action->handle($employee, $request->validated());
            return redirect()->route('employees.assets.edit', $employee->id)->with('success', 'Asset recorded successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function updateAsset(\App\Http\Requests\Employee\UpdateAssetRequest $request, User $employee, \App\Models\Asset $asset, \App\Actions\Employee\UpdateAssetAction $action)
    {
        try {
            $action->handle($asset, $request->validated());
            return redirect()->route('employees.assets.edit', $employee->id)->with('success', 'Asset record updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroyAsset(User $employee, \App\Models\Asset $asset, \App\Actions\Employee\DeleteAssetAction $action)
    {
        try {
            $action->handle($asset);
            return redirect()->route('employees.assets.edit', $employee->id)->with('success', 'Asset record deleted successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
