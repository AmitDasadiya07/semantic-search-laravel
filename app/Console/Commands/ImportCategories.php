<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category; // ✅ Add this line
use Maatwebsite\Excel\Facades\Excel;

class ImportCategories extends Command
{
    protected $signature = 'import:categories';
    protected $description = 'Import sub-categories from Excel into categories table';

    public function handle()
    {
        $path = storage_path('app/public/categories.xlsx');

        if (!file_exists($path)) {
            $this->error("❌ Excel file not found at: $path");
            return;
        }

        $data = Excel::toArray([], $path);
        $rows = $data[0];
        $count = 0;

        foreach ($rows as $row) {
            if (!empty($row[1])) {
                $name = trim($row[1]);
                Category::firstOrCreate(['name' => $name]);
                $count++;
            }
        }

        $this->info("✅ Imported $count sub-categories into the database.");
    }
}
