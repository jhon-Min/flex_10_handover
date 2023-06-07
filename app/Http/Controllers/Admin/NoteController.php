<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Note;
use Illuminate\Http\Request;
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
        $notes = Note::orderBy('id', 'DESC')->get();
        return Datatables::of($notes)
            ->editColumn('date_string', function ($data) {
                return strtotime($data->created_at);
            })
            ->editColumn('date', function ($data) {
                return date('d/m/Y',strtotime($data->created_at));
            })
            ->editColumn('product_nr', function ($data) {
              
                return $data->product->product_nr;
            })
            ->editColumn('description', function ($data) {
                return $data->description;
            })
            ->editColumn('user', function ($data) {
                return $data->user->name;
            })
            ->rawColumns(['date_string','date', 'product_nr', 'description', 'user'])
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
