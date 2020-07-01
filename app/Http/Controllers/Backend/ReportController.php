<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Models\Report;

// Temporario, somente para fazer o faker
use \App\Laravue\Faker;
use \App\Laravue\JsonResponse;

class ReportController extends Controller
{
    public function reportLaws(){
    	$rowsNumber = 10;
    $data = [];
    $leis = ['Constituição Federal de 1988', 'Código de Processo Civil', 'Código Processo Penal', 'POLÍCIA MILITAR GOIÁS', 'Técnico Judiciário do TRF3',
              'DEPARTAMENTO PENITENCIÁRIO NACIONAL - DEPEN', 'POLÍCIA CIVIL PARANÁ', 'ASSEMBLEIA LEGISLATIVA CEARÁ', 'POLÍCIA CIVIL DO DISTRITO FEDERAL', 'POLÍCIA MILITAR GOIÁS',
              'TRIBUNAL DE JUSTIÇA DO RIO DE JANEIRO', 'MAGISTRATURA DO TRABALHO', 'POLÍCIA CIVIL DE SÃO PAULO', 'Técnico Judiciário do TRF3', 'Analista Judiciário do TRF3', 'PRF - Policial Rodoviário Federal
', 'Técnico do INSS', 'Oficial de Justiça de São Paulo', 'Agente e Escrivão da Polícia Federal', 'Constituição do Estado do Alagoas', 'Constituição do Estado do Mato Grosso do Sul','Constituição do Estado de Minas Gerais','Constituição do Estado do Paraná','Constituição do Estado de Pernambuco','Constituição do Estado do Rio de Janeiro','​Constituição do Estado do Rio Grande do Sul','Constituição do Estado de Santa Catarina','Constituição do Estado de São Paulo','Código Ética, Estatuto e Regulamento Geral da OAB','Código Civil','Código de Processo Civil','Código Penal','Código Processo Penal','Código Penal Militar','Código Processo Penal Militar','Código Tributário Nacional','Código Trânsito Brasileiro','Código Eleitoral','Código Defesa do Consumidor','Código Ética Caixa Econômica Federal','Código de Conduta para os Funcionários Responsáveis pela Aplicação da Lei'

            ];

    for ($rowIndex = 0; $rowIndex < $rowsNumber; $rowIndex++) {
        $row = [
            'id' => mt_rand(1, 1000),
            'display_time' => Faker::randomDateTime()->format('d/m/Y H:i:s'),
            'title' => $leis[array_rand($leis)],
            'total_pageview' => mt_rand(500, 999999),
            'total_pageview_phone' => mt_rand(200, 777777),
            'total_pageview_app' => mt_rand(150, 777777),
        ];

        $data[] = $row;
    }

    return response()->json(new JsonResponse(['data' => $data, 'total' => mt_rand(1, 100)]));
    }

    public function subsOrCancel(){
      $rowsNumber = 10;
      $data = [];

    for ($rowIndex = 0; $rowIndex < $rowsNumber; $rowIndex++) {
        $row = [
            'id' => mt_rand(1, 100),
            'data' => Faker::randomDateTime()->format('d/m/Y'),
            'qtd_ass' => mt_rand(1, 200),
            'qtd_cancel' => mt_rand(10, 180)
        ];

        $data[] = $row;
    }

    return response()->json(new JsonResponse(['data' => $data, 'total' => mt_rand(1, 100)]));
    }
    
    public function acessHour(){
      $rowsNumber = 10;
      $data = [];

    for ($rowIndex = 0; $rowIndex < $rowsNumber; $rowIndex++) {
        $row = [
            'id' => mt_rand(1, 100),
            'data' => Faker::randomDateTime()->format('d/m/Y'),
            'hour_acess' => Faker::randomDateTime()->format('h') . ' às ' . Faker::randomDateTime()->format('h') ,
            'qtd_ass' => mt_rand(10, 2000)
        ];

        $data[] = $row;
    }

    return response()->json(new JsonResponse(['data' => $data, 'total' => mt_rand(1, 100)]));
    }
   
   public function transactions(){
        $rowsNumber = 10;
        $data = [];
        for ($rowIndex = 0; $rowIndex < $rowsNumber; $rowIndex++) {
            $row = [
                'order_no' => 'aud-' . mt_rand(1000000, 9999999),
                'price' => mt_rand(30, 250),
                'name' => Faker::randomString(mt_rand(5, 10)),
                'date' => Faker::randomDateTime()->format('d/m/Y h:m'),
                'plan' => Faker::randomInArray(['7 dias grátis', 'Mensal', 'Trimestral', 'Semestral', 'Anual']),
                'status' => Faker::randomInArray(['Sucesso', 'Pendente', 'Cancelado']),
            ];

            $data[] = $row;
        }

        return response()->json(new JsonResponse(['data' => $data, 'total' => mt_rand(1, 100)]));
    }
  
    public function subscription(){
        $names = [
        'Helena Domingues',
        'Miguel Santos',
        'Alice Rodrigues',
        'Arthur Pereira',
        'Laura Oliveira',
        'Heitor Marques',
        'Manuela Pereira',
        'Bernardo Nascimento',
        'Valentina Saito',
        'Davi Sousa',
        'Sophia Reis',
        'Théo Reis do Nascimento',
        'Isabella Domingues de Oliveira',
        'Lorenzo Joaquim',
        'Heloísa Guilherme',
        'Gabriel Domingues',
        'Luiza Benício',
        'Pedro Lucca',
        'Júlia Gabriel',
        'Benjamin Henrique',
        'Lorena Henrique',
        'Matheus Henrique',
        'Lívia Henrique',
        'Lucas Vicente',
        ];
        $emails = [
        'Helena@Domingues',
        'Miguel@Santos',
        'Alice@Rodrigues',
        'Arthur@Pereira',
        'Laura@Oliveira',
        'Heitor@Marques',
        'Manuela@Pereira',
        'Bernardo@Nascimento',
        'Valentina@Saito',
        'Davi@Sousa',
        'Sophia@Reis',
        'Théo@Reis@do@Nascimento',
        'Isabella@Domingues@de@Oliveira',
        'Lorenzo@Joaquim',
        'Heloísa@Guilherme',
        'Gabriel@Domingues',
        'Luiza@Benício',
        'Pedro@Lucca',
        'Júlia@Gabriel',
        'Benjamin@Henrique',
        'Lorena@Henrique',
        'Matheus@Henrique',
        'Lívia@Henrique',
        'Lucas@Vicente'];

        $rowsNumber = 10;
        $data = [];
        for ($rowIndex = 0; $rowIndex < $rowsNumber; $rowIndex++) {
            $row = [
                'name' => $names[array_rand($names)],
                'email' => strtolower($emails[array_rand($emails)] . '.com.br'),
                'date' => Faker::randomDateTime()->format('d/m/Y h:m'),
                'plan' => Faker::randomInArray(['7 dias grátis', 'Mensal', 'Trimestral', 'Semestral', 'Anual']),
                'status' => 'Sucesso'
            ];

            $data[] = $row;
        }

        return response()->json(new JsonResponse(['data' => $data, 'total' => mt_rand(1, 100)]));
    }
}
