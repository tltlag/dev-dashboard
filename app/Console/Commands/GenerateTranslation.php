<?php

namespace App\Console\Commands;

use App\Models\Translation;
use Illuminate\Console\Command;

class GenerateTranslation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate translation files from db.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Translation::generateTranslationFiles();

        return $this->info(__('Translations file generated.'));
    }
}
