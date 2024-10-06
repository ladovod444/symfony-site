<?php

namespace App\Service;

class CheckUniqueText
{

  public function __construct(
    private readonly string $key,
    private $translator // пример подключения сервиса, глянутого через 'symfony console debug:container'
  ){
     // Для $key значение задано в config/services.yaml
    //dd($this->translator);
  }
  public function checkUniqueText(string $text): bool|string|array|int {

    //return false;

    $result = false;

    $post_data = array(
      'key' => $this->key, // ваш ключ доступа (параметр key) со страницы https://content-watch.ru/api/request/
      'text' => $text,
      // 'test' => 0 // при значении 1 вы получите валидный фиктивный ответ (проверки не будет, деньги не будут списаны)
      'test' => 1 // при значении 1 вы получите валидный фиктивный ответ (проверки не будет, деньги не будут списаны)
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_URL, 'https://content-watch.ru/public/api/');
    $result = json_decode(trim(curl_exec($curl)), TRUE);
    curl_close($curl);

    //return $result;

    // TODO нужно обработать ошибки

    return isset($result['percent']) ? (int) $result['percent'] : 0;
  }


}