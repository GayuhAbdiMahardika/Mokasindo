<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        $category = fake()->randomElement(['motor', 'mobil']);
        $motorBrands = ['Honda', 'Yamaha', 'Suzuki', 'Kawasaki', 'Vespa'];
        $carBrands = ['Toyota', 'Honda', 'Daihatsu', 'Mitsubishi', 'Nissan', 'Mazda'];
        $brand = $category === 'motor'
            ? fake()->randomElement($motorBrands)
            : fake()->randomElement($carBrands);

        $modelsByBrand = [
            'Honda' => ['Brio', 'Jazz', 'CR-V', 'PCX', 'Vario'],
            'Toyota' => ['Avanza', 'Kijang Innova', 'Raize', 'Yaris'],
            'Daihatsu' => ['Ayla', 'Xenia', 'Rocky', 'Terios'],
            'Mitsubishi' => ['Pajero Sport', 'Xpander', 'L300', 'Outlander'],
            'Nissan' => ['Livina', 'Serena', 'X-Trail', 'Magnite'],
            'Mazda' => ['CX-3', 'CX-5', '2', '3'],
            'Yamaha' => ['NMAX', 'Aerox', 'R15', 'MT-25'],
            'Suzuki' => ['Ertiga', 'Carry', 'Satria FU', 'GSX'],
            'Kawasaki' => ['Ninja 250', 'W175', 'KLX 150'],
            'Vespa' => ['Sprint', 'Primavera', 'GTS']
        ];

        $modelName = $modelsByBrand[$brand][array_rand($modelsByBrand[$brand])];
        $year = fake()->numberBetween(2010, (int) now()->format('Y'));
        $startingPrice = $category === 'motor'
            ? fake()->numberBetween(7000000, 35000000)
            : fake()->numberBetween(65000000, 520000000);

        $province = fake()->randomElement(['Jawa Barat', 'DKI Jakarta', 'Jawa Timur', 'Jawa Tengah', 'Banten']);
        $city = fake()->randomElement(['Bandung', 'Jakarta Selatan', 'Surabaya', 'Semarang', 'Tangerang']);
        $district = fake()->randomElement(['Coblong', 'Kebayoran Baru', 'Tegalsari', 'Banyumanik', 'Cipondoh']);
        $subDistrict = fake()->randomElement(['Dago', 'Senayan', 'Dr. Soetomo', 'Pedalangan', 'Poris Gaga']);

        return [
            'user_id' => null,
            'category' => $category,
            'brand' => $brand,
            'model' => $modelName,
            'year' => $year,
            'color' => fake()->safeColorName(),
            'license_plate' => strtoupper(fake()->bothify('B #### ??')),
            'mileage' => fake()->numberBetween(5000, 180000),
            'description' => fake()->sentence(12),
            'starting_price' => $startingPrice,
            'transmission' => fake()->randomElement(['manual', 'matic']),
            'fuel_type' => fake()->randomElement(['bensin', 'diesel', 'hybrid', 'listrik']),
            'engine_capacity' => $category === 'motor'
                ? fake()->numberBetween(110, 250)
                : fake()->numberBetween(1200, 3000),
            'condition' => fake()->randomElement(['bekas', 'baru']),
            'province' => $province,
            'city' => $city,
            'district' => $district,
            'sub_district' => $subDistrict,
            'postal_code' => fake()->postcode(),
            'status' => fake()->randomElement(['draft', 'pending', 'approved']),
            'rejection_reason' => null,
            'approved_at' => null,
            'approved_by' => null,
            'views_count' => fake()->numberBetween(0, 2500),
            'latitude' => fake()->latitude(-8.2, -6.5),
            'longitude' => fake()->longitude(106.7, 110.0),
            'full_address' => fake()->address(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Vehicle $vehicle) {
            // Seed 3 placeholder images per vehicle so production seeds have media
            $disk = Storage::disk('public');
            $folder = 'vehicles';

            for ($i = 1; $i <= 3; $i++) {
                $filename = "$folder/seed-{$vehicle->id}-$i.png";

                if (!$disk->exists($filename)) {
                    $disk->put($filename, base64_decode($this->placeholderPng()));
                }

                VehicleImage::create([
                    'vehicle_id' => $vehicle->id,
                    'image_path' => $filename,
                    'is_primary' => $i === 1,
                    'order' => $i,
                ]);
            }
        });
    }

    private function placeholderPng(): string
    {
        // 50x30 solid gray PNG (tiny payload)
        return 'iVBORw0KGgoAAAANSUhEUgAAADIAAAAcCAYAAACQ9bRrAAAACXBIWXMAAAsSAAALEgHS3X78AAABM0lEQVRoge2YWQ7CMAyG3w4kUJJIUzCSuAiAJVkOCZPSJVrFZ9BvZk90un0nLBKBPXn+M+d5YBgYGAgSMuXYFfI0guzl4ru7HSRCAbxJPJHDL0ZuBuGJZljXlR8rDX36JGnKMMULRZJDXoK8QVMr9DwVTJREdWleSwv7pZIfqN66m6H3w4AAEAAO7pTCoAEFkV/LYYVd0U63gEU+s18wdig5KtyA6xIMCY4oed1/ivZRYeThDvwVf7yqgIQM2ENi51UPYzgRr7NP1e1krrXbhxPkUd7Lgpr1FzS4MrcxZgzN6zj7jsiGveJjbcC/af72T4s1PrLg1lJlra5//qSqaAnx7y+IMpUzc0pTrK50PQboR3Hf0NA1yZPqkUFGMNs/xVCgvwTFgYGK2WgA/ICG+NrS59QAAAAASUVORK5CYII=';
    }
}
