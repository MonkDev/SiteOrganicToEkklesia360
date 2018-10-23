<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Export extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'export
                            {orgId : The Id of the site being exported}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Export a Site Organic site to an Ekklesia 360 Format';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orgId = $this->argument('orgId');
        $this->info("Starting the export for Org Id: {$orgId}");
        $this->notify("Site Organic Exporter", "Export Complete and Ready for Import!");
    }
}
