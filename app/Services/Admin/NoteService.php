<?php

namespace App\Services\Admin;

use App\Models\Note;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class NoteService
{
    public function tableLists()
    {
        $notes = Note::query()->with(['product', 'user'])->orderBy('id', 'DESC');
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
            ->filterColumn('product_nr', function ($query, $keyword) {
                $query->whereHas('product', fn ($q) => $q->where(DB::raw('product_nr'), 'like', '%' . $keyword . '%'));
            })
            ->editColumn('description', function ($data) {
                return $data->description;
            })
            ->addColumn('user', function ($data) {
                return $data->user->name;
            })
            ->filterColumn('user', function ($query, $keyword) {
                $query->whereHas('user', fn ($q) => $q->where(DB::raw('concat(first_name," ",last_name)'), 'like', '%' . $keyword . '%'));
            })
            ->rawColumns(['date_string', 'date', 'product_nr', 'description', 'user'])
            ->only(['date_string', 'date', 'product_nr', 'description', 'user'])
            ->make(true);
    }
}
