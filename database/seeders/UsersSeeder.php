<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ════════════════════════════════════════════════════════
        //  1. ADMIN
        // ════════════════════════════════════════════════════════
        $adminId = DB::table('users')->insertGetId([
            'nom'       => 'Benjelloun',
            'prenom'    => 'Khalid',
            'email'     => 'admin@arsmartclinic.ma',
            'password'  => Hash::make('Admin@1234'),
            'telephone' => '0661000001',
            'photo'     => null,
            'role'      => 'admin',
            'created_at'=> $now,
            'updated_at'=> $now,
        ]);

        // ════════════════════════════════════════════════════════
        //  2. SECRÉTAIRE
        // ════════════════════════════════════════════════════════
        $secId = DB::table('users')->insertGetId([
            'nom'       => 'Tahiri',
            'prenom'    => 'Sanae',
            'email'     => 'secretaire@arsmartclinic.ma',
            'password'  => Hash::make('Secret@1234'),
            'telephone' => '0661000002',
            'photo'     => null,
            'role'      => 'secretaire',
            'created_at'=> $now,
            'updated_at'=> $now,
        ]);

        // ════════════════════════════════════════════════════════
        //  3. MÉDECINS  (un par spécialité principale)
        // ════════════════════════════════════════════════════════
        $medecins = [
            // [nom, prenom, email, tel, specialite_id, numero_ordre, biographie]
            ['Alaoui',      'Rachid',   'medecin.alaoui@arsmartclinic.ma',     '0661100001', 1,  'MG-001', 'Médecin généraliste avec 15 ans d\'expérience en médecine de famille.'],
            ['Benali',      'Fatima',   'medecin.benali@arsmartclinic.ma',      '0661100002', 2,  'CAR-002','Cardiologue spécialisée en cardiologie interventionnelle.'],
            ['Chaoui',      'Hamid',    'medecin.chaoui@arsmartclinic.ma',      '0661100003', 3,  'NEU-003','Neurologue expert en épilepsie et maladies neurodégénératives.'],
            ['Drissi',      'Nadia',    'medecin.drissi@arsmartclinic.ma',      '0661100004', 4,  'PED-004','Pédiatre avec une expertise en néonatologie et nutrition infantile.'],
            ['El Fassi',    'Latifa',   'medecin.elfassi@arsmartclinic.ma',     '0661100005', 5,  'GYN-005','Gynécologue-obstétricienne, 12 ans de pratique en maternité.'],
            ['Ghali',       'Youssef',  'medecin.ghali@arsmartclinic.ma',       '0661100006', 6,  'ORT-006','Orthopédiste spécialisé en chirurgie du genou et de la hanche.'],
            ['Hassani',     'Salma',    'medecin.hassani@arsmartclinic.ma',     '0661100007', 7,  'DER-007','Dermatologue spécialisée en dermatologie cosmétique et allergologie.'],
            ['Idrissi',     'Omar',     'medecin.idrissi@arsmartclinic.ma',     '0661100008', 8,  'OPH-008','Ophtalmologue expert en chirurgie réfractive et glaucome.'],
            ['Jabri',       'Khadija',  'medecin.jabri@arsmartclinic.ma',       '0661100009', 9,  'ORL-009','ORL spécialisée en otologie et chirurgie endoscopique sinusienne.'],
            ['Kadiri',      'Mehdi',    'medecin.kadiri@arsmartclinic.ma',      '0661100010', 10, 'GAS-010','Gastro-entérologue expert en endoscopie digestive.'],
            ['Lahlou',      'Zineb',    'medecin.lahlou@arsmartclinic.ma',      '0661100011', 11, 'PNE-011','Pneumologue spécialisée dans l\'asthme, la BPCO et la tuberculose.'],
            ['Moussaoui',   'Amine',    'medecin.moussaoui@arsmartclinic.ma',   '0661100012', 12, 'END-012','Endocrinologue expert en diabétologie et thyroïdologie.'],
            ['Naciri',      'Houda',    'medecin.naciri@arsmartclinic.ma',      '0661100013', 13, 'RHU-013','Rhumatologue spécialisée en polyarthrite rhumatoïde et spondylarthrite.'],
            ['Ouali',       'Tariq',    'medecin.ouali@arsmartclinic.ma',       '0661100014', 14, 'URO-014','Urologue expert en lithiase urinaire et pathologies prostatiques.'],
            ['Qasmi',       'Samira',   'medecin.qasmi@arsmartclinic.ma',       '0661100015', 15, 'PSY-015','Psychiatre spécialisée en thérapies cognitivo-comportementales.'],
        ];

        foreach ($medecins as $m) {
            $userId = DB::table('users')->insertGetId([
                'nom'        => $m[0],
                'prenom'     => $m[1],
                'email'      => $m[2],
                'password'   => Hash::make('Medecin@1234'),
                'telephone'  => $m[3],
                'photo'      => null,
                'role'       => 'medecin',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('medecins')->insert([
                'user_id'       => $userId,
                'specialite_id' => $m[4],
                'numero_ordre'  => $m[5],
                'biographie'    => $m[6],
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        // ════════════════════════════════════════════════════════
        //  4. PATIENTS 
        // ════════════════════════════════════════════════════════
        $patients = [
            // [nom, prenom, email, tel, cin, date_naissance, sexe, adresse, groupe_sanguin]
            ['Bouzidi',   'Mohammed',  'patient.bouzidi@gmail.com',    '0612000001', 'AB123456', '1985-03-15', 'homme',  '12 Rue Hassan II, Casablanca',     'A+'],
            ['Chraibi',   'Fatima',    'patient.chraibi@gmail.com',    '0612000002', 'CD234567', '1990-07-22', 'femme',  '5 Avenue Mohammed V, Rabat',       'B+'],
            ['Darif',     'Karim',     'patient.darif@gmail.com',      '0612000003', 'EF345678', '1978-11-08', 'homme',  '34 Boulevard Zerktouni, Marrakech', 'O+'],
            ['El Amrani', 'Zineb',     'patient.elamrani@gmail.com',   '0612000004', 'GH456789', '1995-02-14', 'femme',  '8 Rue Ibn Batouta, Fès',           'AB-'],
            ['Filali',    'Youssef',   'patient.filali@gmail.com',     '0612000005', 'IJ567890', '1983-09-30', 'homme',  '22 Rue Moulay Ismail, Meknès',     'A-'],
            ['Ghazali',   'Imane',     'patient.ghazali@gmail.com',    '0612000006', 'KL678901', '1998-05-18', 'femme',  '17 Rue Al Qods, Agadir',           'B-'],
            ['Hajji',     'Abdelaziz', 'patient.hajji@gmail.com',      '0612000007', 'MN789012', '1970-12-25', 'homme',  '3 Avenue FAR, Tanger',             'O-'],
            ['Idrissi',   'Meriem',    'patient.idrissi@gmail.com',    '0612000008', 'OP890123', '1992-04-07', 'femme',  '9 Rue Oulad Jerrar, Oujda',        'A+'],
            ['Jennane',   'Hassan',    'patient.jennane@gmail.com',    '0612000009', 'QR901234', '1965-08-19', 'homme',  '45 Rue Al Massira, Kenitra',       'B+'],
            ['Kettani',   'Nora',      'patient.kettani@gmail.com',    '0612000010', 'ST012345', '1988-01-03', 'femme',  '11 Boulevard Al Qods, Tétouan',    'O+'],
        ];

        foreach ($patients as $p) {
            $userId = DB::table('users')->insertGetId([
                'nom'        => $p[0],
                'prenom'     => $p[1],
                'email'      => $p[2],
                'password'   => Hash::make('Patient@1234'),
                'telephone'  => $p[3],
                'photo'      => null,
                'role'       => 'patient',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('patients')->insert([
                'user_id'                    => $userId,
                'cin'                        => $p[4],
                'date_naissance'             => $p[5],
                'sexe'                       => $p[6],
                'adresse'                    => $p[7],
                'groupe_sanguin'             => $p[8],
                'contact_urgence_nom'        => null,
                'contact_urgence_telephone'  => null,
                'created_at'                 => $now,
                'updated_at'                 => $now,
            ]);
        }
    }
}