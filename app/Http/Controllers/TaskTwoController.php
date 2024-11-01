<?php

namespace App\Http\Controllers;

use App\Actions\ApiApprove;
use App\Actions\ApiBooking;
use App\Actions\Barcode;
use App\Models\TaskFirst;
use App\Models\TaskTwo;
use App\Models\TaskTwoTicket;
use App\Models\TicketType;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskTwoController extends Controller
{
    /**
     * @throws \Exception
     */
    public function index(Barcode $barcode, ApiApprove $apiApprove, ApiBooking $apiBooking)
    {
        //пришедшие данные
        $faker = Factory::create('ru_RU');
        $data = [
            'event_two_id' => rand(1,30),
            'event_date' => $faker->date('d-m-Y'),
            'ticket_adult_price' => rand(1000, 9999),
            'ticket_adult_quantity' => rand(1,2),
            'ticket_kid_price' => rand(500, 999),
            'ticket_kid_quantity' => rand(1,2),
            'ticked_group_price' => rand(500, 999),
            'ticked_group_quantity' => rand(1,2),
            'ticked_preferential_price' => rand(500, 999),
            'ticked_preferential_quantity' => rand(1,2),
            'equal_price' => 5000,
            'created' => now(),
        ];
        //Используем retry для повторов, можно использовать и любой другой цикл
        $f = retry(225, function () use ($barcode, $data, $apiApprove, $apiBooking) {
            $check_barcode = $barcode(); //генерируем barcode
            dump($check_barcode);
            $check = TaskFirst::query()
                ->where('barcode', $check_barcode)
                ->get();
            if ($check->isEmpty()) // если в таблице нет такой записи продолжаем
            {
                try {
                    DB::beginTransaction(); // работаем через транзакции в ручную

                    $model = TaskTwo::query()  //подготовили данные для сохранения
                    ->create([
                        'event_two_id' => $data['event_two_id'],
                        'event_date' => $data['event_date'],
                        'barcode' => $check_barcode,
                        'equal_price' => $data['equal_price'],
                        'created' => now(),
                    ]);
                    //Запрос к API
                    $booking_check = $apiBooking($data['event_two_id'], $data['event_date'], $data['ticket_adult_price'], $data['ticket_adult_quantity'], $data['ticket_kid_price'], $data['ticket_kid_quantity'], $barcode());
                    //Проверяем ответ

                    if (array_key_exists('message', $booking_check)){
                        //Запрос к api barcode

                        $check_approve = $apiApprove($check_barcode);
                        if (array_key_exists('message', $check_approve)){
                            DB::commit(); //положительно сохраняем
                            //запрос всех обрабатываемых билетов
                            $ticket_type = TicketType::all();

                            foreach ($ticket_type as $type){

                                if (array_key_exists($type->type.'_quantity', $data)){

                                    TaskTwoTicket::query()
                                        ->create([
                                            'ticket_type_id' => $type->id,
                                            'task_two_id' => $model->id,
                                            'ticket_price' => $data[$type->type.'_price'],
                                            'ticket_quantity' => $data[$type->type.'_quantity'],
                                        ]);
                                }
                            }
                            return $model;
                        }else {
                            DB::rollBack(); //ошибка отменяем все
                            return $check_approve['error'];
                        }
                    } else {
                        throw new \Exception(); //вызываем исключение
                    }

                } catch (\Exception $exception){
                    DB::rollBack(); // отменяем текущею транзакции
                }
            } else {
                throw new \Exception(); //если запись присутствует генерируем исключение и цикл повторяется
            }
            throw new \Exception(); // т.к. мы не вышли по ошибке в последнем запросе к API, повторяем попытку
        });

        if (gettype($f) == 'string')
        {
            dd($f); //Ошибка из последнего API
        }
        else {
            dump($f);
            dd($f->taskTwoTicket); //Созданные билеты
        }
    }


}
