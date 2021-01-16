<?php


namespace AloiaCms\Console;

use Illuminate\Console\Command;

class ConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aloiacms:publish:config';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-publish the Flat File CMS config';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'config',
            '--force' => true,
        ]);

        return 0;
    }
}
