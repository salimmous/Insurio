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

        $slugMap = [
            'Dacia' => 'dacia',
            'Renault' => 'renault',
            'Peugeot' => 'peugeot',
            'Citroën' => 'citroen',
            'Volkswagen' => 'volkswagen',
            'Toyota' => 'toyota',
            'Hyundai' => 'hyundai',
            'Kia' => 'kia',
            'Ford' => 'ford',
            'Fiat' => 'fiat',
            'Opel' => 'opel',
            'Seat' => 'seat',
            'Skoda' => 'skoda',
            'Škoda' => 'skoda',
            'BMW' => 'bmw',
            'Mercedes-Benz' => 'mercedes',
            'Mercedes-Benz (Autocar)' => 'mercedes',
            'Audi' => 'audi',
            'Nissan' => 'nissan',
            'Suzuki' => 'suzuki',
            'Suzuki (Moto)' => 'suzuki',
            'Mitsubishi' => 'mitsubishi',
            'Honda' => 'honda',
            'Honda (Moto)' => 'honda',
            'Mazda' => 'mazda',
            'Chevrolet' => 'chevrolet',
            'Jeep' => 'jeep',
            'Land Rover' => 'landrover',
            'Volvo' => 'volvo',
            'Volvo Buses' => 'volvo',
            'Alfa Romeo' => 'alfaromeo',
            'Porsche' => 'porsche',
            'Lexus' => 'lexus',
            'Infiniti' => 'infiniti',
            'Subaru' => 'subaru',
            'Isuzu' => 'isuzu',
            'Isuzu (Autocar)' => 'isuzu',
            'BYD' => 'byd',
            'MG' => 'mg',
            'Yamaha (Moto)' => 'yamaha',
            'Kawasaki (Moto)' => 'kawasaki',
            'KTM' => 'ktm',
            'Piaggio' => 'piaggio',
            'Aprilia' => 'aprilia',
            'Iveco' => 'iveco',
            'Iveco (Autocar)' => 'iveco',
            'Scania' => 'scania',
            'Scania (Autocar)' => 'scania',
            'MAN' => 'man',
            'MAN (Autocar)' => 'man',
        ];

        $cleanNom = trim($this->nom);
        $slug = $slugMap[$cleanNom] ?? strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode(' ', $cleanNom)[0]));

        return "https://cdn.simpleicons.org/{$slug}";
    }
}
