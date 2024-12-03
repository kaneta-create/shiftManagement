<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'checkRole' => \App\Http\Middleware\CheckRole::class,
    ];
    
    // //まず二ヶ月後の月の日付と曜日を取得する
    // $startOfMonth = Carbon::now()->startOfMonth();
    // $endOfMonth = Carbon::now()->endOfMonth();
    // $now = Carbon::now();
    // $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
    
    // Carbon::setLocale('ja');
    // foreach($period as $date){
    //     $month[] = [
    //         'date' => $date->format('Y-m-d'),
    //         'day_of_week_iso' => $date->dayOfWeekIso,
    //         'day_of_week' => $date->translatedFormat('D'),
    //         'year' => $date->format('Y'),
    //         'month' => $date->translatedFormat('M')
    //     ];
    //     $shiftDates[] = [
    //         $date->format('j') => $date->translatedFormat('D')
    //     ];
        
    // };
    // //次にemployee_id毎にdefaultShiftテーブルの値を取得する
    // $arrayCount = count($period);
    // // dd($arrayCount);
    // $defaultShifts = DefaultShift::select('id', 'employee_id', 'clock_in', 'clock_out', 'day_of_week')->get();
    // for($i = 0; $i < $arrayCount; $i++){
    //     foreach($defaultShifts as $defaultShift){
    //         if($month[$i]['day_of_week_iso'] == $defaultShift->day_of_week){
    //             $shifts[] = [
    //                 'attendance_date' => $month[$i]['date'],
    //                 'clock_in' => $defaultShift->clock_in,
    //                 'clock_out' => $defaultShift->clock_out,
    //                 'day_of_week' => $month[$i]['day_of_week_iso'],
    //                 'employee_id' => $defaultShift->employee_id,
    //             ];
    //         }
    //     }
    // }   
}
