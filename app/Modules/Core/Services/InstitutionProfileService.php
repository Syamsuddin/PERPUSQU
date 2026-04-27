<?php

namespace App\Modules\Core\Services;

use App\Modules\Core\Models\InstitutionProfile;
use Illuminate\Support\Facades\Storage;

class InstitutionProfileService
{
    public function getInstitutionProfile(): ?InstitutionProfile
    {
        return InstitutionProfile::current();
    }

    public function updateInstitutionProfile(array $data): InstitutionProfile
    {
        $profile = InstitutionProfile::current() ?? new InstitutionProfile();

        if (isset($data['logo']) && $data['logo']) {
            if ($profile->logo_path) {
                Storage::disk('public')->delete($profile->logo_path);
            }
            $data['logo_path'] = $data['logo']->store('institution', 'public');
            unset($data['logo']);
        }

        $profile->fill($data);
        $profile->save();

        activity('core')
            ->causedBy(auth()->user())
            ->performedOn($profile)
            ->log('Profil institusi diperbarui');

        return $profile;
    }
}
