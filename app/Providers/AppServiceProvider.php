<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Categoria;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Compartilhar categorias globalmente
        $categoriasall = collect();

        if (! app()->runningInConsole() && Schema::hasTable('categorias')) {
            $categoriasall = Categoria::all();
        }

        view()->share('categoriasall', $categoriasall);

        // Carrinho (contador global)
        View::composer('*', function ($view) {

            $carrinho = session()->get('carrinho', []);

            $quantidade = collect($carrinho)->sum('quantidade');

            $view->with('cartCount', $quantidade);
        });
    }
}
