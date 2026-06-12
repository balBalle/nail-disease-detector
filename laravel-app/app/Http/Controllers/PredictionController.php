<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PredictionController extends Controller
{
    public function index()
    {
        $predictions = Prediction::latest()->paginate(10);
        return view('predictions.index', compact('predictions'));
    }

    public function create()
{
    $predictions = Prediction::latest()->get();
    
    return view('predictions.create', compact('predictions'));
}

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('image')->store('predictions', 'public');

        $prediction = Prediction::create([
            'image_path' => $path,
            'status'     => 'pending',
        ]);

        try {
            $response = Http::timeout(30)->attach(
                'image',
                file_get_contents(storage_path('app/public/' . $path)),
                basename($path)
            )->post(env('PYTHON_SERVICE_URL') . '/predict');

            $result = $response->json();

            if ($response->status() === 422) {
                $prediction->update([
                    'result' => 'invalid',
                    'status' => 'error',
                ]);

                return redirect()->route('predictions.create')
                    ->with('error', $result['message'] ?? 'Gambar tidak valid');
            }

            $prediction->update([
                'result'        => $result['result']     ?? 'Unknown',
                'confidence'    => $result['confidence'] ?? 0,
                'status'        => 'done',
                'probabilities' => json_encode($result['probabilities'] ?? []),
            ]);

        } catch (\Exception $e) {
            $prediction->update(['status' => 'error']);

            return redirect()->route('predictions.create')
                ->with('error', 'Gagal konek ke Python service. Pastikan Python sedang berjalan.');
        }

        return redirect()->route('predictions.show', $prediction->id);
    }

    public function show($id)
    {
        $prediction = Prediction::findOrFail($id);
        return view('predictions.show', compact('prediction'));
    }
}