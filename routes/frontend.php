<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CalenderController;
use App\Http\Controllers\common_frontend\ContactController;
use App\Http\Controllers\common_frontend\PartnerInquiryController;
use App\Http\Controllers\theme1\HomeController;
use Inertia\Inertia;




Route::any('/', [HomeController::class, 'index'])->name('home');

Route::get('/TeamOshe', [HomeController::class, 'TeamOshe'])->name('team-oshe');


Route::get('/OrganizationProfile',[HomeController::class, 'OrganizationProfile'])->name('organization-profile');
Route::get('/OurImpact', [HomeController::class, 'OurImpact'])->name('our-impact');

Route::get('/OurMissionandVision', [HomeController::class, 'OurMissionandVision'])->name('our-mission-and-vision');

Route::get('/OSHEStrength',[HomeController::class, 'OSHEStrength'])->name('oshe-strength');
Route::get('/oshes-core-values', fn () => Inertia::render('OshesCoreValues'))->name('oshes-core-values');
Route::get('/national-policy-contributions', fn () => Inertia::render('NationalPolicyContributions'))->name('national-policy-contributions');
Route::get('/sectoral-coverage', fn () => Inertia::render('SectorWideFootprint'))->name('sectoral-coverage');

Route::get('/OngoingProject', fn () => Inertia::render('OngoingProject'));
Route::get('/PastProject', fn () => Inertia::render('PastProject'));
Route::get('/ProjectPartners', fn () => Inertia::render('ProjectPartners'));
Route::get('/partner-with-us', fn () => Inertia::render('PartnerWithUs'))->name('partner-with-us');
Route::get('/Partner With Us', fn () => Inertia::render('PartnerWithUs'))->name('partner-with-us.spaced');
Route::post('/partner-with-us/store', [PartnerInquiryController::class, 'store'])->name('partner-with-us.store');
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
Route::get('/career', [HomeController::class, 'career'])->name('career');
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

$whatWeDoPages = [
    'what-we-do/occupational-safety-and-health' => ['occupational_safety_and_health', 'Occupational Safety & Health (OSH)'],
    'what-we-do/labour-rights-decent-work' => ['labour_rights_decent_work', 'Labour Rights & Decent Work'],
    'what-we-do/social-protection' => ['social_protection', 'Social Protection'],
    'what-we-do/environmental-sustainability' => ['environmental_sustainability', 'Environmental Sustainability'],
    'what-we-do/climate-change-just-transition' => ['climate_change_just_transition', 'Climate Change & Just Transition'],
    'what-we-do/trade-union-strengthening' => ['trade_union_strengthening', 'Trade Union Strengthening'],
    'what-we-do/research-advocacy' => ['research_advocacy', 'Research & Advocacy'],
    'what-we-do/capacity-building-training' => ['capacity_building_training', 'Capacity Building & Training'],
];

foreach ($whatWeDoPages as $path => [$pageKey, $title]) {
    Route::get('/' . $path, fn () => Inertia::render('WhatWeDoImagePage', [
        'pageKey' => $pageKey,
        'title' => $title,
    ]))->name('what-we-do.' . str_replace(['what-we-do/', '-'], ['', '_'], $path));
}

Route::get('/sectoral-coverage/rmg-sector', fn () => Inertia::render('RmgSector'))->name('sectoral-coverage.rmg-sector');

$thematicPriorityPages = [
    'thematic-priorities/occupational-safety-health' => ['occupational_safety_health', 'Occupational Safety & Health'],
    'thematic-priorities/human-rights-labour-rights' => ['human_rights_labour_rights', 'Human Rights & Labour Rights'],
    'thematic-priorities/gender-equality-social-inclusion' => ['gender_equality_social_inclusion', 'Gender Equality & Social Inclusion'],
    'thematic-priorities/social-protection' => ['social_protection', 'Social Protection'],
    'thematic-priorities/environmental-sustainability' => ['environmental_sustainability', 'Environmental Sustainability'],
    'thematic-priorities/climate-change-just-transition' => ['climate_change_just_transition', 'Climate Change & Just Transition'],
    'thematic-priorities/responsible-business-conduct' => ['responsible_business_conduct', 'Responsible Business Conduct'],
    'thematic-priorities/worker-participation-social-dialogue' => ['worker_participation_social_dialogue', 'Worker Participation & Social Dialogue'],
];

foreach ($thematicPriorityPages as $path => [$pageKey, $title]) {
    Route::get('/' . $path, fn () => Inertia::render('MenuPlaceholder', [
        'pageKey' => $pageKey,
        'title' => $title,
        'heroOnly' => true,
    ]))->name('thematic-priorities.' . str_replace(['thematic-priorities/', '-'], ['', '_'], $path));
}

$menuPlaceholderPages = [
    'governance-structure' => 'Governance Structure',
    'board-of-trustees' => 'Board of Trustees',
    'executive-team' => 'Executive Team',
    'annual-reports' => 'Annual Reports',
    'sectoral-coverage/leather-tannery-sector' => 'Leather & Tannery Sector',
    'sectoral-coverage/construction-sector' => 'Construction Sector',
    'sectoral-coverage/shipbreaking-sector' => 'Shipbreaking Sector',
    'sectoral-coverage/agriculture-sector' => 'Agriculture Sector',
    'sectoral-coverage/informal-economy-workers' => 'Informal Economy Workers',
    'sectoral-coverage/home-based-workers' => 'Home-Based Workers',
    'sectoral-coverage/waste-management-sector' => 'Waste Management Sector',
    'sectoral-coverage/smes-other-vulnerable-sectors' => 'SMEs & Other Vulnerable Sectors',
    'project-database' => 'Project Database',
    'bangladesh-project-map' => 'Interactive Bangladesh Project Map',
    'project-success-stories' => 'Project Success Stories',
    'development-partners' => 'Development Partners',
    'government-partners' => 'Government Partners',
    'international-networks' => 'International Networks',
    'publications' => 'Publications',
    'newsletter' => 'Newsletter',
    'meeting-reports' => 'Meeting Reports',
    'partner-reports' => 'Partner Reports',
    'training-reports' => 'Training Reports',
    'day-observations' => 'Day Observations',
    'volunteer-opportunities' => 'Volunteer Opportunities',
    'internship-opportunities' => 'Internship Opportunities',
    'consultancy-opportunities' => 'Consultancy Opportunities',
    'office-location' => 'Office Location',
    'feedback-complaints' => 'Feedback & Complaints',
    'newsletter-subscription' => 'Newsletter Subscription',
];

foreach ($menuPlaceholderPages as $path => $title) {
    Route::get('/' . $path, fn () => Inertia::render('MenuPlaceholder', [
        'title' => $title,
        'summary' => 'This page has been added to the final approved OSHE Foundation menu structure.',
    ]))->name('menu.placeholder.' . str_replace(['/', '-'], '.', $path));
}

Route::get('/{newsCategory}/{newsId?}', [HomeController::class, 'newsCategory'])
    ->where([
        'newsCategory' => '[^/]+',
        'newsId' => '[0-9]+',
    ])
    ->name('news.legacy');
