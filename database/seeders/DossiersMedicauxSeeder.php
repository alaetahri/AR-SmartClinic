<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DossiersMedicauxSeeder extends Seeder
{
    public function run(): void
    {
        $patients = DB::table('patients')->orderBy('id')->get();
        $now      = Carbon::now();
        $counter  = 1;

        foreach ($patients as $patient) {
            DB::table('dossiers_medicaux')->insert([
                'patient_id'             => $patient->id,
                'numero_dossier'         => 'DOS-' . str_pad($counter, 5, '0', STR_PAD_LEFT),
                'allergies'              => null,
                'maladies_chroniques'    => null,
                'antecedents_medicaux'   => null,
                'traitements_en_cours'   => null,
                'observations_generales' => null,
                'date_ouverture'         => $now->toDateString(),
                'created_at'             => $now,
                'updated_at'             => $now,
            ]);
            $counter++;
        }
    }
}