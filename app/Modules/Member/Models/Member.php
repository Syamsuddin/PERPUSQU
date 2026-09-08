<?php

namespace App\Modules\Member\Models;

use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Identity\Models\User;
use App\Modules\MasterData\Models\Faculty;
use App\Modules\MasterData\Models\StudyProgram;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $member_number
 * @property string $member_type
 * @property string|null $identity_number
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $faculty_id
 * @property int|null $study_program_id
 * @property bool $is_active
 * @property bool $is_blocked
 * @property string|null $blocked_reason
 * @property Carbon|null $blocked_at
 * @property string|null $notes
 * @property-read User|null $user
 * @property-read Faculty|null $faculty
 * @property-read StudyProgram|null $studyProgram
 * @property-read Collection<int, Loan> $loans
 * @property-read Collection<int, Fine> $fines
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static> keyword(?string $keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static> active()
 * @method static \Illuminate\Database\Eloquent\Builder<static> blocked()
 */
class Member extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'members';

    protected $fillable = [
        'user_id', 'member_number', 'name', 'member_type', 'faculty_id', 'study_program_id',
        'identity_number', 'email', 'phone', 'is_active', 'is_blocked',
        'blocked_reason', 'blocked_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_blocked' => 'boolean',
            'blocked_at' => 'datetime',
        ];
    }

    /**
     * Alamat tujuan surat. Anggota punya emailnya sendiri, terpisah dari akun
     * login — banyak anggota dilayani di meja sirkulasi tanpa pernah punya akun.
     */
    public function routeNotificationForMail(): ?string
    {
        return $this->email;
    }

    /**
     * Akun login milik anggota ini, bila ada. Anggota yang hanya dilayani di
     * meja sirkulasi tidak perlu punya akun.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBlocked($query)
    {
        return $query->where('is_blocked', true);
    }

    public function scopeKeyword($query, ?string $keyword)
    {
        if (! $keyword) {
            return $query;
        }

        return $query->where(fn ($q) => $q->where('name', 'like', "%{$keyword}%")->orWhere('member_number', 'like', "%{$keyword}%")->orWhere('identity_number', 'like', "%{$keyword}%"));
    }
}
