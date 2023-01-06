<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Program;
use App\Models\Timetable;

use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     * Выводит список всех элементов.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // получение всех программ
        $programs = Program::all();

        return view('admin.pages.list_programs', ['programs' => $programs]);
    }

    /**
     * Show the form for creating a new resource.
     * Добавление программы + добавление расписания.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $r)
    {
        // добавление программы
        $program = Program::create($r->all());

        // если дата и время существуют
        if ($r->day && $r->time) {

            // сопоставления к дням для сортировки
            $days = [
                'Пн' => 1,
                'Вт' => 2,
                'Ср' => 3,
                'Чт' => 4,
                'Пт' => 5,
                'Сб' => 6,
                'Вс' => 7,
            ];

            foreach ($r->day as $k => $v) {

                $day = $v;
                $day_number = $days[$day];
                $time = $r->time[$k];
                $entity_id = $program->id;

                // добавление расписания
                $timetable = Timetable::create(['day' => $day, 'day_number' => $day_number, 'time' => $time, 'entity_id' => $entity_id]);
            }
        }

        // сообщение о результате выполнения операции
        $r->session()->flash('message', 'Программа успешно добавлена.');

        // return redirect()->route('admin.add.program');
        return redirect()->route('admin.list.programs');
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // получение программы
        $program = Program::find($id);
        // получение расписания
        $timetable = Timetable::where('entity_id', $id)->orderBy('day_number')->orderBy('time')->get();

        // костыль для страницы просмотра предварительного результата
        if (url()->current() == route('admin.look.program', ['id' => $id])) {

            // вывод расписсания
            $timetable = DB::table('programs')
                ->where('programs.id', $id)
                ->join('timetables', 'programs.id', 'timetables.entity_id')
                ->select('timetables.day', 'timetables.time', 'programs.name', 'programs.description', 'programs.price')
                ->orderBy('day_number')
                ->orderBy('time')
                ->get();

            return view('admin.looks.program', ['id' => $id, 'program' => $program, 'timetable' => $timetable]);
        }
        
        return view('admin.pages.form_program', ['id' => $id, 'program' => $program, 'timetable' => $timetable]);
    }

    /**
     * Show the form for editing the specified resource.
     * Редактирование программы.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $r, $id)
    {
        // получение программы
        $program = Program::find($id);
        // обновление программы
        $program->update($r->all());

        // если статус "снят с публикации"
        if (!$r->status) {
            // обновление программы
            $program->update(['status' => 0]);
        }

        // очистка расписания для обновляемой программы, чтобы избежать дублей
        $timetable = Timetable::where('entity_id', $id)->delete();

        // если дата и время существуют
        if ($r->day && $r->time) {

            // сопоставления к дням для сортировки
            $days = [
                'Пн' => 1,
                'Вт' => 2,
                'Ср' => 3,
                'Чт' => 4,
                'Пт' => 5,
                'Сб' => 6,
                'Вс' => 7,
            ];

            foreach ($r->day as $k => $v) {

                $day = $v;
                $day_number = $days[$day];
                $time = $r->time[$k];
                $entity_id = $program->id;

                // добавление расписания
                $timetable = Timetable::create(['day' => $day, 'day_number' => $day_number, 'time' => $time, 'entity_id' => $entity_id]);
            }
        }

        // получение созданного расписания
        $timetable = Timetable::where('entity_id', $id)->orderBy('day_number')->orderBy('time')->get();

        // сообщение о результате выполнения операции
        $r->session()->flash('message', 'Программа успешно обновлена.');

        return redirect()->route('admin.control.program', ['id' => $id]);
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
     * Удаление программы + расписания к ней.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r, $id)
    {
        // получение программы
        $program = Program::find($id);
        // получение названия программы
        $program_name = $program->name;
        // удаление программы
        $program->delete();
        // удаление расписания для программы
        $timetable = Timetable::where('entity_id', $id)->delete();

        // сообщение о результате выполнения операции
        $r->session()->flash('message', "Программа \"$program_name\" успешно удалена.");

        return redirect()->route('admin.list.programs');
    }

    public function look()
    {
        // вывод расписсания
        $timetable = DB::table('timetables')
            // ->where('entity_id', $id)
            ->join('programs', 'timetables.entity_id', 'programs.id')
            ->select('timetables.day', 'timetables.time', 'programs.name')
            ->orderBy('day_number')
            ->orderBy('time')
            ->get();

        return view('admin.looks.timetable', ['timetable' => $timetable]);
    }
}
