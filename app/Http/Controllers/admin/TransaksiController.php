<?php

namespace App\Http\Controllers\admin;

use App\Models\Item;
use App\Models\Transaksi;
use App\Models\ItemProduct;
use Illuminate\Http\Request;
use App\Models\TransaksiItem;
use App\Http\Controllers\Controller;

class TransaksiController extends Controller
{
    public function index() {
        $transaksis = Transaksi::orderBy('created_at', 'desc')->get();
        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function create() {

        $daftars = ItemProduct::orderBy('created_at','desc')->get();
        return view('admin.transaksi.create', compact('daftars'));
    }

    public function store(Request $request) {
        $transaksi = new Transaksi();
        $transaksi->fill([
            'nama_resep' => $request->get('nama_resep'),
            'total_harga' => $request->get('total_harga'),
            'qty' => $request->get('qty'),
            'grand_total' => $request->get('total_harga')*$request->get('qty'),
        ]);
        $transaksi->save();
        foreach ($request->get('id_item') as $id_item) {
            $daftar = ItemProduct::findOrFail($id_item);
            $transaksi_item = new TransaksiItem();
            $transaksi_item->fill([
                'id_transaksi' => $transaksi->id, 
                'id_item' => $id_item, 
                'nama' => $daftar->name, 
                'harga' => $daftar->hpp
            ]);
            $transaksi_item->save();
        }
        return redirect()->route('transaksi.index');
    }   

    public function destroy($id) {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return redirect()->back();
    }
}
