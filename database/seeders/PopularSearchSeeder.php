<?php

namespace Database\Seeders;

use App\Models\PopularSearch;
use Illuminate\Database\Seeder;

class PopularSearchSeeder extends Seeder
{
    public function run(): void
    {
        $searches = [
            ['query' => 'dress', 'search_count' => 150],
            ['query' => 'shirt', 'search_count' => 120],
            ['query' => 'jeans', 'search_count' => 100],
            ['query' => 'jacket', 'search_count' => 95],
            ['query' => 'shoes', 'search_count' => 90],
            ['query' => 'bag', 'search_count' => 85],
            ['query' => 'watch', 'search_count' => 80],
            ['query' => 'sunglasses', 'search_count' => 75],
            ['query' => 'sneakers', 'search_count' => 70],
            ['query' => 'blazer', 'search_count' => 65],
        ];

        foreach ($searches as $search) {
            PopularSearch::updateOrCreate(
                ['query' => $search['query']],
                ['search_count' => $search['search_count']]
            );
        }

        $this->command->info('Popular searches seeded!');
    }
}
