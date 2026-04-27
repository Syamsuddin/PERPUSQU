<?php

namespace App\Modules\Member\Services;

use App\Modules\Member\Models\Member;

class MemberBlockingService
{
    /**
     * Block a member — sets is_blocked = true with reason and timestamp
     */
    public function block(Member $member, string $reason): Member
    {
        $member->update([
            'is_blocked' => true,
            'blocked_reason' => $reason,
            'blocked_at' => now(),
        ]);

        activity('member')
            ->causedBy(auth()->user())
            ->performedOn($member)
            ->withProperties(['action' => 'block', 'reason' => $reason])
            ->log('Anggota diblokir: '.$member->name.' — '.$reason);

        return $member;
    }

    /**
     * Unblock a member — sets is_blocked = false, clears reason
     */
    public function unblock(Member $member): Member
    {
        $member->update([
            'is_blocked' => false,
            'blocked_reason' => null,
            'blocked_at' => null,
        ]);

        activity('member')
            ->causedBy(auth()->user())
            ->performedOn($member)
            ->withProperties(['action' => 'unblock'])
            ->log('Anggota di-unblock: '.$member->name);

        return $member;
    }
}
