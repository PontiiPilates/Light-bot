<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// сервис-контейнер
use App\Services\TelegramService;
// модель хранения входящих запросов
use App\Models\IncomingMessage;
// для отладки
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

class SquirrelKidsBotController extends Controller
{
    // сервис-контейнер
    private $telegram_service;

    /**
     * Внедрение зависимости через конструктор класса
     */
    public function __construct(TelegramService $telegram_service)
    {
        $this->telegram_service = $telegram_service;
    }

    /**
     * Webhook routing
     * Распознаёт полученные данные и отправляет соответствующие ответы
     * @param r object объект класса Request
     * @return mixed возвращает результат работы вызываемых методов
     */
    public function webhook(Request $r)
    {
        // Log::debug($r->all());

        // подготовка данных
        $bot_name = 'SquirrelKidsBot'; // название бота
        $update_id = $r->input('update_id'); // обновление
        $message_id = $r->input('message.message_id'); // идентификатор сообщения
        $from_id = $r->input('message.from.id'); // идентификатор отправителя
        $first_name = $r->input('message.from.first_name'); // имя отправителя
        $last_name = $r->input('message.from.last_name'); // фамилия отправителя
        $username = $r->input('message.from.username'); // ник отправителя
        $chat_id = $r->input('message.chat.id'); // идентификатор чата
        $date = $r->input('message.date'); // временная метка отправления
        $text = $r->input('message.text'); // текст сообщения
        $callback_data = $r->input('callback_query.data'); // callback-команда
        $callback_message_id = $r->input('callback_query.message.message_id'); // идентификатор callback-сообщения
        $callback_chat_id = $r->input('callback_query.message.chat.id'); // идентификатор callback-чата
        $mark = 0; // числовой маркер
        $notes = 'some'; // текстовая метка

        // сохранение входящего сообщения (для аналитики)
        $incoming_message = IncomingMessage::create(
            [
                'bot_name' => $bot_name,
                'update_id' => $update_id,
                'message_id' => $message_id,
                'from_id' => $from_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'username' => $username,
                'chat_id' => $chat_id,
                'date' => $date,
                'text' => $text,

                'callback_data' => $callback_data,
                'callback_message_id' => $callback_message_id,
                'callback_chat_id' => $callback_chat_id,

                'mark' => $mark,
                'notes' => $notes,
            ]
        );

        if ($text == '/start') {
            // пользователь стартовал бот:
            $this->telegram_service->send_message($chat_id, 'Добро пожаловать в Сквирел-бот! Пользуйтесь кнопкой "Меню" для навигации.');
        } elseif ($text == '/timetable') {
            // пользователь выбрал пункт меню:
            $view = (string) view('Telegram.timetable');
            $this->telegram_service->send_message($chat_id, $view);
        } elseif ($text == '/promotions') {
            // пользователь выбрал пункт меню:
            $view = (string) view('Telegram.promotions');
            $this->telegram_service->send_message($chat_id, $view);
        } elseif ($text == '/programs') {
            // пользователь выбрал пункт меню:
            $view = (string) view('Telegram.programs');
            $this->telegram_service->send_message($chat_id, $view);
        } elseif ($text == '/events') {
            $view = (string) view('Telegram.events');
            $this->telegram_service->send_message($chat_id, $view);
        } elseif ($text == '/about') {
            $view = (string) view('Telegram.about');
            $this->telegram_service->send_message($chat_id, $view);
        } else {
            $this->telegram_service->send_message($chat_id, 'Не знаю такой команды, но скоро меня обучат.');
        }
    }

    /**
     * Установка вебхука
     */
    public function set()
    {
        $http = $this->telegram_service->push_get('setWebhook', ['url' => 'https://light-bot.ru/tg/6bf533cfeff238e0d7d265a69a7018ad/webhook']);
        dd(json_decode($http));
    }

    /**
     * Удаление вебхука
     */
    public function delete()
    {
        $http = $this->telegram_service->push_get('deleteWebhook', ['url' => 'drop_pending_updates']);
        dd(json_decode($http));
    }

    /**
     * Получение информации о работе вебхука
     */
    public function info()
    {
        $http = $this->telegram_service->push_get('getWebhookInfo');
        dd(json_decode($http));
    }

    public function admin()
    {
        $people_started = IncomingMessage::select('from_id')->where('text', '/start')->groupBy('from_id')->get()->count();

        $messages = IncomingMessage::whereNotIn('text', ['/start', '/timetable', '/programs', '/events', '/promotions', '/about'])->get();

        $text = [];
        foreach ($messages as $v) {
            $text[] = [
                'text' => $v->text,
                'from_id' => $v->from_id,
                'created_at' => $v->created_at,
            ];
        }

        // dd($messages);

        $data = [
            'people_started' => $people_started,
            'messages' => $text,
        ];

        // return view('Admin.Pages.info', ['data' => $data]);
        return view('admin.pages.admin_home', ['data' => $data]);
    }
}
