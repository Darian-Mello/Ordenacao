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

    //add outras funcoes com outros alg de ordenacao

    function carregaVetor () { 
        try {    
            ini_set('memory_limit', '3G'); 
            global $A;
            
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
                    switch ($t['month']) {
                        case 'January' :
                            array_push($tripulanes_por_mes['January'], $temp);
                            break;
                        case 'February' :
                            array_push($tripulanes_por_mes['February'], $temp);
                            break;
                        case 'March' :
                            array_push($tripulanes_por_mes['March'], $temp);
                            break;
                        case 'April' :
                            array_push($tripulanes_por_mes['April'], $temp);
                            break;
                        case 'May' :
                            array_push($tripulanes_por_mes['May'], $temp);
                            break;
                        case 'June' :
                            array_push($tripulanes_por_mes['June'], $temp);
                            break;
                        case 'July' :
                            array_push($tripulanes_por_mes['July'], $temp);
                            break;
                        case 'August' :
                            array_push($tripulanes_por_mes['August'], $temp);
                            break;
                        case 'September' :
                            array_push($tripulanes_por_mes['September'], $temp);
                            break;
                        case 'October' :
                            array_push($tripulanes_por_mes['October'], $temp);
                            break;
                        case 'November' :
                            array_push($tripulanes_por_mes['November'], $temp);
                            break;
                        case 'December' :
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
                        
                        // Quick Sort
                        $A = $t;
                        $start_quick_sort = microtime(true);
                        quickSort(0, count($A)-1);
                        $end_quick_sort = microtime(true);
                        $execution_time_quick_sort = ($end_quick_sort - $start_quick_sort);

                        //Aqui vai a chamada de outras funcoes com outros alg de ordenacao
                        break;
                    }
                }

                $impostor = 1000001 - $count_logs;
                $impostor--;
                echo '<p>Tempo para carregar o arquivo: ' . round($execution_time_carregar_arquivo, 3) . ' segundos;</p>';
                echo '<p>Tempo para separar os tripulantes por mês: ' . round($execution_time_separa_trip_mes, 3) . ' segundos;</p>';
                echo '<p>----------------------------------------------------------------------------------------------------------------------------------------</p>';
                echo '<p>De acordo com o Quick Sort, o impostor é:</p>';
                echo '<p>User: <b>' . $A[$impostor]['user'] . ';</b></p>';
                echo '<p>Log: <b>' . $A[$impostor]['log'] . ';</b></p>';
                echo '<p>Tempo para ordenação: ' . round($execution_time_quick_sort, 3) . ' segundos;</p>';
                echo '<p>----------------------------------------------------------------------------------------------------------------------------------------</p>';
                //Aqui vai a exibicao de outros alg de ordenacao
            }
        } catch (Exception $e) {
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
    }
    carregaVetor();
?>