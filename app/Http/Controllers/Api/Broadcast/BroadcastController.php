<?php

namespace App\Http\Controllers\Api\Broadcast;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BroadcastController extends Controller
{
    private function getInformation()
    {
        $user = Auth::user();

        return [
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
    private function postMessage()
    {
        $postMessage = Broadcast::create([
            
        ]);
    }
    private function getMessage()
    {
        
    }

    public function sendMessage()
    {
        $data_pengguna = $this->getInformation();
        $data_message = $this->getMessage();
        return response()->json(['data_pengguna' => $data_pengguna]);
    }
}
