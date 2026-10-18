<?php

namespace App\Console\Commands;

use App\Services\Widgets;
use Illuminate\Console\Command;

class RefreshWidgets extends Command
{
    protected $signature = 'widgets:refresh';
    protected $description = 'Actualise la météo, les horaires de prière et les taux de change';

    public function handle(Widgets $widgets): int
    {
        $data = $widgets->all(force: true);

        foreach ($data as $name => $value) {
            $this->line(str_pad($name, 8).($value ? 'OK' : 'ÉCHEC (voir storage/logs)'));
        }

        return self::SUCCESS;
    }
}