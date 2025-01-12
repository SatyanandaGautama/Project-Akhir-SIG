<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ViewMapController extends Controller
{
    public function showAdd()
    {
        return view('addMarker');
    }

    public function showRute()
    {
        return view('rute');
    }

    public function showMap()
    {
        return view('map');
    }

    public function getMarkers()
    {
        return response()->json(Marker::select(
            'id',
            'name',
            'latitude',
            'longitude',
            'layanan_kesehatan',
            'jam_operasional',
            'no_telpon',
            'alamat',
            'foto'
        )->get());
    }
    

    public function viewMarker($id)
    {
        $marker = Marker::findOrFail($id);
        return response()->json($marker);
    }

    public function storeMarker(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'layanan_kesehatan' => 'required|string',
            'jam_operasional' => 'required|string',
            'no_telpon' => 'required|string',
            'alamat' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validasi untuk file foto
        ]);

        //Cek apakah ada file foto yang diunggah.
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos','public'); //Simpan foto di storage/app/public
        } else {
            $fotoPath = null;
        }

        //Simpan data pada tabel marker
        $marker = Marker::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'layanan_kesehatan' => $request->layanan_kesehatan,
            'jam_operasional' => $request->jam_operasional,
            'no_telpon' => $request->no_telpon,
            'alamat' => $request->alamat,
            'foto' => $fotoPath,
        ]);
        return response()->json($marker);
    }

    public function deleteMarker($id){
    $marker = Marker::findOrFail($id);
    // Hapus foto dari storage/app/public
    if ($marker->foto && Storage::disk('public')->exists($marker->foto)) {
        Storage::disk('public')->delete($marker->foto);
    }
    // Hapus marker dari database
    $marker->delete();
    return response()->json(['message' => 'Marker berhasil dihapus.']);
    }
}

