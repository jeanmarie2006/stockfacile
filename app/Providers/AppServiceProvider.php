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
        Paginator::defaultView('pagination');
        Blade::directive('fcfa', fn ($expr) => "<?php echo \\App\\Support\\Fmt::fcfa({$expr}); ?>");
        Blade::directive('datefr', fn ($expr) => "<?php echo \\App\\Support\\Fmt::date({$expr}); ?>");
    }
}
