<?php

namespace Database\Factories;

use App\Models\MedicalHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalHistory>
 */
class MedicalHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $weightData = $this->fakeWeigthHeight();
        return [
            "weight"=>$weightData["weight"],
            "height"=>$weightData["height"],
            "chronic_diseases"=>implode(', ', fake()->randomElements(["Diabetes", "Hipertensión", "Asma", "Artritis", 
                "Enfermedad Pulmonar Obstructiva Crónica", "Enfermedad Cardíaca", "Depresión", "Obesidad", 
                "Insuficiencia Renal", "Cáncer"], random_int(0, 3))),
            "allergies"=>implode(', ', fake()->randomElements(["Penicilina", "Ácaros", "Polen", "Cacahuetes", "Mariscos", "Latex",
                "Huevo", "Leche", "Gluten", "Níquel"], random_int(0, 3))),
            "date_of_birth"=>fake()->dateTimeBetween(endDate: now()),
            "medications"=>implode(', ', fake()->randomElements([
                "Aspirina", "Ibuprofeno", "Paracetamol", "Amoxicilina", "Metformina", "Lisinopril", "Atorvastatina", "Omeprazol", "Loratadina", "Fluoxetina"
            ], random_int(0, 3))),
        ];
    }

    // Define el peso del modelo creado mediante un factory
    private function fakeWeigthHeight() : array {
        $weight = fake()->numberBetween(55, 85);
        $IMC = fake()->numberBetween(15, 30);
        $height = sqrt($weight/$IMC);

        return ["weight"=>$weight, "height"=>$height];
    }

}
