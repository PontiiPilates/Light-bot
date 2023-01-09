<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Timetable;

use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     * Вывод всех мероприятий.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // получение всех мероприятий
        $events = Event::all();

        return view('admin.pages.events_index', ['events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     * Вывод формы добавления мероприятия.
     * Добавление мероприятия в базу данных.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $r)
    {
        // если get, то вывод формы
        if ($r->isMethod('GET')) {
            return view('admin.pages.event_form');
        }

        // если post, то добавление в базу данных
        if ($r->isMethod('POST')) {

            // добавление мероприятия
            $event = Event::create($r->all());

            // если существует расписание, то добавление расписания
            if ($r->date && $r->time) {

                foreach ($r->date as $k => $v) {

                    $date = $v;
                    $time = $r->time[$k];
                    $entity_id = $event->id;
                    $type = 'event';

                    // добавление расписания
                    $timetable = Timetable::create(['date' => $date, 'time' => $time, 'entity_id' => $entity_id, 'type' => $type]);
                }
            }

            // сообщение о результате выполнения операции
            $r->session()->flash('message', "Мероприятие \"$event->name\" успешно добавлено.");

            return redirect()->route('admin.events.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * Вывод мероприятия.
     * Вывод расписания.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = 'unrequired')
    {
        // если адрес просмотра мероприятия
        if (url()->current() == route('admin.event.show', ['id' => $id])) {

            // получение мероприятия
            $event = Event::find($id);

            // получение расписания
            $timetable = Timetable::where('entity_id', $id)->where('type', 'event')->orderBy('day_number')->orderBy('time')->get();

            return view('admin.pages.event_show', ['id' => $id, 'event' => $event, 'timetable' => $timetable]);
        }

        // если адрес просмотра расписания
        if (url()->current() == route('admin.timetable.events.show')) {

            // получение расписания
            $timetable = DB::table('timetables')
                ->join('events', 'timetables.entity_id', 'events.id')
                ->where('type', 'event')
                ->select('timetables.date', 'timetables.time', 'events.name')
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            return view('admin.pages.events_timetable', ['timetable' => $timetable]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * Вывод формы.
     * Обновление записи в базе данных
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $r, $id)
    {
        // если get, то вывод формы
        if ($r->isMethod('GET')) {

            // получение мероприятия
            $event = Event::find($id);

            // получение расписания
            $timetable = Timetable::where('entity_id', $id)->where('type', 'event')->orderBy('day_number')->orderBy('time')->get();

            return view('admin.pages.event_form', ['id' => $id, 'event' => $event, 'timetable' => $timetable]);
        }

        // если post, то обновление мероприятия в базе данных
        if ($r->isMethod('POST')) {

            // получение мероприятия
            $event = Event::find($id);

            // обновление мероприятия
            $event->update($r->all());

            // дополнительное обновление при отсутствующем статусе
            if (!$r->status) {
                $event->update(['status' => 0]);
            }

            // очистка расписания для обновляемого мероприятия, чтобы избежать дублей
            $timetable = Timetable::where('entity_id', $id)->delete();

            // если существует расписание, то добавление расписания
            if ($r->date && $r->time) {

                foreach ($r->date as $k => $v) {

                    $date = $v;
                    $time = $r->time[$k];
                    $entity_id = $event->id;
                    $type = 'event';

                    // добавление расписания
                    $timetable = Timetable::create(['date' => $date, 'time' => $time, 'entity_id' => $entity_id, 'type' => $type]);
                }
            }

            // сообщение о результате выполнения операции
            $r->session()->flash('message', 'Мероприятие успешно обновлено.');

            return redirect()->route('admin.event.show', ['id' => $event->id]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * Удаление мероприятия.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r, $id)
    {
        // получение мероприятия
        $event = Event::find($id);
        // получение названия мероприятия
        $event_name = $event->name;
        // удаление мероприятия
        $event->delete();
        // удаление расписания для мероприятия
        $timetable = Timetable::where('entity_id', $id)->where('type', 'event')->delete();

        // сообщение о результате выполнения операции
        $r->session()->flash('message', "Программа \"$event_name\" успешно удалена.");

        return redirect()->route('admin.events.index');
    }
}