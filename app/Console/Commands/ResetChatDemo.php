<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetChatDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:reset-demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset chat demo data with fresh users and messages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Resetting chat demo data...');
        $this->newLine();

        $this->call('db:seed', ['--class' => 'ChatDemoSeeder']);

        $this->newLine();
        $this->info('✅ Done! You can now login and test chat.');
        
        return 0;
    }
}
