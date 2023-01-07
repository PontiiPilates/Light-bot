<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Program;
use App\Models\Timetable;

use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    /**
     * Вывод всех программ.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = Program::all();
        return view('admin.pages.programs_index', ['programs' => $programs]);
    }

    /**
     * Вывод формы для добавления расписания.
     * Добавление расписания в базу данных.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $r)
    {
        // если get, то вывод формы
        if ($r->isMethod('GET')) {
            return view('admin.pages.program_form');
        }

        // если post, то добавление в базу данных
        if ($r->isMethod('POST')) {

            // добавление программы
            $program = Program::create($r->all());

            // если если существует расписание, то добавление расписания
            if ($r->day && $r->time) {

                // сопоставления для возможности сортировки по дням
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
            $r->session()->flash('message', "Программа \"$program->name\" успешно добавлена.");

            return redirect()->route('admin.programs.index');
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
     * Вывод программы.
     * Вывод расписания.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = 'unrequired')
    {

        // если адрес просмотра программы
        if (url()->current() == route('admin.program.show', ['id' => $id])) {

            // получение программы
            $program = Program::find($id);

            // получение расписания
            $timetable = Timetable::where('entity_id', $id)->orderBy('day_number')->orderBy('time')->get();

            return view('admin.pages.program_show', ['id' => $id, 'program' => $program, 'timetable' => $timetable]);
        }

        // если адрес просмотра расписания
        if (url()->current() == route('admin.timetable.show')) {

            // получение расписания
            $timetable = DB::table('timetables')
                ->join('programs', 'timetables.entity_id', 'programs.id')
                ->select('timetables.day', 'timetables.time', 'programs.name')
                ->orderBy('day_number')
                ->orderBy('time')
                ->get();

            return view('admin.pages.timetable_show', ['timetable' => $timetable]);
        }
    }

    /**
     * Редактирование программы.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $r, $id)
    {

        // если get, то вывод формы
        if ($r->isMethod('GET')) {

            // получение программы
            $program = Program::find($id);

            // получение расписания
            $timetable = Timetable::where('entity_id', $id)->orderBy('day_number')->orderBy('time')->get();

            return view('admin.pages.program_form', ['id' => $id, 'program' => $program, 'timetable' => $timetable]);
        }

        // если post, то обновление программы в базе данных
        if ($r->isMethod('POST')) {

            // получение программы
            $program = Program::find($id);

            // обновление программы
            $program->update($r->all());

            // дополнительное обновление при отсутствующем статусе
            if (!$r->status) {
                $program->update(['status' => 0]);
            }

            // очистка расписания для обновляемой программы, чтобы избежать дублей
            $timetable = Timetable::where('entity_id', $id)->delete();

            // сопоставления для возможности сортировки по дням
            if ($r->day && $r->time) {

                // сопоставления для возможности сортировки по дням
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
            $r->session()->flash('message', 'Программа успешно обновлена.');

            return redirect()->route('admin.program.show', ['id' => $program->id]);
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
     * Удаление программы.
     * Удаление расписания к программе.
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

        return redirect()->route('admin.programs.index');
    }
}
