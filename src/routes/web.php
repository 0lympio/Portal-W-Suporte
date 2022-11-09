<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SlideshowController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\QuestionnaireViewController;
use App\Http\Controllers\Reports\LoginLogoutController;
use App\Http\Controllers\Reports\BillingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reports\PostsReportController;
use App\Http\Controllers\Reports\QuestionnaireReportController;
use App\Http\Controllers\UserAnswerController;

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth', 'session']], function () {
    Route::get('/', [ContentController::class, 'home'])->name('content.home');

    Route::name('admin.')->prefix('admin')->group(function () {
        Route::get('/view/{post}', [PostController::class, 'adminView'])->name('adminView');
    });

    Route::name('posts.')->prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('update');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('delete');
        Route::get('/hasPopup', [PostController::class, 'hasPopup'])->name('hasPopup');
        Route::get('/{slug}/{read?}', [PostController::class, 'show'])->name('show');

        Route::post('/{post}/read', [PostController::class, 'markAsRead'])->name('read');
        Route::post('/status/{post?}', [PostController::class, 'changeStatus'])->name('status');
    });

    Route::name('categories.')->prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('delete');
        Route::post('/status/{category?}', [CategoryController::class, 'changeStatus'])->name('status');
    });

    Route::name('faqs.')->prefix('faqs')->group(function () {
        Route::get('/', [FaqController::class, 'index'])->name('index');
    });

    Route::name('approvals.')->prefix('approvals')->group(function () {
        Route::get('/', [ApprovalController::class, 'index'])->name('index');
        Route::put('/', [ApprovalController::class, 'approver'])->name('approver');
        Route::post('/{approvals}', [ApprovalController::class, 'show'])->name('show');
    });

    Route::name('roles.')->prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('delete');
    });

    Route::name('questionnaires.')->prefix('questionnaires')->group(function () {
        Route::get('/', [QuestionnaireController::class, 'index'])->name('index');
        Route::get('/open', [QuestionnaireController::class, 'open'])->name('open');
        Route::get('/create', [QuestionnaireController::class, 'create'])->name('create');
        Route::get('/{questionnaire}/edit', [QuestionnaireController::class, 'edit'])->name('edit');
        Route::get('/{questionnaire}', [QuestionnaireController::class, 'show'])->name('show');
        Route::put('/{questionnaire}', [QuestionnaireController::class, 'update'])->name('update');
        Route::post('/', [QuestionnaireController::class, 'store'])->name('store');
        Route::delete('/{questionnaire}', [QuestionnaireController::class, 'destroy'])->name('delete');
        Route::post('/status/{questionnaire?}', [QuestionnaireController::class, 'changeStatus'])->name('status');
        Route::post('/reply', [UserAnswerController::class, 'store'])->name('reply');
        Route::post('/view', [QuestionnaireViewController::class, 'store'])->name('view');
    });

    Route::name('users.')->prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('delete');
        Route::put('/{user}/changePassword', [UserController::class, 'changePassword'])->name('changePassword');
        Route::post('/import', [UserController::class, 'import'])->name('import');
        Route::post('/status/{user?}', [UserController::class, 'changeStatus'])->name('status');
    });

    Route::name('slideshow.')->prefix('slideshow')->group(function () {
        Route::get('/', [SlideshowController::class, 'index'])->name('index');
        Route::get('/create', [SlideshowController::class, 'create'])->name('create');
        Route::post('/', [SlideshowController::class, 'store'])->name('store');
        Route::get('/{slideshow}/edit', [SlideshowController::class, 'edit'])->name('edit');
        Route::put('/{slideshow}', [SlideshowController::class, 'update'])->name('update');
        Route::delete('/{slideshow}', [SlideshowController::class, 'destroy'])->name('delete');

        Route::put('/', [SlideshowController::class, 'displayTime'])->name('displayTime');
    });

    Route::name('reports.')->prefix('reports')->group(function () {
        Route::get('/login-logout', [LoginLogoutController::class, 'index'])->name('loginLogout.index');
        Route::post('/login-logout/filter', [LoginLogoutController::class, 'filter'])->name('loginLogout.filter');
        Route::post('/login-logout/export', [LoginLogoutController::class, 'export'])->name('loginLogout.export');

        Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
        Route::post('/billing/export', [BillingController::class, 'export'])->name('billing.export');
        Route::post('/billing/filter', [BillingController::class, 'filter'])->name('billing.filter');

        Route::get('/posts', [PostsReportController::class, 'index'])->name('posts.index');
        Route::post('/posts/export', [PostsReportController::class, 'export'])->name('posts.export');

        Route::get('/questionnaires', [QuestionnaireReportController::class, 'index'])->name('questionnaires.index');
        Route::get('/questionnaires/{questionnaire}', [QuestionnaireReportController::class, 'show'])->name('questionnaires.show');
        Route::get('/questionnaires/{questionnaire}/filter', [QuestionnaireReportController::class, 'filter'])->name('questionnaires.filter');
        Route::post('/questionnaires/export', [QuestionnaireReportController::class, 'export'])->name('questionnaires.export');
    });

    Route::name('uploads.')->prefix('uploads')->group(function () {
        Route::post('/', [UploadController::class, 'store'])->name('store');
        Route::get('/', [UploadController::class, 'index'])->name('index');
        Route::delete('/', [UploadController::class, 'destroy'])->name('destroy');
    });

    Route::name('comments.')->prefix('comments')->group(function () {
        Route::post('/', [PostCommentController::class, 'store'])->name('store');
        Route::delete('/{postComment}', [PostCommentController::class, 'destroy'])->name('destroy');
    });

    Route::name('admin.')->prefix('admin')->group(function () {
        Route::get('/view/{post}', [PostController::class, 'adminView'])->name('view');
        Route::put('/publish/{post}', [PostController::class, 'adminPublish'])->name('publish');
    });

    Route::name('home.')->prefix('home')->group(function () {
        Route::get('/edit', [ContentController::class, 'homeEdit'])->name('edit');
        Route::post('/store', [ContentController::class, 'homeStore'])->name('store');
    });

    Route::name('companies.')->prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::post('/store', [CompanyController::class, 'store'])->name('store');
        Route::put('/{company}', [CompanyController::class, 'update'])->name('update');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy');
    });

    // Tem que ser sempre a última rota, porque ela é generica
    Route::get('/{category}', [CategoryController::class, 'show'])->name('content.show');
});
