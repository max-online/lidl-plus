<?php

namespace App\Console\Commands;

use App\Services\CategoryService;
use App\Services\ReceiptService;
use Illuminate\Console\Command;

class ImportReceipts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receipts:import {--test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all receipts';

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
        $files = $this->getFiles();

        $this->info("Importing receipts!");

        $bar = $this->output->createProgressBar(count($files));

        $bar->start();

        foreach ($files as $file) {
            (new ReceiptService)->parse($file);

            $bar->advance();
        }

        $bar->finish();

        $this->info("\nImported " . count($files) . " receipts.\n");

        $this->info("Categorizing articles...");

        (new CategoryService())->categorize();

        $this->info('Done!');
    }

    protected function getFiles()
    {
        if ($this->option('test')) {
            return \File::allFiles('tests/data');
        }

        /* $data = (new TesseractOCR($targetFile))
            ->lang('deu')
            ->run(); */

        return \File::allFiles(public_path('/data'));
    }
}
