<?php

namespace App\Modules\Auth\Factories;

use App\Modules\Auth\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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
            'name' => $this->faker->name,
            'surnames' => $this->faker->name . ' ' . $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            //'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            //'password' => '$2y$10$pclCf11VkdFA89Uxqz.cxO0VuX8NawFr0UzjRjlR8cv2Y7ZcA5Ukq', //Wasabil1234$
            'password' => '$2y$10$1.EgCUF9ySmmG1LKPRWeIe1lX519YfgPhJBq0O/ooZ5QKpXZfVC0O', //ByG01234$
            'remember_token' => Str::random(10),
        ];
    }
}
