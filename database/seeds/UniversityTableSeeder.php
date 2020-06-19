<?php

use Illuminate\Database\Seeder;
use App\Laravue\Models\University;

class UniversityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $fag = University::create([
            'fantasy_name' => 'FAG',
            'social_reason' => 'Centro Universitário da Fundação Assis Gurgacz',
            'nickname' => 'FAG',
            'cnpj' => '66.864.056/0001-00',
            'telephone' => '45 3030-3030',
            'email' => 'contato@fag.com.br',
            'rector' => 'Fulano Beltrano',
             'state_id' => 18,
             'city_id' => 3994
        ]);
    $unopar = University::create([
            'fantasy_name' => 'Unopar',
            'social_reason' => 'Kroton Educacional S.A.',
            'nickname' => 'Unopar',
            'cnpj' => '66.864.056/0001-00',
            'telephone' => '45 3030-3030',
            'email' => 'contato@unopar.com.br',
            'rector' => 'Fulano Beltrano',
             'state_id' => 18,
             'city_id' => 3994
        ]);
    $unipar = University::create([
            'fantasy_name' => 'Unipar',
            'social_reason' => 'Universidade Paranaense',
            'nickname' => 'Unipar',
            'cnpj' => '66.864.056/0001-00',
            'telephone' => '45 3030-3030',
            'email' => 'contato@unipar.com.br',
            'rector' => 'Fulano Beltrano',
             'state_id' => 18,
             'city_id' => 3994
        ]);
    $cesumar = University::create([
            'fantasy_name' => 'Unicesumar',
            'social_reason' => 'Universidade Cesumar',
            'nickname' => 'Unicesumar',
            'cnpj' => '66.864.056/0001-00',
            'telephone' => '45 3030-3030',
            'email' => 'contato@unicesumar.com.br',
            'rector' => 'Fulano Beltrano',
             'state_id' => 18,
             'city_id' => 3994
        ]);
    $unioeste =  University::create([
            'fantasy_name' => 'Unioeste',
            'social_reason' => 'Universidade Estadual do Oeste do Paraná',
            'nickname' => 'Unioeste',
            'cnpj' => '66.864.056/0001-00',
            'telephone' => '45 3030-3030',
            'email' => 'contato@unicesumar.com.br',
            'rector' => 'Fulano Beltrano',
             'state_id' => 18,
             'city_id' => 3994
        ]);
    }
}
