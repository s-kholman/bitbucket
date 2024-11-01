<?php

namespace App\Http\Controllers;

use App\Actions\ApiApprove;
use App\Actions\ApiBooking;
use App\Actions\Barcode;
use App\Models\TaskFirst;
use App\Models\TaskTickedBarcode;
use App\Models\TicketBarcode;
use App\Models\TicketType;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class TaskTicketBarcodeController extends Controller
{
    public function index(Barcode $barcode, ApiApprove $apiApprove, ApiBooking $apiBooking)
    {
        //пришедшие данные
        $faker = Factory::create('ru_RU');
        $data = [
            'event_two_id' => rand(1, 30),
            'event_date' => $faker->date('d-m-Y'),
            'ticket_adult_price' => rand(1000, 9999),
            'ticket_adult_quantity' => rand(1, 3),
            'ticket_kid_price' => rand(500, 999),
            'ticket_kid_quantity' => rand(1, 2),
            'ticked_group_price' => rand(500, 999),
            'ticked_group_quantity' => rand(1, 2),
            'ticked_preferential_price' => rand(500, 999),
            'ticked_preferential_quantity' => rand(1, 2),
            'equal_price' => 5000,
            'created' => now(),
        ];

        dump($data);

        try {
            DB::beginTransaction();

            $model = TaskTickedBarcode::query()  //подготовили данные для сохранения
            ->create([
                'event_two_id' => $data['event_two_id'],
                'event_date' => $data['event_date'],
                'equal_price' => $data['equal_price'],
                'created' => now(),
            ]);

            //Проверяем ответ
            DB::commit(); //положительно сохраняем
        } catch (\Exception $exception) {
            DB::rollBack(); // отменяем текущею транзакции
            return ['error' => $exception];
        }


                $ticket_type = TicketType::all();

                foreach ($ticket_type as $type) {

                    if (array_key_exists($type->type . '_quantity', $data)) {
                        for ($x = 1; $x <= $data[$type->type . '_quantity']; ++$x) {
                            while (true){
                                $check_barcode = $barcode(); //генерируем barcode
                                $check = TaskFirst::query()
                                    ->where('barcode', $check_barcode)
                                    ->get();
                                if ($check->isEmpty()) // если в таблице нет такой записи продолжаем
                                {
                                    dump($check_barcode);
                                    //Запрос к API 1
                                    $booking_check = $apiBooking(
                                        $data['event_two_id'],
                                        $data['event_date'],
                                        $data['ticket_adult_price'],
                                        $data['ticket_adult_quantity'],
                                        $data['ticket_kid_price'],
                                        $data['ticket_kid_quantity'],
                                        $barcode());
                                    if (array_key_exists('message', $booking_check)) {
                                        break;
                                    } else{
                                        dump($booking_check['error'] . ' Повторная генерация barcode');
                                    }
                                }

                            }
                            //Запрос к api barcode

                            $check_approve = $apiApprove($check_barcode);

                                if (array_key_exists('message', $check_approve)) {
                                    //dd($check_barcode);
                                    TicketBarcode::query()
                                        ->create([
                                            'task_ticked_barcode_id' => $model->id,
                                            'ticket_type_id' => $type->id,
                                            'ticket_price' => $data[$type->type . '_price'],
                                            'barcode' => $check_barcode
                                        ]);
                                } else {
                                    dump($check_approve['error']. '  ' . 'Отмена билета на последнем API');
                                }
                            }
                    }
                }

            dd($model->ticketBarcode); //Созданные билеты

        }
}
