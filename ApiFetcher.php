<?php
#Убираем лимит на выполнение цикла, т.к. ответ от сервера превышает дефолтное время в 30 сек
set_time_limit(0);

require_once('vendor/autoload.php');
require_once('Synhronize.php');

#Получаем дату из формы и преобразуем ее в формат Timestamp
$dateStart = $_GET['date_start'];
$dateFinish = $_GET['date_finish'];

$timeStr = '00:00';
list($hours, $minutes) = explode(':', $timeStr);

$dateTime = \DateTime::createFromFormat('m/d/Y', $dateStart)->setTime($hours, $minutes);
$dateTime2 = \DateTime::createFromFormat('m/d/Y', $dateFinish)->setTime($hours, $minutes);
$dateStartTimestamp = $dateTime->getTimestamp();
$dateFinishTimestamp = $dateTime2->getTimestamp();
#

$synhronize = new Synhronize();
$clients = $synhronize->getClients();
$table = [];
$finalAmount;

#Начинаем обход клиентов ЯДРА
foreach ($clients as $client)  
{
    Introvert\Configuration::getDefaultConfiguration()->setApiKey('key', $client['api']);
    $api = new Introvert\ApiClient();

    try {
        $result = $api->account->infoWithHttpInfo();

#Проверяем есть ли клиент с таким ключем API, если да, то для него подсчитываем сумму всех успешных сделок
if($result[0]['result']){
            
    $count = 250;
    $offset = 0;

    while(true){
            try {
            $result = $api->lead->getAll(null, null, null, null, $count, $offset);
            $synhronize->filter($result, $dateStartTimestamp, $dateFinishTimestamp);
    } catch (Exception $e) {
        echo 'Exception when calling lead->getAll: ', $e->getMessage(), PHP_EOL;
    }
$offset = $offset + 250;

        if($result['count']<$count){
#Помещаем данные от клиента в итоговый ассоциативный массив
            $key = array ('id','name','amount');
            $value = array (strval($client['id']), strval($client['name']), $synhronize->getFinalBudget());
            $element = array();
                for ($i = 0; $i<count($key); $i++) {
                    $element[$key[$i]] = $value[$i];
                }
            $table[] = $element;
            
            
            break;
        }
}       
#Считаем итоговую сумму по клиентам
$finalAmount += $synhronize->getFinalBudget();

#Если это не клиент ЯДРА, то все равно закидываем его в итоговый массив, но вместо суммы сделок дописываем 0
}
}catch (Exception $e) {
        $key = array ('id','name','amount');
        $value = array (strval($client['id']), strval($client['name']), 'Аккаунт не действителен');
        $element = array();
        for ($i = 0; $i<count($key); $i++) {
            $element[$key[$i]] = $value[$i];
        }
        $table[] = $element;
    }

}
require_once('index.html');






    

