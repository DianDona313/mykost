<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    public function send(array $pengelolas)
    {
        $message = 'Halo selamat datang, ini hanya pesan testing';

        foreach ($pengelolas as $pengelola) {
            // Pastikan nomor HP dalam format internasional tanpa spasi atau tanda
            $to = $pengelola->no_telp_pengelola;

            // Kirim pesan
            $response = $this->fonnte->sendMessage($to, $message);

            // (Opsional) Simpan response ke array jika ingin ditampilkan semua
            $responses[] = [
                'to' => $to,
                'response' => $response,
            ];
        }

        return response()->json([
            'status' => 'success',
            'sent_to' => count($pengelolas) . ' pengelola',
            'responses' => $responses ?? [],
        ]);
    }

}