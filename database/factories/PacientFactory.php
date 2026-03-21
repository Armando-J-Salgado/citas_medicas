<?php

namespace Database\Factories;

use App\Models\Pacient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pacient>
 */
class PacientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(["male", "female"]);
        return [
            "name"=> $gender === "male" ? fake()->firstNameMale() : fake()->firstNameFemale(),
            "lastname"=>fake()->lastName(),
            "phone_number"=>fake()->numerify("####-####"),
            "dui"=>fake()->numerify("########-#"),
            "gender"=>$gender,
        ];
    }
}
