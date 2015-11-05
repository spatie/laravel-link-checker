<?php
namespace Spatie\MediaLibrary\Commands;



use Spatie\Crawler\Crawler;

class CheckLinksCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'linkchecker:run {url?}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all links';


    public function __construct(Crawler $crawler)
    {
        parent::__construct();

        $this->crawler = $crawler;
    }

    public function handle()
    {
        $this->info('All done!');
    }

}