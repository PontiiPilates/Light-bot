<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Promotion;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     * Вывод всех акций.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // получение всех акций
        $promotions = Promotion::all();

        return view('admin.pages.promotions_index', ['promotions' => $promotions]);
    }

    /**
     * Show the form for creating a new resource.
     * Вывод формы.
     * Добавление в базу данных.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $r)
    {
        // если get, то вывод формы
        if ($r->isMethod('GET')) {
            return view('admin.pages.promotion_form');
        }

        // если post, то добавление в базу данных
        if ($r->isMethod('POST')) {

            // добавление акции
            $promotion = Promotion::create($r->all());

            // сообщение о результате выполнения операции
            $r->session()->flash('message', "Акция \"$promotion->name\" успешно добавлена.");

            return redirect()->route('admin.promotions.index');
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = 'unrequired')
    {
        // если адрес просмотра акции
        if (url()->current() == route('admin.promotion.show', ['id' => $id])) {

            // получение акции
            $promotion = Promotion::find($id);

            return view('admin.pages.promotion_show', ['id' => $id, 'promotion' => $promotion]);
        }

        // если адрес просмотра акций
        if (url()->current() == route('admin.promotions.show')) {

            // получение акций
            $promotions = Promotion::where('status', 1)->get();

            return view('admin.pages.promotions_show', ['promotions' => $promotions]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $r, $id)
    {
        // если get, то вывод формы
        if ($r->isMethod('GET')) {

            // получение акции
            $promotion = Promotion::find($id);

            return view('admin.pages.promotion_form', ['id' => $id, 'promotion' => $promotion]);
        }

        // если post, то обновление записи в базе данных
        if ($r->isMethod('POST')) {

            // получение акции
            $promotion = Promotion::find($id);

            // обновление акции
            $promotion->update($r->all());

            // дополнительное обновление при отсутствующем статусе
            if (!$r->status) {
                $promotion->update(['status' => 0]);
            }

            // сообщение о результате выполнения операции
            $r->session()->flash('message', 'Акция успешно обновлена.');

            return redirect()->route('admin.promotion.show', ['id' => $promotion->id]);
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $r, $id)
    {
        // получение акции
        $promotion = Promotion::find($id);
        // получение названия акции
        $promotion_name = $promotion->name;
        // удаление акции
        $promotion->delete();

        // сообщение о результате выполнения операции
        $r->session()->flash('message', "Акция \"$promotion_name\" успешно удалена.");

        return redirect()->route('admin.promotions.index');
    }
}
