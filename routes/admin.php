<?php

use App\Http\Controllers\Admin\AboutController;

use App\Http\Controllers\Admin\AchivementController;


use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CertificateController;


use App\Http\Controllers\Admin\FaqController;

use App\Http\Controllers\Admin\PageController;


use App\Http\Controllers\Admin\ValueTypeController;
use App\Http\Controllers\Admin\WhyChooseUsController;

use App\Http\Controllers\TraningCertificateController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContactConroller;
use App\Http\Controllers\Admin\PartnerInquiryController;
use App\Http\Controllers\Admin\CareearConroller;
use App\Http\Controllers\Admin\CareearJobsConroller;
use App\Http\Controllers\Admin\ContentController;


use App\Http\Controllers\Admin\ManagementController;
use App\Http\Controllers\Admin\YoutubeController;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectCategoryController;

use App\Http\Controllers\Admin\RewardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TeamController;


//End Financial Report




// Upload Image and get =======================================================
use App\Http\Controllers\Admin\UploadController;
    Route::group(['prefix' => '', 'as' => '', 'middleware' => 'auth:admin'], function () {
    Route::post('/uploads/post', [UploadController::class, 'store']);
    Route::get('/uploads/get', [UploadController::class, 'index']);
    Route::get('/uploads/delete', [UploadController::class, 'delete']);
});
// Upload Image and get =======================================================





// Authentic Route ============================================================================
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\RegisteredUserController as AdminRegisteredUserController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController as AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::group(['middleware' => 'guest:admin'], function () {
        Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AdminAuthenticatedSessionController::class, 'store'])->name('loginCheck');
        Route::get('register', [AdminRegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [AdminRegisteredUserController::class, 'store']);
    });

    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
        Route::post('dashboard/queue-worker/start', [AdminDashboardController::class, 'queueWorkerStart'])->name('dashboard.queue_worker.start');
        Route::post('dashboard/queue-worker/stop', [AdminDashboardController::class, 'queueWorkerStop'])->name('dashboard.queue_worker.stop');
        Route::get('dashboard/queue-worker/status', [AdminDashboardController::class, 'queueWorkerStatus'])->name('dashboard.queue_worker.status');
        Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    });

    // Mixex middleware
    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->middleware('auth')->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])->middleware('auth');
    Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth:admin');
});






use App\Http\Controllers\Admin\AppllicationController;
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::resource('application', AppllicationController::class)->names('application');


    Route::resource('contacts', ContactConroller::class);
    Route::post('contacts/mark_as_read', [ContactConroller::class, 'mark_as_read'])->name('contacts.mark_as_read');
    Route::resource('partner-inquiries', PartnerInquiryController::class)->only(['index', 'show', 'destroy']);
    Route::post('partner-inquiries/mark_as_read', [PartnerInquiryController::class, 'mark_as_read'])->name('partner-inquiries.mark_as_read');

    Route::resource('careear_jobs', CareearJobsConroller::class);
    Route::resource('careears', CareearConroller::class);
    Route::post('careears/mark_as_read', [CareearConroller::class, 'mark_as_read'])->name('careears.mark_as_read');

    Route::resource('projects', ProjectController::class);
    Route::resource('project-categories', ProjectCategoryController::class);
    Route::get('project_categories_select', [ProjectCategoryController::class, 'select'])->name('project_categories.select');



    Route::resource('teams', TeamController::class);

    Route::resource('clients', ClientController::class);
    Route::resource('certificates', CertificateController::class);
    Route::resource('training-certificates', TraningCertificateController::class);
    Route::resource('sliders', SliderController::class);


    // ============================ Product =======================================
    // CAtegory
    Route::resource('categories', CategoryController::class);
    Route::get('categories_select', [CategoryController::class, 'select'])->name('categories.select');

    // Subcategory
    Route::resource('subcategories', SubCategoryController::class);
    Route::get('subcategories_select', [SubCategoryController::class, 'select'])->name('subcategories.select');




    Route::resource('values', ValueTypeController::class);



    // faq
    Route::resource('faq', FaqController::class)->names('faq');
    Route::get('/faq/delete/{faq}', [FaqController::class, 'delete'])->name('faq.delete');
    Route::get('/faq/getfaq/get', [FaqController::class, 'getFaq'])->name('faq.select');

    // Route::resource('processes', ProcessController::class);
    Route::resource('businesses', BusinessController::class);
    // Route::resource('business', BusinessController::class)->names('business');
    // Route::get('business_select', [BusinessController::class, 'select'])->name('business.select');


    Route::resource('abouts', AboutController::class);

    Route::resource('contents', ContentController::class);
    // Route::resource('achievements', AchievementController::class);
    Route::resource('rewards', RewardController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('youtubes', YoutubeController::class);
    Route::resource('managements', ManagementController::class);

    Route::resource('blog-categories', BlogCategoryController::class)->names('blog-categories');
    Route::get('blog-categories_select', [BlogCategoryController::class, 'select'])->name('blog-categories.select');
    Route::post('blogs/newsletter-bulk', [BlogController::class, 'bulkNewsletter'])->name('blogs.newsletter_bulk');
    Route::get('blogs/newsletter-bulk-preview', [BlogController::class, 'bulkNewsletterPreview'])->name('blogs.newsletter_bulk_preview');
    Route::resource('blogs', BlogController::class);



    Route::resource('trainings', App\Http\Controllers\Admin\TraningController::class);


    Route::get('product-image-delete/{id}', [ProductController::class, 'delete'])->name('product.image.delete');

    Route::post('serive-contents', [ServiceController::class, 'titleStore'])->name('service.content.store');
    Route::post('product-settings', [ProductController::class, 'productTitleStore'])->name('product.setting.store');
    // Route::post('news-settings', [NewsController::class, 'newsSettingStore'])->name('news.setting.store');








    // WhyChooseUs management
    Route::resource('/whychooseus', WhyChooseUsController::class)->names('whychooseus');


    // achivement management
    Route::resource('/achivement', AchivementController::class)->names('achivement');






    // Settings
    Route::get('database/backup', [SettingController::class, 'downloadBackup'])->name('database.backup');
    Route::any('setting-store-update', [SettingController::class, 'store'])->name('setting.store.update');
});



// ================================== News =======================
use App\Http\Controllers\Admin\News\NewsCategoryController;
use App\Http\Controllers\Admin\News\NewsController;
Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin/news', 'as' => 'admin.news.'], function () {
    Route::resource('categories', NewsCategoryController::class)->names('categories');
    Route::get('categories_select', [NewsCategoryController::class, 'select'])->name('categories.select');
    Route::post('newses/newsletter-bulk', [NewsController::class, 'bulkNewsletter'])->name('newses.newsletter_bulk');
    Route::get('newses/newsletter-bulk-preview', [NewsController::class, 'bulkNewsletterPreview'])->name('newses.newsletter_bulk_preview');
    Route::resource('newses', NewsController::class)->names('newses');
    Route::get('newses/{id}/newsletter-preview', [NewsController::class, 'newsletterPreview'])->name('newses.newsletter_preview');

});



// ================================= Mail =======================
use App\Http\Controllers\Admin\Mail\MailSettingController;
use App\Http\Controllers\Admin\Mail\MailTemplateController;
Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/mail', [MailSettingController::class, 'index'])->name('mail.index');
    Route::post('/mail', [MailSettingController::class, 'store'])->name('mail.store');
    Route::post('/mail/test', [MailSettingController::class, 'testMail'])->name('mail.test');
    Route::put('/mail/template/{mailTemplate}', [MailTemplateController::class, 'update'])->name('mail.update');

});


use App\Http\Controllers\Admin\CalenderController;
use App\Http\Controllers\Admin\SubscriberController;
Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin/calender', 'as' => 'admin.calender.'], function () {
    Route::get('/', [CalenderController::class, 'index'])->name('index');
    Route::get('/events', [CalenderController::class, 'events'])->name('events');
    Route::post('/store', [CalenderController::class, 'store'])->name('add');
    Route::put('/update/{id}', [CalenderController::class, 'store'])->name('update');
    Route::post('/calender_update_date_store/{id?}', [CalenderController::class, 'update_date'])->name('update_date');
    Route::delete('/delete/{id}', [CalenderController::class, 'delete'])->name('delete');

    Route::get('/modal/{id?}', [CalenderController::class, 'modal'])->name('modal');
});


use App\Http\Controllers\Admin\EventTypeController;
Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin/calender', 'as' => 'admin.'], function () {
     Route::resource('eventtype', EventTypeController::class)->names('eventtypes');
     Route::get('eventtypes_select', [EventTypeController::class, 'select'])->name('eventtypes.select');
     Route::get('eventtype/import/template', [EventTypeController::class, 'downloadImportTemplate'])->name('eventtypes.import.template');
     Route::post('eventtype/import', [EventTypeController::class, 'bulkImport'])->name('eventtypes.import.bulk');

});

Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('subscribers', SubscriberController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('subscribers/{subscriber}/preview', [SubscriberController::class, 'preview'])->name('subscribers.preview');
    Route::post('subscribers/bulk/status', [SubscriberController::class, 'bulkStatusUpdate'])->name('subscribers.bulk.status');
    Route::post('subscribers/bulk/delete', [SubscriberController::class, 'bulkDelete'])->name('subscribers.bulk.delete');
    Route::get('subscribers/import/template', [SubscriberController::class, 'downloadImportTemplate'])->name('subscribers.import.template');
    Route::post('subscribers/import', [SubscriberController::class, 'bulkImport'])->name('subscribers.import.bulk');
    Route::get('subscribers/export', [SubscriberController::class, 'export'])->name('subscribers.export');
});








// ========================================= Gallery ================================

use App\Http\Controllers\Admin\Gallery\PhotoGalleryController;

Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin/gallery', 'as' => 'admin.gallery.'], function () {
    // Gallery
    Route::resource('photo', PhotoGalleryController::class)->names('photo');

});
// ========================================= Gallery ================================





use App\Http\Controllers\Admin\AdminController;
Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/admin/login/{adminId}', [AdminController::class, 'login'])->name('admin.login');
    Route::put('password-reset/{id}', [AdminController::class, 'passwordReset'])->name('admin.password.reset');
    Route::get('profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('profile', [AdminController::class, 'profileUpdate'])->name('profile.update');
    Route::put('profile/password', [AdminController::class, 'profilePasswordUpdate'])->name('profile.password.update');
    Route::resource('admin', AdminController::class);
    Route::post('admin/{admin}/update_permission', [AdminController::class, 'update_permission'])->name('admin.update.permission');
    Route::get('admin_select', [AdminController::class, 'select'])->name('admin.select');

});




Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    // ================================ Service ================================

    Route::resource('pages', PageController::class);





    // Admin


    // Settings
    Route::get('/settings/{page}', [SettingController::class, 'index'])->name('setting.index');
    Route::get('/settings/{view}/show/update', [SettingController::class, 'view_setting'])->name('setting.view');
    Route::get('/settings/{slug}/{key}/group', [SettingController::class, 'index_group'])->name('setting.index_group');






    // Catche Clear
    Route::get('/clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        return redirect()->back();
    })->name('clear');
});
