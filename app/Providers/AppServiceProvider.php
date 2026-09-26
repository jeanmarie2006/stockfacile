<?php

namespace App\Providers;

use App\Support\Fmt;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // DomPDF : dossier public réel (utile quand le code de l'application est séparé du dossier public, comme sur l'hébergement)
        config(['dompdf.public_path' => public_path()]);
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        Paginator::defaultView('pagination');
        Blade::directive('fcfa', fn ($expr) => "<?php echo \\App\\Support\\Fmt::fcfa({$expr}); ?>");
        Blade::directive('datefr', fn ($expr) => "<?php echo \\App\\Support\\Fmt::date({$expr}); ?>");
    }
}
