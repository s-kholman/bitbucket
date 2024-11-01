<?php

namespace App\Http\Controllers;

use App\Actions\Barcode;
use App\Models\TaskFirst;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class TaskFirstController extends Controller
{
private array $post_data;

public function __construct()
{
    $faker = Factory::create('ru_RU');
    $this->post_data = [
        'event_id' => rand(1,30),
        'event_date' => $faker->date('d-m-Y'),
        'ticket_adult_price' => rand(1000, 9999),
        'ticket_adult_quantity' => rand(1,2),
        'ticket_kid_price' => rand(500, 999),
        'ticket_kid_quantity' => rand(1,2),
    ];
}

    public function index(Barcode $barcode)
    {
        //пришедшие данные
        $data = $this->post_data;

        //Используем retry для повторов, можно использовать и любой другой цикл
        $f = retry(225, function () use ($barcode, $data) {
            $check_barcode = $barcode(); //генерируем barcode
            dump($check_barcode);
            $check = TaskFirst::query()
                ->where('barcode', $check_barcode)
                ->get();
            if ($check->isEmpty()) // если в таблице нет такой записи продолжаем
            {
                try {
                    DB::beginTransaction(); // работаем через транзакции

                    $model = TaskFirst::query()  //подготовили данные для сохранения
                    ->create([
                        'event_id' => $data['event_id'],
                        'event_date' => $data['event_date'],
                        'ticket_adult_price' => $data['ticket_adult_price'],
                        'ticket_adult_quantity' => $data['ticket_adult_quantity'],
                        'ticket_kid_price' => $data['ticket_kid_price'],
                        'ticket_kid_quantity' => $data['ticket_kid_quantity'],
                        'barcode' => $check_barcode,
                        'equal_price' => 5000,
                        'created' => now(),
                    ]);
                    //Запрос к API
                    $booking_check = $this->booking($data['event_id'], $data['event_date'], $data['ticket_adult_price'], $data['ticket_adult_quantity'], $data['ticket_kid_price'], $data['ticket_kid_quantity'], $barcode());
                    //Проверяем ответ
                    if (array_key_exists('message', $booking_check)){
                        //Запрос к api barcode
                        $check_approve = $this->approve($check_barcode);
                        if (array_key_exists('message', $check_approve)){
                            DB::commit(); //положительно сохраняем
                            return $model;
                        }else {
                            DB::rollBack(); //ошибка отменяем все
                            return $check_approve['error'];
                        }

                    } else {
                        throw new \Exception(); //вызываем исключение
                    }

                } catch (\Exception){
                    DB::rollBack(); // отменяем текущею транзакции
                }
            } else {
                throw new \Exception(); //если запись присутствует генерируем исключение и цикл повторяется
            }
            throw new \Exception(); // т.к. мы не вышли по ошибке в последнем API, повторяем попытку
        });
        dd($f);
    }



    public function booking($event_id, $event_date, $ticket_adult_price, $ticket_adult_quantity, $ticket_kid_price, $ticket_kid_quantity, $barcode)
    {
        if (rand(0, 1)){
            return ['message'=> 'order successfully booked'];
        } else {
            return ['error' => 'barcode already exists'];
        }
    }

    public function approve($barcode)
    {
        $return [] = ['message' => 'order successfully aproved'];
        $return [] = ['error' => 'event cancelled'];
        $return [] = ['error' => 'no tickets'];
        $return [] = ['error' => 'no seats'];
        $return [] = ['error' => 'fan removed'];

        return $return[rand(0,4)];
    }

}
