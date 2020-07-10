<?php

use Illuminate\Database\Seeder;

class LawTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $laws = [
            [
                'name' => 'Teste',
                'type' => 'folder',
                'path' => 'leis',
                'size' => 0,
            ],
            [
                'name' => 'Teste 2',
                'type' => 'folder',
                'path' => 'leis',
                'size' => 0,

                'children' => [
                    [    
                        'name' => 'asdf.png',
                        'type' => 'file',
                        'path' => 'leis/Teste 2',
                        'size' => 0,
                    ],
                ],
            ],
            [
                'name' => 'gc_CGN.pdf',
                'type' => 'file',
                'path' => 'leis',
                'size' => 0,
            ],
        ];
        foreach($laws as $law)
        {
            \App\Laravue\Models\Law::create($law);
        }
    }
}
