<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->mobileNumber,
            'dob' => $this->faker->date('Y-m-d'),
            'country_code' => 'MY',
            'avatar' => $this->faker->imageUrl(45, 45),
            'email_verified_at' => now(),
            'password' => bcrypt('aaaaaa'), // password
            'remember_token' => Str::random(10),
        ];
    }
}
