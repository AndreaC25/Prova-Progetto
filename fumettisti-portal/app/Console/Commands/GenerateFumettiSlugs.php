<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fumetto;
use Illuminate\Support\Str;

class GenerateFumettiSlugs extends Command
{
    protected $signature = 'fumetti:generate-slugs';
    protected $description = 'Generate slugs for existing fumetti';

    public function handle()
    {
        $fumetti = Fumetto::whereNull('slug')->get();

        foreach ($fumetti as $fumetto) {
            $slug = Str::slug($fumetto->title);
            $originalSlug = $slug;
            $counter = 1;

            while (Fumetto::where('slug', $slug)->where('id', '!=', $fumetto->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $fumetto->update(['slug' => $slug]);
            $this->info("Generated slug for: {$fumetto->title} -> {$slug}");
        }

        $this->info('Slugs generated successfully!');
    }
}
