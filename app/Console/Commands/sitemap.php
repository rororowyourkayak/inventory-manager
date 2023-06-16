<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\SitemapGenerator;


class sitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a sitemap.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(!Storage::disk('public')->exists('sitemap.xml')){
            Storage::disk('public')->put('sitemap.xml', '');
        }
        SitemapGenerator::create(env('APP_URL'))->writeToFile(public_path('sitemap.xml'));
        $this->newLine();
        $this->info('Writing to sitemap.xml in public folder');
        $this->newLine();
        $this->info('Sitemap made successfully!');
    }
}
