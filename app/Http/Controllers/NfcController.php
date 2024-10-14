<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NfcController extends Controller
{
    public function readCard()
    {
        $scriptPath = base_path('python_scripts/nfc_reader.py');
        $output = shell_exec("python $scriptPath");
    
        if ($output) {
            return response()->json([
                'status' => 'success',
                'card_uid' => trim($output)
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to read NFC card.'
            ]);
        }
    }
    
}
