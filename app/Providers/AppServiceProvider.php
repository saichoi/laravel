<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 엘로퀀트 스트릭트 모드 : 레이지 로딩, 대량 할당, 존재하지 않는 변수들에 대한 에러를 잡아준다.
        Model::shouldBeStrict(! $this->app->isProduction()); // prod에서는 동작하지 않도록 
    }
}
