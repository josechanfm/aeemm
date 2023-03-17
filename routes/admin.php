<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Inertia\Inertia;


Route::group(['middleware' => config('fortify.middleware', ['admin_web'])], function () {

    $limiter = config('fortify.limiters.login');

    Route::get('/admin/login', function () {
        return Inertia::render('Admin/Login');
    })->middleware(['guest:'.config('fortify.guard')]);
    
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(array_filter([
        'guest:'.config('fortify.guard'),
        $limiter ? 'throttle:'.$limiter : null,
    ]));

    Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('admin.logout');
});


// Route::middleware([
//     'auth:admin_web',
// ])->group(function () {
//     Route::get('/admin', function () {
//         // 如果是 admin 或者 master 就跳到 amdin
//         if (request()->user()->hasRole(['admin', 'master'])) return redirect()->to('/admin');
//         // 否則去teacher
//         else return redirect()->to('/');
//     });
// });

Route::middleware([
    'auth:admin_web',
    config('jetstream.auth_session'),
    'role:organization|admin|master',
])->group(function () {
    Route::prefix('/admin')->group(function(){
        Route::get('/',[App\Http\Controllers\Admin\DashboardController::class,'index'])->name('admin.index');
        Route::resource('members', App\Http\Controllers\Admin\MemberController::class);
        Route::resource('certificates', App\Http\Controllers\Admin\CertificateController::class);
        Route::resource('forms', App\Http\Controllers\Admin\FormController::class);
        Route::resource('form.fields', App\Http\Controllers\Admin\FormFieldController::class);
        Route::get('certificate-delete-media/{media}',[App\Http\Controllers\Admin\CertificateController::class,'deleteMedia'])->name('certificate-delete-media');
    })->name('admin');
});

// Route::middleware([
//     'auth:admin_web',
//     config('jetstream.auth_session'),
//     'role:master',
// ])->group(function () {
//         Route::prefix('/master')->group(function(){
//             Route::get('/',[App\Http\Controllers\Admin\DashboardController::class,'index'])->name('master.dashboard');
//             Route::prefix('/member')->group(function(){
//                 Route::resource('/',App\Http\Controllers\Admin\MemberController::class);
//                 Route::get('/create_login/{member}',[App\Http\Controllers\Admin\MemberController::class,'createLogin']);
//             })->name('admin.mastermember');        
//         })->name('admin.master');
// });



