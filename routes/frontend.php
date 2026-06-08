<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CalenderController;
use App\Http\Controllers\common_frontend\ContactController;
use App\Http\Controllers\theme1\HomeController;
use Inertia\Inertia;




Route::any('/', [HomeController::class, 'index'])->name('home');

Route::get('/TeamOshe', [HomeController::class, 'TeamOshe'])->name('team-oshe');


Route::get('/OrganizationProfile',[HomeController::class, 'OrganizationProfile'])->name('organization-profile');

Route::get('/OurMissionandVision', [HomeController::class, 'OurMissionandVision'])->name('our-mission-and-vision');

Route::get('/OSHEStrength',[HomeController::class, 'OSHEStrength'])->name('oshe-strength');

Route::get('/OngoingProject', fn () => Inertia::render('OngoingProject'));
Route::get('/PastProject', fn () => Inertia::render('PastProject'));
Route::get('/ProjectPartners', fn () => Inertia::render('ProjectPartners'));
Route::get('/Events', fn () => Inertia::render('Events'));
Route::get('/ajax/events', [CalenderController::class, 'publicEvents'])->name('events.ajax.list');
Route::get('/ajax/event-types', [CalenderController::class, 'publicEventTypes'])->name('events.ajax.types');
Route::get('/project/{slug?}', [HomeController::class, 'projects'])->name('projects');
Route::get('/ajax/project/{slug}', [HomeController::class, 'ajaxProjectsRequest'])->name('projects.ajax.list');

Route::get('/videos', fn () => Inertia::render('Videos'))->name('videos');
Route::get('/ajax/youtube-videos', [HomeController::class, 'ajaxYoutubeVideos'])->name('videos.ajax.list');
Route::get('/photo-gallery', [HomeController::class, 'photoGallery'])->name('photo.gallery');
Route::get('/photo-gallery/{id}', [HomeController::class, 'photoGalleryDetails'])->whereNumber('id')->name('photo.gallery.details');
Route::get('/ajax/photo-gallery', [HomeController::class, 'ajaxPhotoGallery'])->name('photo.gallery.ajax.list');
// Backward-compatible alias for older callers using route('ajax/photo-gallery')
Route::get('/ajax/photo-gallery', [HomeController::class, 'ajaxPhotoGallery'])->name('ajax/photo-gallery');
Route::get('/ajax/sliders', [HomeController::class, 'ajaxSliders'])->name('sliders.ajax.list');
Route::get('/ajax/clients', [HomeController::class, 'ajaxClients'])->name('clients.ajax.list');

Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/ajax/blog', [HomeController::class, 'ajaxBlogRequest'])->name('blog.ajax.list');
Route::get('/blog/{slug}', [HomeController::class, 'blogDetails'])->name('blog.details');



Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/career', fn () => Inertia::render('Career'))->name('career');
Route::post('/contact-store', [ContactController::class,'store'])->name('contact.store');


Route::any('/thankyou', [ContactController::class, 'thankyou'])->name('contact.thankyou');
Route::post('/carrearStore', [ContactController::class,'carrearStore'])->name('carrearStore');

Route::post('subscribe', [ContactController::class, 'subscribe'])->name('subscribe.store');
Route::get('verifysubscribe/{md5email?}', [ContactController::class, 'verifysubscribe'])->name('subscribe.verify');
Route::get('unsubscribe/{md5email?}', [ContactController::class, 'unsubscribe'])->name('subscribe.remove');



// News routes
Route::get('/news', [HomeController::class, 'newsIndex'])->name('news.index');
Route::get('/news/{id}', [HomeController::class, 'newsShow'])->whereNumber('id')->name('news.show');
Route::get('/ajax/news', [HomeController::class, 'ajaxNews'])->name('news.ajax.list');
Route::get('/ajax/news/{newsCategory}', [HomeController::class, 'ajaxLatestNews'])->name('news.ajax.latest');
Route::get('/about-project', [HomeController::class, 'aboutproject'])->name('about-project');



Route::get('/{newsCategory}/{newsId?}', [HomeController::class, 'newsCategory'])
    ->where([
        'newsCategory' => '[^/]+',
        'newsId' => '[0-9]+',
    ])
    ->name('news.legacy');
