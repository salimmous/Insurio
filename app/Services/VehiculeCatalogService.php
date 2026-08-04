<?php

namespace App\Services;

use App\Models\VehiculeMarque;
use App\Models\VehiculeModele;

class VehiculeCatalogService
{
    /**
     * Seeds or ensures default brands and models are loaded in DB.
     */
    public static function seedIfEmpty(): void
    {
        if (VehiculeMarque::count() > 0) {
            return;
        }

        $catalog = config('vehicules_maroc', []);

        foreach ($catalog as $brandName => $info) {
            $marque = VehiculeMarque::create([
                'nom' => $brandName,
                'type' => $info['type'] ?? 'voiture',
                'is_active' => true,
            ]);

            foreach ($info['modeles'] ?? [] as $modelName) {
                VehiculeModele::create([
                    'marque_id' => $marque->id,
                    'nom' => $modelName,
                    'is_active' => true,
                ]);
            }
        }
    }
}
