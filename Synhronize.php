<?php

#Инициализируем финальный бюджет
class Synhronize{
    public function __construct(){
      $finalBudget = 0;
    }

#Получение клиентов ЯДРА
    function getClients() {
        return [
            [
                'id' => 1,
                'name' => 'introverttest',
                'api' => '23bc075b710da43f0ffb50ff9e889aed'
            ],
            [
                'id' => 2,
                'name' => 'artedegrass',
                'api' => 'fafafafas',
            ],
            [
                'id' => 3,
                'name' => 'artefafafaffagrass',
                'api' => 'fafafafas',
            ]
        ];
    }
    
#Проверяем для каждого элемента статус сделки и диапазон начальной и конечной даты
    public function filter($result, $date_start, $date_finish){
        if( $result ){
            for( $i = 0; $i < $result['count']; $i++ ){ 
                if( $result['result'][$i]['status_id'] == 35904058 
                && ( $result['result'][$i]['date_create'] > $date_start && $result['result'][$i]['date_create'] < $date_finish) ){
                    $this->finalBudget = $this->finalBudget + $result['result'][$i]['price'];
                }
            }
        }
    }
#Возвращаем итоговый бюджет по сделкам
    public function getFinalBudget(){
        return $this->finalBudget;
    }

}