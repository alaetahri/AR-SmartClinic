<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialitesSeeder extends Seeder
{
    public function run(): void
    {
        $specialites = [
            [
                'nom'         => 'Médecine Générale',
                'description' => 'Prise en charge globale du patient, suivi médical courant, consultations de premier recours.',
            ],
            [
                'nom'         => 'Cardiologie',
                'description' => 'Diagnostic et traitement des maladies du cœur et des vaisseaux sanguins.',
            ],
            [
                'nom'         => 'Neurologie',
                'description' => 'Maladies du système nerveux central et périphérique (AVC, épilepsie, migraines).',
            ],
            [
                'nom'         => 'Pédiatrie',
                'description' => 'Médecine de l\'enfant et de l\'adolescent, suivi de croissance et vaccinations.',
            ],
            [
                'nom'         => 'Gynécologie-Obstétrique',
                'description' => 'Santé de la femme, suivi de grossesse, accouchement et pathologies gynécologiques.',
            ],
            [
                'nom'         => 'Orthopédie',
                'description' => 'Traitement des affections de l\'appareil locomoteur : os, articulations, muscles et tendons.',
            ],
            [
                'nom'         => 'Dermatologie',
                'description' => 'Diagnostic et traitement des maladies de la peau, des cheveux et des ongles.',
            ],
            [
                'nom'         => 'Ophtalmologie',
                'description' => 'Soins des yeux et troubles de la vision.',
            ],
            [
                'nom'         => 'ORL',
                'description' => 'Oto-rhino-laryngologie : maladies des oreilles, du nez et de la gorge.',
            ],
            [
                'nom'         => 'Gastro-entérologie',
                'description' => 'Pathologies du système digestif : estomac, intestins, foie, pancréas.',
            ],
            [
                'nom'         => 'Pneumologie',
                'description' => 'Maladies respiratoires : asthme, BPCO, pneumonies, tuberculose.',
            ],
            [
                'nom'         => 'Endocrinologie',
                'description' => 'Troubles hormonaux : diabète, thyroïde, obésité, ostéoporose.',
            ],
            [
                'nom'         => 'Rhumatologie',
                'description' => 'Maladies inflammatoires et dégénératives des articulations et des os.',
            ],
            [
                'nom'         => 'Urologie',
                'description' => 'Pathologies de l\'appareil urinaire et génital masculin.',
            ],
            [
                'nom'         => 'Psychiatrie',
                'description' => 'Santé mentale : dépression, anxiété, troubles bipolaires, schizophrénie.',
            ],
        ];

        DB::table('specialites')->insert($specialites);
    }
}