<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SymptomsSeeder extends Seeder
{
    public function run(): void
    {
        // Les IDs correspondent à l'ordre d'insertion dans SpecialitesSeeder
        // 1=Médecine Générale, 2=Cardiologie, 3=Neurologie, 4=Pédiatrie,
        // 5=Gynéco, 6=Orthopédie, 7=Dermato, 8=Ophtalmo, 9=ORL,
        // 10=Gastro, 11=Pneumo, 12=Endocrino, 13=Rhumato, 14=Urologie, 15=Psychiatrie

        $symptomes = [

            // ── Médecine Générale (1) ───────────────────────────────────────
            ['nom' => 'Fièvre',              'description' => 'Température corporelle supérieure à 38°C.',                         'specialite_id' => 1],
            ['nom' => 'Fatigue générale',    'description' => 'Sensation persistante d\'épuisement sans effort particulier.',      'specialite_id' => 1],
            ['nom' => 'Maux de tête',        'description' => 'Céphalées d\'intensité variable, fréquentes.',                     'specialite_id' => 1],
            ['nom' => 'Toux sèche',          'description' => 'Toux sans expectorations, irritative.',                            'specialite_id' => 1],
            ['nom' => 'Nausées',             'description' => 'Envie de vomir, inconfort gastrique.',                             'specialite_id' => 1],

            // ── Cardiologie (2) ─────────────────────────────────────────────
            ['nom' => 'Douleur thoracique',  'description' => 'Douleur ou pression dans la poitrine pouvant irradier au bras.',   'specialite_id' => 2],
            ['nom' => 'Essoufflement',       'description' => 'Dyspnée au repos ou à l\'effort.',                                 'specialite_id' => 2],
            ['nom' => 'Palpitations',        'description' => 'Sensation de battements cardiaques irréguliers ou rapides.',       'specialite_id' => 2],
            ['nom' => 'Œdèmes des membres', 'description' => 'Gonflement des chevilles ou des jambes.',                          'specialite_id' => 2],
            ['nom' => 'Vertiges cardiaques', 'description' => 'Vertiges liés à une hypotension ou arythmie.',                    'specialite_id' => 2],

            // ── Neurologie (3) ──────────────────────────────────────────────
            ['nom' => 'Céphalées intenses',  'description' => 'Migraines sévères ou céphalées de tension persistantes.',          'specialite_id' => 3],
            ['nom' => 'Troubles de mémoire', 'description' => 'Oublis fréquents, difficultés de concentration.',                  'specialite_id' => 3],
            ['nom' => 'Engourdissements',    'description' => 'Picotements ou perte de sensibilité dans les membres.',            'specialite_id' => 3],
            ['nom' => 'Convulsions',         'description' => 'Crises épileptiques avec contractions musculaires involontaires.', 'specialite_id' => 3],
            ['nom' => 'Troubles de la marche','description' => 'Difficultés à marcher, perte d\'équilibre.',                     'specialite_id' => 3],

            // ── Pédiatrie (4) ───────────────────────────────────────────────
            ['nom' => 'Fièvre de l\'enfant', 'description' => 'Température élevée chez l\'enfant de moins de 15 ans.',           'specialite_id' => 4],
            ['nom' => 'Retard de croissance','description' => 'Croissance insuffisante par rapport à la courbe normale.',         'specialite_id' => 4],
            ['nom' => 'Diarrhée infantile',  'description' => 'Selles liquides fréquentes chez l\'enfant.',                      'specialite_id' => 4],
            ['nom' => 'Éruption cutanée enfant','description' => 'Rougeurs, boutons ou plaques sur la peau de l\'enfant.',       'specialite_id' => 4],
            ['nom' => 'Pleurs excessifs',    'description' => 'Pleurs inconsolables chez le nourrisson (coliques).',             'specialite_id' => 4],

            // ── Gynécologie (5) ─────────────────────────────────────────────
            ['nom' => 'Douleurs pelviennes', 'description' => 'Douleurs dans le bas-ventre chez la femme.',                      'specialite_id' => 5],
            ['nom' => 'Irrégularités menstruelles','description' => 'Cycles irréguliers, règles douloureuses ou absentes.',      'specialite_id' => 5],
            ['nom' => 'Pertes vaginales anormales','description' => 'Sécrétions vaginales inhabituelles en couleur ou odeur.',   'specialite_id' => 5],
            ['nom' => 'Douleurs pendant les rapports','description' => 'Dyspareunie, inconfort lors des rapports sexuels.',      'specialite_id' => 5],

            // ── Orthopédie (6) ──────────────────────────────────────────────
            ['nom' => 'Douleur lombaire',    'description' => 'Lombalgie aiguë ou chronique, douleur dans le bas du dos.',       'specialite_id' => 6],
            ['nom' => 'Douleur articulaire', 'description' => 'Arthralgie touchant genoux, hanches, épaules ou poignets.',       'specialite_id' => 6],
            ['nom' => 'Fracture suspectée',  'description' => 'Douleur et déformation après un traumatisme.',                    'specialite_id' => 6],
            ['nom' => 'Douleur cervicale',   'description' => 'Cervicalgie, raideur du cou avec ou sans irradiation.',          'specialite_id' => 6],

            // ── Dermatologie (7) ────────────────────────────────────────────
            ['nom' => 'Éruption cutanée',    'description' => 'Rougeurs, plaques ou boutons sur la peau.',                       'specialite_id' => 7],
            ['nom' => 'Démangeaisons',       'description' => 'Prurit généralisé ou localisé, souvent chronique.',               'specialite_id' => 7],
            ['nom' => 'Chute de cheveux',    'description' => 'Alopécie progressive ou par plaques.',                            'specialite_id' => 7],
            ['nom' => 'Lésion cutanée suspecte','description' => 'Grain de beauté qui change, plaie qui ne cicatrise pas.',     'specialite_id' => 7],

            // ── Ophtalmologie (8) ────────────────────────────────────────────
            ['nom' => 'Baisse de vision',    'description' => 'Diminution progressive ou brutale de l\'acuité visuelle.',        'specialite_id' => 8],
            ['nom' => 'Douleur oculaire',    'description' => 'Douleur dans ou autour de l\'œil.',                               'specialite_id' => 8],
            ['nom' => 'Yeux rouges',         'description' => 'Conjonctivite ou irritation oculaire.',                           'specialite_id' => 8],
            ['nom' => 'Vision double',       'description' => 'Diplopie, voir deux images au lieu d\'une.',                      'specialite_id' => 8],

            // ── ORL (9) ──────────────────────────────────────────────────────
            ['nom' => 'Maux de gorge',       'description' => 'Angine, pharyngite, douleur à la déglutition.',                  'specialite_id' => 9],
            ['nom' => 'Perte d\'audition',   'description' => 'Hypoacousie, bourdonnements ou sifflements (acouphènes).',        'specialite_id' => 9],
            ['nom' => 'Obstruction nasale',  'description' => 'Nez bouché chronique, sinusite.',                                 'specialite_id' => 9],
            ['nom' => 'Vertiges ORL',        'description' => 'Vertiges liés à l\'oreille interne (vertige positionnel).',       'specialite_id' => 9],

            // ── Gastro-entérologie (10) ──────────────────────────────────────
            ['nom' => 'Douleur abdominale',  'description' => 'Douleurs dans le ventre, crampes ou coliques.',                  'specialite_id' => 10],
            ['nom' => 'Troubles du transit', 'description' => 'Constipation ou diarrhée chronique.',                            'specialite_id' => 10],
            ['nom' => 'Brûlures d\'estomac', 'description' => 'Reflux gastro-œsophagien, acidité gastrique.',                   'specialite_id' => 10],
            ['nom' => 'Vomissements',        'description' => 'Nausées avec vomissements répétés.',                             'specialite_id' => 10],

            // ── Pneumologie (11) ─────────────────────────────────────────────
            ['nom' => 'Toux chronique',      'description' => 'Toux persistant plus de 8 semaines.',                            'specialite_id' => 11],
            ['nom' => 'Essoufflement effort','description' => 'Dyspnée à l\'effort physique modéré.',                           'specialite_id' => 11],
            ['nom' => 'Sifflement respiratoire','description' => 'Wheezing, respiration sifflante (asthme, BPCO).',             'specialite_id' => 11],
            ['nom' => 'Crachats de sang',    'description' => 'Hémoptysie, sang dans les expectorations.',                      'specialite_id' => 11],

            // ── Endocrinologie (12) ──────────────────────────────────────────
            ['nom' => 'Soif excessive',      'description' => 'Polydipsie, signe possible de diabète.',                         'specialite_id' => 12],
            ['nom' => 'Prise de poids rapide','description' => 'Augmentation de poids inexpliquée.',                            'specialite_id' => 12],
            ['nom' => 'Perte de poids rapide','description' => 'Amaigrissement inexpliqué malgré une alimentation normale.',    'specialite_id' => 12],
            ['nom' => 'Intolérance au froid','description' => 'Sensation de froid permanent, signe d\'hypothyroïdie.',          'specialite_id' => 12],

            // ── Rhumatologie (13) ────────────────────────────────────────────
            ['nom' => 'Raideur matinale',    'description' => 'Articulations raides au réveil pendant plus de 30 minutes.',     'specialite_id' => 13],
            ['nom' => 'Douleur musculaire',  'description' => 'Myalgies diffuses, douleurs musculaires persistantes.',          'specialite_id' => 13],
            ['nom' => 'Gonflement articulaire','description' => 'Arthrite avec rougeur et chaleur locale.',                     'specialite_id' => 13],

            // ── Urologie (14) ────────────────────────────────────────────────
            ['nom' => 'Brûlures urinaires',  'description' => 'Dysurie, douleur ou brûlure lors de la miction.',               'specialite_id' => 14],
            ['nom' => 'Urines fréquentes',   'description' => 'Pollakiurie, besoin fréquent d\'uriner.',                        'specialite_id' => 14],
            ['nom' => 'Sang dans les urines','description' => 'Hématurie, urines roses ou rouges.',                             'specialite_id' => 14],
            ['nom' => 'Douleur lombaire urinaire','description' => 'Colique néphrétique, douleur rénale.',                      'specialite_id' => 14],

            // ── Psychiatrie (15) ─────────────────────────────────────────────
            ['nom' => 'Insomnie',            'description' => 'Difficultés à s\'endormir ou à maintenir le sommeil.',           'specialite_id' => 15],
            ['nom' => 'Anxiété',             'description' => 'Inquiétudes persistantes, attaques de panique.',                 'specialite_id' => 15],
            ['nom' => 'Humeur dépressive',   'description' => 'Tristesse profonde, perte d\'intérêt et de motivation.',         'specialite_id' => 15],
            ['nom' => 'Troubles du comportement','description' => 'Changements brusques d\'humeur, agressivité inexpliquée.',   'specialite_id' => 15],
        ];

        DB::table('symptomes')->insert($symptomes);
    }
}