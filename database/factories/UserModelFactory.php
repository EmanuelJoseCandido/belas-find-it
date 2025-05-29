<?php

namespace Database\Factories;

use App\Models\UserModel;
use App\Enums\Users\GenderEnum;
use App\Enums\Users\RoleEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserModel>
 */
class UserModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = UserModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+244' . fake()->numerify('#########'),
            'password' => Hash::make('password'),
            'role' => fake()->randomElement(RoleEnum::cases()),
            'gender' => fake()->randomElement(GenderEnum::cases()),
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user should be inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the user should be an admin.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => RoleEnum::ADMIN,
        ]);
    }

    /**
     * Indicate that the user should be a moderator.
     */
    public function moderator(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => RoleEnum::MODERATOR,
        ]);
    }
}
