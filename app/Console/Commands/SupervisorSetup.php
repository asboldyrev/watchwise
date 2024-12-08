<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SupervisorSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:supervisor-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up Supervisor configurations...');

        // Пути к вашим конфигурационным файлам и целевой директории
        $configDirectory = base_path('config/supervisord');
        $supervisorConfigDir = '/etc/supervisor/conf.d'; // Измените, если путь другой

        // Конфигурационные файлы
        $files = [
            'queue.conf' => $configDirectory . '/queue.conf',
            'reverb.conf' => $configDirectory . '/reverb.conf',
        ];

        // Создание символических ссылок
        foreach ($files as $configFile => $sourcePath) {
            $targetPath = $supervisorConfigDir . '/' . $configFile;

            if (File::exists($targetPath)) {
                $this->comment("Symbolic link for {$configFile} already exists.");
            } else {
                exec('sudo ln -s ' . $sourcePath . ' ' . $targetPath);
                $this->info("Created symbolic link for {$configFile}.");
            }
        }

        // Перезапуск Supervisor, чтобы применить новые конфигурации
        $this->info('Reloading Supervisor configurations...');
        exec('sudo supervisorctl reread');
        exec('sudo supervisorctl update');
        exec('sudo supervisorctl start all');

        $this->info('Supervisor setup completed.');
    }
}
