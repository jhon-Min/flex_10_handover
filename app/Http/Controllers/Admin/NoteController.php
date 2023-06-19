<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('note.notes');
    }

    public function getNoteDatatable(Request $request)
    {
        $notes = Note::query()->with(['product','user'])->orderBy('id', 'DESC');
        return Datatables::of($notes)
            ->addColumn('date_string', function ($data) {
                return strtotime($data->created_at);
            })
            ->addColumn('date', function ($data) {
                return date('d/m/Y', strtotime($data->created_at));
            })
            ->editColumn('product_nr', function ($data) {
                return $data->product->product_nr;
            })
            ->filterColumn('product_nr', function($query, $keyword) {
                $query->whereHas('product', fn($q) => $q->where(DB::raw('product_nr'), 'like','%'. $keyword . '%'));
            })
            ->editColumn('description', function ($data) {
                return $data->description;
            })
            ->addColumn('user', function ($data) {
                return $data->user->name;
            })
            ->filterColumn('user', function($query, $keyword) {
                $query->whereHas('user', fn($q) => $q->where(DB::raw('concat(first_name," ",last_name)'), 'like','%'. $keyword . '%'));
            })
            ->rawColumns(['date_string', 'date', 'product_nr', 'description', 'user'])
            ->only(['date_string', 'date', 'product_nr', 'description', 'user'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
