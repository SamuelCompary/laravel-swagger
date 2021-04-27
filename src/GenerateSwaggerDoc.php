<?php

namespace Mtrajano\LaravelSwagger;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateSwaggerDoc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-swagger:generate
                            {--format=json : The format of the output, current options are json and yaml}
                            {--f|filter= : Filter to a specific route prefix, such as /api or /v2/api}
                            {--o|output= : Output file to write the contents to, defaults to stdout}
                            {--u|upload= : Upload file to provider}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generates a swagger documentation file for this application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = config('laravel-swagger');
        $filter = $this->option('filter') ?: null;
        $file = $this->option('output') ?: null;
        $upload = $this->option('upload') ?: null;

        $docs = (new Generator($config, $filter))->generate();

        $formattedDocs = (new FormatterManager($docs))
            ->setFormat($this->option('format'))
            ->format();
        $this->info(gettype($formattedDocs));

        if ($file) {
            file_put_contents($file, $formattedDocs);
        } elseif ($upload) {
            $tmpFile = tmpfile();
            $stream = stream_get_meta_data($tmpFile)['uri'];
            $writer = fopen($stream, 'w');
            fputs($writer, $formattedDocs);

            Storage::disk($upload)->putFileAs(
                config('constants.uploads.folder_export'), $stream, 'swagger-' . \App::environment() . '.json'
            );
            $this->line('Uploaded file to azure');
        } else {
            $this->line($formattedDocs);
        }
    }
}
