<?php 
    $A = null;
    function ordenaEparticona ($p, $r) {
        global $A;
        $pivo = $A[$r];
        $i = $p - 1; 
    
        for ($j = $p; $j < $r; $j++) {
            if ($A[$j]['log'] <= $pivo['log']) {
                $i = $i + 1;
                $aux = $A[$i];
                $A[$i] = $A[$j];
                $A[$j] = $aux;
            }
        }
        $i = $i + 1;
        $aux = $A[$i];
        $A[$i] = $A[$r];
        $A[$r] = $aux;
        return $i;
    }
    function quickSort ($ini, $fim) {
        $p;
        if ($ini <= $fim) {
            $p = ordenaEparticona($ini, $fim);
            quickSort($ini, $p - 1);
            quickSort($p + 1, $fim);
        }
    }

    function countingSort ($tripulantes) {
        $maior = $tripulantes[0]['log'];
        for ($i = 0; $i < count($tripulantes); $i++) {
            if($tripulantes[$i]['log'] > $maior) {
                $maior = $tripulantes[$i]['log'];
            }
        }

        $freq = [];
        for ($i = 0; $i < $maior+1; $i++) {
            $freq[$i] = [];
        }

        for ($i = 0; $i < count($tripulantes); $i++) {
            array_push($freq[$tripulantes[$i]['log']], $tripulantes[$i]);
        }

        $resposta = [];
        for ($i = 0; $i < count($freq); $i++) {
            for ($j = 0; $j < count($freq[$i]); $j++) {
                array_push($resposta, $freq[$i][$j]);
            }
        }
        return $resposta;
    }

    function carregaVetor () { 
        try {    
            ini_set('memory_limit', '3G'); 
            
            $start_leitura = microtime(true);
            $tripulantes = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', file_get_contents('logNaveSbornia.txt', true)), true);
            $end_leitura = microtime(true);
            $execution_time_carregar_arquivo = ($end_leitura - $start_leitura);

            $tripulanes_por_mes = array(
                'January' => [],
                'February' => [],
                'March' => [],
                'April' => [],
                'May' => [],
                'June' => [],
                'July' => [],
                'August' => [],
                'September' => [],
                'October' => [],
                'November' => [],
                'December' => []
            );
            
            if ($tripulantes[0]) {
                $start_separa = microtime(true);
                foreach ($tripulantes as $t) {
                    $temp = array(
                        'log' => $t['log'],
                        'user' => $t['user']
                    );
                    switch (strtolower($t['month'])) {
                        case 'january' :
                            array_push($tripulanes_por_mes['January'], $temp);
                            break;
                        case 'february' :
                            array_push($tripulanes_por_mes['February'], $temp);
                            break;
                        case 'march' :
                            array_push($tripulanes_por_mes['March'], $temp);
                            break;
                        case 'april' :
                            array_push($tripulanes_por_mes['April'], $temp);
                            break;
                        case 'may' :
                            array_push($tripulanes_por_mes['May'], $temp);
                            break;
                        case 'june' :
                            array_push($tripulanes_por_mes['June'], $temp);
                            break;
                        case 'july' :
                            array_push($tripulanes_por_mes['July'], $temp);
                            break;
                        case 'august' :
                            array_push($tripulanes_por_mes['August'], $temp);
                            break;
                        case 'september' :
                            array_push($tripulanes_por_mes['September'], $temp);
                            break;
                        case 'october' :
                            array_push($tripulanes_por_mes['October'], $temp);
                            break;
                        case 'november' :
                            array_push($tripulanes_por_mes['November'], $temp);
                            break;
                        case 'december' :
                            array_push($tripulanes_por_mes['December'], $temp);
                            break;
                    }
                }
                $end_separa = microtime(true);
                $execution_time_separa_trip_mes = ($end_separa - $start_separa);

                $count_logs = 0;
                foreach ($tripulanes_por_mes as $t) {
                    $count_logs += count($t);
                    if ($count_logs >= 1000001) {
                        $count_logs -= count($t);
                        $posicao_impostor = 1000001 - $count_logs;
                        $posicao_impostor--;
                        
                        echo '<p>Tempo para carregar o arquivo: ' . round($execution_time_carregar_arquivo, 3) . ' segundos;</p>';
                        echo '<p>Tempo para separar os tripulantes por mês: ' . round($execution_time_separa_trip_mes, 3) . ' segundos;</p>';
                        echo '<p>----------------------------------------------------------------------------------------------------------------------------------------</p>';

                        $resposta = array (
                            'possiveis_impostores' => $t,
                            'posicao_impostor' => $posicao_impostor 
                        );
                        return $resposta;
                    }
                }
                return null;
            }
        } catch (Exception $e) {
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }

    function index () {
        try {
            $possiveis_impostores = carregaVetor();
            if ($possiveis_impostores['possiveis_impostores'][0] && $possiveis_impostores['posicao_impostor'] != null) {
                $posicao_impostor = $possiveis_impostores['posicao_impostor'];

                // Counting Sort
                $start_counting_sort = microtime(true);
                $ordenadoPorCS = countingSort($possiveis_impostores['possiveis_impostores']);
                $end_counting_sort = microtime(true);
                $execution_time_counting_sort = ($end_counting_sort - $start_counting_sort);

                echo '<p>De acordo com o Counting Sort, o impostor é:</p>';
                echo '<p>User: <b>' . $ordenadoPorCS[$posicao_impostor]['user'] . ';</b></p>';
                echo '<p>Log: <b>' . $ordenadoPorCS[$posicao_impostor]['log'] . ';</b></p>';
                echo '<p>Tempo para ordenação: ' . round($execution_time_counting_sort, 3) . ' segundos;</p>';
                echo '<p>----------------------------------------------------------------------------------------------------------------------------------------</p>';

                // Quick Sort
                global $A;
                $A = $possiveis_impostores['possiveis_impostores'];
                $start_quick_sort = microtime(true);
                quickSort(0, count($A)-1);
                $end_quick_sort = microtime(true);
                $execution_time_quick_sort = ($end_quick_sort - $start_quick_sort);

                echo '<p>De acordo com o Quick Sort, o impostor é:</p>';
                echo '<p>User: <b>' . $A[$posicao_impostor]['user'] . ';</b></p>';
                echo '<p>Log: <b>' . $A[$posicao_impostor]['log'] . ';</b></p>';
                echo '<p>Tempo para ordenação: ' . round($execution_time_quick_sort, 3) . ' segundos;</p>';
                echo '<p>----------------------------------------------------------------------------------------------------------------------------------------</p>';
                
            } else {
                echo 'Houve algum erro ao carregar os possíveis impostores.';
            }
        } catch (Exception $e) {
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }
    index();
?>