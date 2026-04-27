<?php

namespace App\Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Services\InstitutionProfileService;
use Illuminate\Http\Request;

class InstitutionProfileController extends Controller
{
    public function __construct(protected InstitutionProfileService $profileService) {}

    public function edit()
    {
        $profile = $this->profileService->getInstitutionProfile();
        return view('modules.core.institution_profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'institution_name' => 'required|string|min:3|max:255',
            'library_name' => 'required|string|min:3|max:255',
            'address' => 'nullable|string|max:2000',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email:rfc|max:150',
            'website' => 'nullable|url|max:255',
            'about_text' => 'nullable|string|max:10000',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $this->profileService->updateInstitutionProfile($request->all());
        return back()->with('success', 'Profil institusi berhasil diperbarui.');
    }
}
