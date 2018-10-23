<?php

namespace App\Commands;

use Requests;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\Storage;

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

    protected $orgId;
    protected $export;
    protected $pages = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->orgId = $this->argument('orgId');

        $this->info("Fetching data from Site Organic");
        $this->fetchSiteOrganicData();

        $this->info("Generating Standard Page Array Format");
        $this->generatePages();

        Storage::put("{$this->orgId}.json", json_encode($this->pages));

        $this->notify("Site Organic Exporter", "Export Complete and Ready for Import!");
    }


    private function fetchSiteOrganicData()
    {
        $this->export = collect(json_decode(Requests::get("http://care.siteorganic.com/webservices/api/exportjson.asp?orgid={$this->orgId}&published=1", array('Accept' => 'application/json'))->body));
    }

    private function generatePages()
    {
        $this->export->each(function ($set) {
            $set = array_values(get_object_vars($set));
            $set = collect($set[0]->Pages);
            $set->each(function ($page) {
                $this->pages[] = [
                    'name' => $page->name,
                    'description' => $page->description,
                    'description' => $page->description,
                    'keywords' => $page->keywords,
                    'keywords' => $page->keywords,
                    'image_field_1' => $page->image_field_1,
                    'image_field_2' => $page->image_field_2,
                    'side_image_field' => $page->side_image_field,
                    'body' => html_entity_decode($page->body),
                ];
            });
        });
    }
}
