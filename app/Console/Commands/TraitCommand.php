<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class TraitCommand extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {traitName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new trait';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Trait';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }
  
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $traitName = $this->argument('traitName');
        $traitPath = app_path("Traits" . DIRECTORY_SEPARATOR . "{$traitName}.php");

        if (file_exists($traitPath)) {
            $this->error("Trait '{$traitName}' already exists!");
            return;
        }

        if (! $this->files->isDirectory(dirname($traitPath))) {
            $this->files->makeDirectory(dirname($traitPath), 0777, true, true);
        }

        $nameSpace = explode(DIRECTORY_SEPARATOR, $traitName);

        if ($nameSpace) {
            $traitName = array_pop($nameSpace);
        }

        $nameSpace = (array) ($nameSpace ? $nameSpace : []);

        file_put_contents($traitPath, "<?php\n\nnamespace App\\Traits" . ($nameSpace ? "\\" . implode('\\', $nameSpace) : '') . ";\n\ntrait {$traitName}\n{\n    // Trait logic goes here\n}\n");

        $this->info("Trait '{$traitName}' created successfully!");
    }
}
