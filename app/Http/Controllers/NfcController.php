<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;

class NfcController extends Controller
{
    public function readCard()
    {
        $scriptPath = base_path('python_scripts/nfc_reader.py');
        $output = shell_exec("python $scriptPath");
    
        if ($output) {
            $student = Students::where('nis', trim($output))->first();

            return response()->json([
                'status' => 'success',
                'card_uid' => trim($output),
                'student' => $student
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to read NFC card.'
            ]);
        }
    }
    
}
