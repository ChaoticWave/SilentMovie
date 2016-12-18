<?php
//******************************************************************************
//* Bootstrap logic
//******************************************************************************

if (!function_exists('__sm_bootstrap')) {
    function __sm_bootstrap()
    {
        $_app = new Illuminate\Foundation\Application(realpath(__DIR__ . '/../'));

        $_app->singleton(Illuminate\Contracts\Http\Kernel::class, ChaoticWave\SilentMovie\Http\Kernel::class);
        $_app->singleton(Illuminate\Contracts\Console\Kernel::class, ChaoticWave\SilentMovie\Console\Kernel::class);
        $_app->singleton(Illuminate\Contracts\Debug\ExceptionHandler::class, ChaoticWave\SilentMovie\Exceptions\Handler::class);

        return $_app;
    }
}

return __sm_bootstrap();

