<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $results = [];
        $query = $request->input('query');

        if ($query) {
            $apiKey = env('OPENAI_API_KEY');

            // Get embedding of input
            $embeddingResponse = Http::withHeaders([
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/embeddings', [
                'input' => $query,
                'model' => 'text-embedding-ada-002',
            ]);

            if ($embeddingResponse->successful()) {
                $inputVector = $embeddingResponse->json()['data'][0]['embedding'];

                $categories = Category::whereNotNull('embedding')->get();

                $scored = [];

                foreach ($categories as $category) {
                    $categoryVector = json_decode($category->embedding, true);
                    $similarity = $this->cosineSimilarity($inputVector, $categoryVector);

                    $scored[] = [
                        'name' => $category->name,
                        'score' => $similarity,
                    ];
                }

                usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);
                $results = array_slice($scored, 0, 5);
            }
        }

        return view('search', compact('results', 'query'));
    }

    private function cosineSimilarity($vec1, $vec2)
    {
        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        for ($i = 0; $i < count($vec1); $i++) {
            $dot += $vec1[$i] * $vec2[$i];
            $normA += $vec1[$i] ** 2;
            $normB += $vec2[$i] ** 2;
        }

        return $normA && $normB ? $dot / (sqrt($normA) * sqrt($normB)) : 0;
    }
}
