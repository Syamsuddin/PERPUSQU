<?php

namespace App\Modules\Identity\Support;

use App\Modules\Identity\Models\User;

/**
 * Menentukan halaman awal yang tepat bagi seorang pengguna.
 *
 * Sebelumnya tujuan setelah login ditulis mati sebagai dashboard admin, di dua
 * tempat terpisah. Anggota perpustakaan tidak memiliki `core.view_dashboard`,
 * sehingga setiap kali masuk ia langsung mendarat di halaman 403.
 *
 * Urutannya menurun dari yang paling berwenang: staf ke dashboard, anggota ke
 * portalnya, pengguna tanpa keduanya setidaknya ke profilnya sendiri, dan
 * sisanya ke katalog publik yang selalu terbuka.
 */
class HomeRedirect
{
    public static function routeNameFor(?User $user): string
    {
        if (! $user) {
            return 'opac.home';
        }

        return match (true) {
            $user->can('core.view_dashboard') => 'admin.dashboard.index',
            $user->can('own_loans.view') => 'member.portal.loans',
            $user->can('own_profile.view') => 'admin.profile.show',
            default => 'opac.home',
        };
    }

    public static function urlFor(?User $user): string
    {
        return route(self::routeNameFor($user));
    }
}
