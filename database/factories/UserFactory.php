<?php

namespace Database\Factories;

use App\Modules\Identity\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Password default seluruh user hasil factory. Di-hash sekali lalu dipakai
     * ulang supaya suite tidak membayar biaya bcrypt per user.
     */
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            // StoreUserRequest/UpdateUserRequest memberlakukan `alpha_dash`,
            // sedangkan fake()->userName() bisa menyisipkan titik. Username
            // hasil factory harus lolos validasi aplikasi itu sendiri.
            'username' => fake()->unique()->bothify('user_#####?'),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'is_active' => true,
            'last_login_at' => null,
            'remember_token' => Str::random(10),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
