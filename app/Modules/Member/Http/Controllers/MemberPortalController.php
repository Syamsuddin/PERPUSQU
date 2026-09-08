<?php

namespace App\Modules\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Member\Models\Member;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Layanan mandiri anggota: pinjaman saya, riwayat, dan denda saya.
 *
 * Sebelum ini peran "Anggota Perpustakaan" memiliki izin untuk ketiganya tanpa
 * satu pun route yang melayaninya — anggota yang masuk langsung mendarat di
 * halaman 403.
 *
 * Seluruh kueri di sini dibatasi pada anggota yang sedang masuk. Tidak ada
 * parameter route yang menyebut anggota lain, sehingga tidak ada permukaan
 * untuk mengintip pinjaman orang lain dengan menebak id.
 */
class MemberPortalController extends Controller
{
    public function loans(): View
    {
        if (! $member = $this->currentMember()) {
            return $this->unlinkedAccount();
        }

        $loans = $member->loans()
            ->with(['physicalItem.bibliographicRecord.authors'])
            ->where('loan_status', 'active')
            ->orderBy('due_date')
            ->paginate(15);

        return view('modules.member.portal.loans', compact('member', 'loans'));
    }

    public function history(Request $request): View
    {
        if (! $member = $this->currentMember()) {
            return $this->unlinkedAccount();
        }

        $loans = $member->loans()
            ->with(['physicalItem.bibliographicRecord.authors', 'returnTransaction'])
            ->latest('loan_date')
            ->paginate(15);

        return view('modules.member.portal.history', compact('member', 'loans'));
    }

    public function fines(): View
    {
        if (! $member = $this->currentMember()) {
            return $this->unlinkedAccount();
        }

        $fines = $member->fines()
            ->with(['loan.physicalItem.bibliographicRecord'])
            ->latest()
            ->paginate(15);

        $outstanding = $member->fines()->where('status', 'outstanding')->sum('amount');

        return view('modules.member.portal.fines', compact('member', 'fines', 'outstanding'));
    }

    /**
     * Anggota milik akun yang sedang masuk, atau null bila akunnya belum
     * ditautkan ke data anggota mana pun.
     */
    protected function currentMember(): ?Member
    {
        $member = auth()->user()?->member;

        return $member instanceof Member ? $member : null;
    }

    /**
     * Akun tanpa tautan bukan pelanggaran hak akses — izinnya benar, datanya
     * yang belum disiapkan. Karena itu yang ditampilkan halaman penjelasan,
     * bukan 403 yang menyesatkan.
     */
    protected function unlinkedAccount(): View
    {
        return view('modules.member.portal.unlinked');
    }
}
