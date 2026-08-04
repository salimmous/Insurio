<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehiculeMarque extends Model
{
    protected $table = 'vehicule_marques';

    protected $fillable = [
        'nom',
        'type',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function modeles(): HasMany
    {
        return $this->hasMany(VehiculeModele::class, 'marque_id')->orderBy('nom');
    }

    public function getLogoUrlAttribute(): string
    {
        if (!empty($this->logo)) {
            return $this->logo;
        }

        $cleanNom = trim(preg_replace('/\([^)]+\)/', '', $this->nom));

        $directMap = [
            'Alfa Romeo' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/alfa-romeo.png',
            'Audi' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/audi.png',
            'BAIC' => 'https://www.carlogos.org/car-logos/baic-logo.png',
            'BMW' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/bmw.png',
            'BYD' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/byd.png',
            'Changan' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/changan.png',
            'Chery' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/chery.png',
            'Chevrolet' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/chevrolet.png',
            'Citroën' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/citroen.png',
            'Dacia' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/dacia.png',
            'Fiat' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/fiat.png',
            'Ford' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/ford.png',
            'Geely' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/geely.png',
            'Haval' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/haval.png',
            'Hino' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/hino.png',
            'Honda' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/honda.png',
            'Hyundai' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/hyundai.png',
            'Infiniti' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/infiniti.png',
            'Isuzu' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/isuzu.png',
            'Iveco' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/iveco.png',
            'JAC' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/jac.png',
            'Jeep' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/jeep.png',
            'Kia' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/kia.png',
            'King Long' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/king-long.png',
            'KTM' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/ktm.png',
            'Land Rover' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/land-rover.png',
            'Lexus' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/lexus.png',
            'Lifan' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/lifan.png',
            'MAN' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/man.png',
            'Mazda' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/mazda.png',
            'Mercedes-Benz' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/mercedes-benz.png',
            'MG' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/mg.png',
            'Mitsubishi' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/mitsubishi.png',
            'Nissan' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/nissan.png',
            'Opel' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/opel.png',
            'Peugeot' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/peugeot.png',
            'Porsche' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/porsche.png',
            'Renault' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/renault.png',
            'Scania' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/scania.png',
            'Seat' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/seat.png',
            'Skoda' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/skoda.png',
            'Škoda' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/skoda.png',
            'Ssangyong' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/ssangyong.png',
            'Subaru' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/subaru.png',
            'Suzuki' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/suzuki.png',
            'Toyota' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/toyota.png',
            'Volkswagen' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/volkswagen.png',
            'Volvo' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/volvo.png',
            'Yutong' => 'https://raw.githubusercontent.com/filippofilip95/car-logos-dataset/master/logos/optimized/yutong.png',
        ];

        if (isset($directMap[$cleanNom])) {
            return $directMap[$cleanNom];
        }

        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode(' ', $cleanNom)[0]));
        return "https://cdn.simpleicons.org/{$slug}";
    }
}
