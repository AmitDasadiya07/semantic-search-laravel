<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use Illuminate\Support\Facades\Http;

class GenerateEmbeddings extends Command
{
    protected $signature = 'generate:embeddings';
    protected $description = 'Generate OpenAI embeddings for all categories';

    public function handle()
    {
        $apiKey = env('OPENAI_API_KEY');

        if (!$apiKey) {
            $this->error('❌ OpenAI API key not found in .env file.');
            return;
        }

        $categories = Category::whereNull('embedding')->get();

        if ($categories->isEmpty()) {
            $this->info('✅ All categories already have embeddings.');
            return;
        }

        foreach ($categories as $category) {
            $response = Http::withHeaders([
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/embeddings', [
                'input' => $category->name,
                'model' => 'text-embedding-ada-002',
            ]);

            if ($response->successful()) {
                $embedding = $response->json()['data'][0]['embedding'];
                $category->embedding = json_encode($embedding);
                $category->save();

                $this->info("✅ Embedded: {$category->name}");
          } else {
    $this->error("❌ Failed: {$category->name} - " . $response->body());
}


            usleep(200000); // 0.2 sec delay
        }

        $this->info('🎉 All embeddings generated successfully.');
    }
}
