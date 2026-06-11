<?php

namespace App\Http\Controllers\theme1;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Client;
use App\Models\Gallery;
use App\Models\Management;
use App\Models\Media;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Models\Team;
use App\Models\Traning;
use App\Models\Youtube;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;


class HomeController extends Controller
{


    public function index()
    {
        [$missionVisionImage, $missionVisionImageId] = settings('mission_vision_image', '171', 1);
        [$eoshVictimsImage, $eoshVictimsImageId] = settings('eosh_victims_image', '9', 1);
        [$impactImage, $impactImageId] = settings('impact_image', '303', 1);
        $journeyTitle = $this->settingValue('journey_title_text', '401', "OSHE's Journey: Footprint & Strategic Progress");

        if (trim($journeyTitle) === 'Strategic Progress') {
            $journeyTitle = "OSHE's Journey: Footprint & Strategic Progress";
        }


        return Inertia::render('Home', [
            // mission viison
            'mission_title' => settings('mission_title', '171'),
            'vision_title' => settings('vision_title', '171'),
            'mission' => settings('mission_description', '171'),
            'vision' => settings('vision_description', '171'),
            'mission_vision_image' => is_numeric($missionVisionImageId) && (int) $missionVisionImageId > 0
                ? $missionVisionImage
                : asset('assets/mission-vision/mission-vision-default.png'),

            // workers power
            'workpower_title' => settings('workpower_title', '9'),
            'workpower_description' => settings('workpower_description', '9'),

            // eosh victims
            'eosh_victims_title' => settings('eosh_victims_title', '9'),
            'eosh_victims_description' => settings('eosh_victims_description', '9'),
            'eosh_victims_image' => $eoshVictimsImage,
            'eosh_victims_image_id' => $eoshVictimsImageId,

            // partner_title
            'partner_title' => settings('partner_title', '201'),

            // slider_title
            'slider_title' => settings('slider_title', '201'),
            'projects_title' => settings('projects_title', '201'),
            'impact_home_status' => $this->settingValue('impact_home_status', '303', '1'),
            'impact_home_text' => $this->settingValue('impact_home_text', '303', 'Our Impact'),
            'impact_home_link' => $this->settingValue('impact_home_link', '303', '/OurImpact'),
            'impact_image' => is_numeric($impactImageId) && (int) $impactImageId > 0
                ? $impactImage
                : asset('assets/impact/our-impact-default.png'),

            // oshe journey timeline
            'journey_kicker_text' => $this->settingValue('journey_kicker_text', '401', 'Strategic Progress'),
            'journey_title_text' => $journeyTitle,
            'journey_timeline_items_text' => $this->settingValue('journey_timeline_items_text', '401', ''),
        ]);
    }

    protected function settingValue(string $name, string $key, string $default = ''): string
    {
        $setting = Setting::where('name', $name)->where('key', $key)->first();

        if (!$setting || trim((string) $setting->value) === '') {
            return $default;
        }

        return (string) $setting->value;
    }

    public function OSHEStrength()
    {
        return Inertia::render('OSHEStrength', [

            'strength_description' => settings('strength_description', '9'),
            'strength_title' => settings('strength_title', '9'),
        ]);
    }
    public function OrganizationProfile()
    {
        return Inertia::render('OrganizationProfile', [
            'organization_profile_description' => settings('org_prof_ile_description', '221'),
            'organization_profile_title' => settings('org_prof_ile_title', '221'),
        ]);
    }

    public function OurImpact()
    {
        [$impactImage, $impactImageId] = settings('impact_image', '303', 1);

        return Inertia::render('OurImpact', [
            'impact_image' => is_numeric($impactImageId) && (int) $impactImageId > 0
                ? $impactImage
                : asset('assets/impact/our-impact-default.png'),
        ]);
    }

    public function career()
    {
        return Inertia::render('Career', [
            'career_public_jobs_status' => $this->settingValue('career_public_jobs_status', '304', '1'),
            'career_public_jobs_title' => $this->settingValue('career_public_jobs_title', '304', 'Public Jobs'),
            'career_public_jobs_description' => $this->settingValue(
                'career_public_jobs_description',
                '304',
                'Browse current public job openings and apply through the OSHE HRM portal.'
            ),
            'career_public_jobs_link' => $this->settingValue(
                'career_public_jobs_link',
                '304',
                'https://hrm.oshefoundation.com/jobs/openings'
            ),
            'career_public_jobs_links_text' => $this->settingValue(
                'career_public_jobs_links_text',
                '304',
                "OSHE HRM | https://hrm.oshefoundation.com/jobs/openings\nBDJobs | https://bdjobs.com/h/details/1495038?ln=1"
            ),
        ]);
    }

    public function ajaxLatestNews(Request $request, $newsCategory)
    {
        $category = NewsCategory::where('slug', $newsCategory)->first();
        if (!$category) {
            return response()->json(['message' => 'News Category Not Found'], 404);
        }

        $perPage = (int) $request->query('per_page', 5);
        $perPage = max(1, min($perPage, 20));

        $beforePublishDate = $request->query('before_publish_date');
        $beforeId = $request->query('before_id');
        $beforeId = is_numeric($beforeId) ? (int) $beforeId : null;
        $keyword = trim((string) $request->query('keyword', ''));

        $query = News::where('news_category_id', $category->id)
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc');

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('short_descripiton', 'like', "%{$keyword}%")
                    ->orWhere('long_description', 'like', "%{$keyword}%");
            });
        }

        if ($beforePublishDate && $beforeId) {
            $query->where(function ($q) use ($beforePublishDate, $beforeId) {
                $q->where('publish_date', '<', $beforePublishDate)
                    ->orWhere(function ($q) use ($beforePublishDate, $beforeId) {
                        $q->where('publish_date', '=', $beforePublishDate)
                            ->where('id', '<', $beforeId);
                    });
            });
        }

        $items = $query->limit($perPage + 1)->get();
        $hasMore = $items->count() > $perPage;
        $items = $items->take($perPage)->values();
        $nextBeforeId = optional($items->last())->id;
        $nextBeforePublishDate = optional($items->last())->publish_date;

        return response()->json([
            'items' => $items,
            'has_more' => $hasMore,
            'next_before_id' => $nextBeforeId,
            'next_before_publish_date' => $nextBeforePublishDate,
        ]);
    }

    public function OurMissionandVision()
    {
        $setting = function (string $name, string $key, string $fallback) {
            $value = settings($name, $key);
            $placeholder = \Illuminate\Support\Str::title(str_replace('_', ' ', $name));

            return $value === $placeholder ? $fallback : $value;
        };
        [$missionVisionImage, $missionVisionImageId] = settings('mission_vision_image', '171', 1);

        return Inertia::render('OurMissionandVision', [
            'mission_title' => $setting('mission_title', '171', 'Mission'),
            'vision_title' => $setting('vision_title', '171', 'Vision'),
            'mission' => $setting(
                'mission_description',
                '171',
                'To promote and protect the human rights of workers, with a special focus on workplace safety, workers’ health, and environmental protection. OSHE works to eliminate poverty, advance social progress, and build a healthier future for workers by strengthening the capacity, solidarity, and unified voice of the labour movement as a vital contributor to the world of work and sustainable development.'
            ),
            'vision' => $setting(
                'vision_description',
                '171',
                'A world of work where every worker enjoys safe, healthy, dignified, and rights-based workplaces, free from hazards, poverty, and discrimination, while contributing to sustainable development and social progress.'
            ),
            'mission_vision_image' => is_numeric($missionVisionImageId) && (int) $missionVisionImageId > 0
                ? $missionVisionImage
                : asset('assets/mission-vision/mission-vision-default.png'),
        ]);
    }

      public function TeamOshe()
    {
        $teamMembers = Team::query()
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get(['id', 'name', 'designation', 'type', 'upload_id', 'short_des', 'email', 'phone'])
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'designation' => $member->designation,
                    'type' => $member->type,
                    'short_des' => $member->short_des,
                    'email' => $member->email,
                    'phone' => $member->phone,
                    'upload_id' => $member->upload_id,
                    'image_url' => dynamic_asset($member->upload_id ?? 0),
                ];
            });

        return Inertia::render('TeamOshe', [
            'teamMembers' => $teamMembers,
        ]);
    }

    public function newsIndex(Request $request)
    {
        return Inertia::render('News');
    }

    public function ajaxNews(Request $request)
    {
        $perPage = (int) $request->query('per_page', 9);
        $perPage = max(1, min($perPage, 30));

        $beforePublishDate = $request->query('before_publish_date');
        $beforeId = $request->query('before_id');
        $beforeId = is_numeric($beforeId) ? (int) $beforeId : null;

        $keyword = trim((string) $request->query('keyword', ''));
        $categorySlug = strtolower(trim((string) $request->query('category', '')));

        $query = News::query();

        if ($categorySlug !== '') {
            $category = NewsCategory::whereRaw('LOWER(slug) = ?', [$categorySlug])->first();
            if ($category) {
                $query->where('news_category_id', $category->id);
            } else {
                return response()->json([
                    'items' => [],
                    'has_more' => false,
                    'next_before_id' => null,
                    'next_before_publish_date' => null,
                ]);
            }
        }

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('short_descripiton', 'like', "%{$keyword}%")
                    ->orWhere('long_description', 'like', "%{$keyword}%");
            });
        }

        if ($beforePublishDate && $beforeId) {
            $query->where(function ($q) use ($beforePublishDate, $beforeId) {
                $q->where('publish_date', '<', $beforePublishDate)
                    ->orWhere(function ($q) use ($beforePublishDate, $beforeId) {
                        $q->where('publish_date', '=', $beforePublishDate)
                            ->where('id', '<', $beforeId);
                    });
            });
        }

        $items = $query
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit($perPage + 1)
            ->get();

        $hasMore = $items->count() > $perPage;
        $items = $items->take($perPage)->values();

        return response()->json([
            'items' => $items,
            'has_more' => $hasMore,
            'next_before_id' => optional($items->last())->id,
            'next_before_publish_date' => optional($items->last())->publish_date,
        ]);
    }

    public function newsShow(Request $request, $id)
    {
        $showNews = News::find($id);
        if (!$showNews) {
            return Inertia::render('404', ['message' => 'News Not Found']);
        }

        $category = NewsCategory::find($showNews->news_category_id);
        if (!$category) {
            return Inertia::render('404', ['message' => 'News Category Not Found']);
        }

        $latestPerPage = 8;
        $latestNews = News::where('news_category_id', $category->id)
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit($latestPerPage + 1)
            ->get();
        $latestHasMore = $latestNews->count() > $latestPerPage;
        $latestNews = $latestNews->take($latestPerPage)->values();
        $latestBeforeId = optional($latestNews->last())->id;
        $latestBeforePublishDate = optional($latestNews->last())->publish_date;

        $newsCategoryData = [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
        ];

        $newsUrl = url('/news/' . $showNews->id);
        $encodedUrl = urlencode($newsUrl);
        $encodedTitle = urlencode((string) ($showNews->title ?? $category->name));

        $shareLinks = [
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $encodedUrl,
            'twitter' => 'https://twitter.com/intent/tweet?url=' . $encodedUrl . '&text=' . $encodedTitle,
            'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encodedUrl,
            'whatsapp' => 'https://wa.me/?text=' . urlencode((string) ($showNews->title ?? '') . ' ' . $newsUrl),
        ];

        $nextNews = News::where('news_category_id', $category->id)
            ->where(function ($q) use ($showNews) {
                $q->where('publish_date', '>', $showNews->publish_date)
                    ->orWhere(function ($q) use ($showNews) {
                        $q->where('publish_date', '=', $showNews->publish_date)
                            ->where('id', '>', $showNews->id);
                    });
            })
            ->orderBy('publish_date', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        $prevNews = News::where('news_category_id', $category->id)
            ->where(function ($q) use ($showNews) {
                $q->where('publish_date', '<', $showNews->publish_date)
                    ->orWhere(function ($q) use ($showNews) {
                        $q->where('publish_date', '=', $showNews->publish_date)
                            ->where('id', '<', $showNews->id);
                    });
            })
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        return Inertia::render('NewsDetails', [
            'newsCategory' => $newsCategoryData,
            'lastestnews' => $latestNews,
            'lastestnews_has_more' => $latestHasMore,
            'lastestnews_before_id' => $latestBeforeId,
            'lastestnews_before_publish_date' => $latestBeforePublishDate,
            'show_news' => $showNews,
            'share_links' => $shareLinks,
            'next_news' => $nextNews,
            'prev_news' => $prevNews,
            'search_keyword' => '',
        ]);
    }

    public function newsCategory(Request $request, $newsCategory, $newsId = null)
    {
        $normalizedSlug = strtolower(trim(urldecode((string) $newsCategory)));
        $keyword = trim((string) $request->query('keyword', ''));

        $page = Page::whereRaw('LOWER(slug) = ?', [$normalizedSlug])->first();
        if ($page && $page->status == 1 && $page->description && $newsId === null) {
            return Inertia::render('Page', [
                'page' => [
                    'name' => $page->name,
                    'description' => $page->description,
                ],
            ]);
        }


        $category = NewsCategory::whereRaw('LOWER(slug) = ?', [$normalizedSlug])->first();
        if (!$category) {
            return Inertia::render('404', ['message' => 'News Category Not Found']);
        }

        $newsBaseQuery = News::query()
            ->where('news_category_id', $category->id);

        if ($keyword !== '') {
            $newsBaseQuery->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('short_descripiton', 'like', "%{$keyword}%")
                    ->orWhere('long_description', 'like', "%{$keyword}%");
            });
        }

        $newsCategoryData = [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
        ];

        if (!$newsId) {
            $perPage = 9;
            $items = (clone $newsBaseQuery)
                ->orderBy('publish_date', 'desc')
                ->orderBy('id', 'desc')
                ->limit($perPage + 1)
                ->get();

            $hasMore = $items->count() > $perPage;
            $items = $items->take($perPage)->values();
            $beforeId = optional($items->last())->id;
            $beforePublishDate = optional($items->last())->publish_date;

            if ($items->isEmpty() && $keyword !== '') {
                return Inertia::render('404', ['message' => 'No news found for "' . $keyword . '"']);
            }

            return Inertia::render('News', [
                'newsCategory' => $newsCategoryData,
                'initial_news' => $items,
                'news_has_more' => $hasMore,
                'news_before_id' => $beforeId,
                'news_before_publish_date' => $beforePublishDate,
                'search_keyword' => $keyword,
            ]);
        }

        $showNews = News::where('news_category_id', $category->id)
            ->where('id', $newsId)
            ->first();
        if (!$showNews) {
            return Inertia::render('404', ['message' => 'News Not Found']);
        }

        $latestPerPage = 8;
        $latestNews = News::where('news_category_id', $category->id)
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit($latestPerPage + 1)
            ->get();
        $latestHasMore = $latestNews->count() > $latestPerPage;
        $latestNews = $latestNews->take($latestPerPage)->values();
        $latestBeforeId = optional($latestNews->last())->id;
        $latestBeforePublishDate = optional($latestNews->last())->publish_date;

        $newsUrl = url('/' . $category->slug . '/' . $showNews->id);
        $encodedUrl = urlencode($newsUrl);
        $encodedTitle = urlencode((string) ($showNews->title ?? $category->name));

        $shareLinks = [
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $encodedUrl,
            'twitter' => 'https://twitter.com/intent/tweet?url=' . $encodedUrl . '&text=' . $encodedTitle,
            'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encodedUrl,
            'whatsapp' => 'https://wa.me/?text=' . urlencode((string) ($showNews->title ?? '') . ' ' . $newsUrl),
        ];

        // Next news (newer): nearest item after current in (publish_date desc, id desc)
        $nextNews = (clone $newsBaseQuery)
            ->where(function ($q) use ($showNews) {
                $q->where('publish_date', '>', $showNews->publish_date)
                    ->orWhere(function ($q) use ($showNews) {
                        $q->where('publish_date', '=', $showNews->publish_date)
                            ->where('id', '>', $showNews->id);
                    });
            })
            ->orderBy('publish_date', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        // Previous news (older): nearest item before current in (publish_date desc, id desc)
        $prevNews = (clone $newsBaseQuery)
            ->where(function ($q) use ($showNews) {
                $q->where('publish_date', '<', $showNews->publish_date)
                    ->orWhere(function ($q) use ($showNews) {
                        $q->where('publish_date', '=', $showNews->publish_date)
                            ->where('id', '<', $showNews->id);
                    });
            })
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        return Inertia::render('NewsDetails', [
            'newsCategory' => $newsCategoryData,
            'lastestnews' => $latestNews,
            'lastestnews_has_more' => $latestHasMore,
            'lastestnews_before_id' => $latestBeforeId,
            'lastestnews_before_publish_date' => $latestBeforePublishDate,
            'show_news' => $showNews,
            'share_links' => $shareLinks,
            'next_news' => $nextNews,
            'prev_news' => $prevNews,
            'search_keyword' => $keyword,
        ]);
    }


    public function contact()
    {

        return Inertia::render('Contact', [
            'maps' =>  settings('app_map_embaded_url', 9)
        ]);
    }


    public function ajaxBlogRequest(){
        $perPage = (int) request()->query('per_page', 9);
        $perPage = max(1, min($perPage, 30));

        $beforePublishDate = request()->query('before_publish_date');
        $beforeId = request()->query('before_id');
        $beforeId = is_numeric($beforeId) ? (int) $beforeId : null;
        $categoryId = request()->query('category_id');
        $categoryId = is_numeric($categoryId) ? (int) $categoryId : null;
        $keyword = trim((string) request()->query('keyword', ''));

        $query = Blog::query()
            ->where('status', 'active')
            ->whereNotNull('publish_date')
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('short_description', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($beforePublishDate && $beforeId) {
            $query->where(function ($q) use ($beforePublishDate, $beforeId) {
                $q->where('publish_date', '<', $beforePublishDate)
                    ->orWhere(function ($q) use ($beforePublishDate, $beforeId) {
                        $q->where('publish_date', '=', $beforePublishDate)
                            ->where('id', '<', $beforeId);
                    });
            });
        }

        $items = $query->limit($perPage + 1)->get();
        $hasMore = $items->count() > $perPage;
        $items = $items->take($perPage)->values();

        $nextBeforeId = optional($items->last())->id;
        $nextBeforePublishDate = optional($items->last())->publish_date;

        $payloadItems = $items->map(function ($blog) {
            $attachmentId = $blog->attachment_id ?? null;
            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'publish_date' => $blog->publish_date,
                'category_id' => $blog->category_id,
                'short_description' => $blog->short_description,
                'description' => $blog->description,
                'image_url' => dynamic_asset($blog->upload_id),
                'attachment_url' => $attachmentId ? dynamic_asset($attachmentId) : null,
            ];
        });

        return response()->json([
            'items' => $payloadItems,
            'has_more' => $hasMore,
            'next_before_id' => $nextBeforeId,
            'next_before_publish_date' => $nextBeforePublishDate,
        ]);
    }

    public function blog()
    {
        $blogCategories = BlogCategory::query()
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'slug'])
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ];
            })
            ->values();

        return Inertia::render('Blog', [
            'blog_categories' => $blogCategories,
        ]);
    }

    public function blogDetails($slug)
    {
        $blog = Blog::query()
            ->where('status', 'active')
            ->where(function ($query) use ($slug) {
                $query->whereRaw('LOWER(slug) = ?', [strtolower(trim((string) $slug))]);

                if (is_numeric($slug)) {
                    $query->orWhere('id', (int) $slug);
                }
            })
            ->first();

        if (!$blog) {
            return Inertia::render('404', ['message' => 'Blog not found']);
        }

        $relatedBlogs = Blog::query()
            ->where('status', 'active')
            ->whereNotNull('publish_date')
            ->where('id', '!=', $blog->id)
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($item) {
                $attachmentId = $item->attachment_id ?? null;

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'publish_date' => $item->publish_date,
                    'short_description' => $item->short_description,
                    'description' => $item->description,
                    'image_url' => dynamic_asset($item->upload_id),
                    'attachment_url' => $attachmentId ? dynamic_asset($attachmentId) : null,
                ];
            })
            ->values();

        $attachmentId = $blog->attachment_id ?? null;
        $blogPayload = [
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'publish_date' => $blog->publish_date,
            'short_description' => $blog->short_description,
            'description' => $blog->description,
            'image_url' => dynamic_asset($blog->upload_id),
            'attachment_url' => $attachmentId ? dynamic_asset($attachmentId) : null,
        ];

        return Inertia::render('BlogDetails', [
            'blog' => $blogPayload,
            'related_blogs' => $relatedBlogs,
        ]);
    }








    public function ajaxProjectsRequest(Request $request, $slug)
    {
        $category = ProjectCategory::where('slug', $slug)->where('status', 1)->first();
        if (!$category) {
            return response()->json(['message' => 'Project Category Not Found'], 404);
        }

        $perPage = (int) $request->query('per_page', 20);
        $perPage = max(1, min($perPage, 50));

        $beforeId = $request->query('before_id');
        $beforeId = is_numeric($beforeId) ? (int) $beforeId : null;

        $query = Project::query()
            ->where('project_category_id', $category->id)
            ->orderBy('id', 'desc');

        if ($beforeId) {
            $query->where('id', '<', $beforeId);
        }

        $items = $query->limit($perPage + 1)->get();
        $hasMore = $items->count() > $perPage;
        $items = $items->take($perPage)->values();
        $nextBeforeId = optional($items->last())->id;

        $payloadItems = $items->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'slug' => $project->slug ?? null,
                'duration' => $project->duration ?? null,
                'funded_by' => $project->funded_by ?? null,
                'project_status' => $project->project_status ?? null,
                'description' => $project->description ?? null,
                'image_url' => dynamic_asset($project->upload_id ?? 0),
            ];
        });

        return response()->json([
            'items' => $payloadItems,
            'has_more' => $hasMore,
            'next_before_id' => $nextBeforeId,
        ]);
    }

    public function projects(Request $request, $slug = null)
    {
        $categories = ProjectCategory::query()
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get(['id', 'name', 'slug']);

        $activeCategory = null;
        if ($slug) {
            $activeCategory = $categories->firstWhere('slug', $slug);
            if (!$activeCategory) {
                return Inertia::render('404', ['message' => 'Project Category Not Found']);
            }
        } else {
            $activeCategory = $categories->first();
        }

        $initialItems = collect();
        $initialHasMore = false;
        $initialBeforeId = null;

        if ($activeCategory) {
            $perPage = 20;
            $initialItems = Project::query()
                ->where('project_category_id', $activeCategory->id)
                ->orderBy('id', 'desc')
                ->limit($perPage + 1)
                ->get();
            $initialHasMore = $initialItems->count() > $perPage;
            $initialItems = $initialItems->take($perPage)->values();
            $initialBeforeId = optional($initialItems->last())->id;

            $initialItems = $initialItems->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'slug' => $project->slug ?? null,
                    'duration' => $project->duration ?? null,
                    'funded_by' => $project->funded_by ?? null,
                    'project_status' => $project->project_status ?? null,
                    'description' => $project->description ?? null,
                    'image_url' => dynamic_asset($project->upload_id ?? 0),
                ];
            });
        }

        return Inertia::render('Project', [
            'project_categories' => $categories,
            'active_category' => $activeCategory,
            'initial_projects' => $initialItems,
            'projects_has_more' => $initialHasMore,
            'projects_before_id' => $initialBeforeId,
        ]);
    }

    public function ajaxYoutubeVideos(Request $request)
    {
        $perPage = (int) $request->query('per_page', 6);
        $perPage = max(1, min($perPage, 50));

        $beforeId = $request->query('before_id');
        $beforeId = is_numeric($beforeId) ? (int) $beforeId : null;

        $query = Youtube::query()
            ->where('status', 1)
            ->orderBy('id', 'desc');

        if ($beforeId) {
            $query->where('id', '<', $beforeId);
        }

        $items = $query->limit($perPage + 1)->get(['id', 'video_url', 'title', 'description', 'upload_id', 'created_at']);
        $hasMore = $items->count() > $perPage;
        $items = $items->take($perPage)->values();
        $nextBeforeId = optional($items->last())->id;

        $payloadItems = $items->map(function ($video) {
            return [
                'id' => $video->youtube_video_id ?: $video->video_url,
                'video_id' => $video->youtube_video_id,
                'video_url' => $video->video_url,
                'embed_url' => $video->youtube_embed_url,
                'title' => $video->title,
                'description' => $video->description,
                'image_url' => dynamic_asset($video->upload_id ?? 0),
                'created_at' => optional($video->created_at)->toISOString(),
            ];
        });

        return response()->json([
            'items' => $payloadItems,
            'has_more' => $hasMore,
            'next_before_id' => $nextBeforeId,
        ]);
    }

    public function photoGallery()
    {
        $perPage = 24;
        $items = Gallery::query()
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->limit($perPage + 1)
            ->get(['id', 'name', 'upload_id', 'created_at']);

        $hasMore = $items->count() > $perPage;
        $items = $items->take($perPage)->values();

        $payloadItems = $items->map(function ($photo) {
            return [
                'id' => $photo->id,
                'name' => $photo->name ?: ('Photo #' . $photo->id),
                'image_url' => dynamic_asset($photo->upload_id ?? 0),
                'created_at' => optional($photo->created_at)->toISOString(),
            ];
        });

        return Inertia::render('PhotoGallery', [
            'initial_photos' => $payloadItems,
            'photos_has_more' => $hasMore,
            'photos_before_id' => optional($items->last())->id,
        ]);
    }

    public function ajaxPhotoGallery(Request $request)
    {
        $perPage = (int) $request->query('per_page', 24);
        $perPage = max(1, min($perPage, 60));

        $beforeId = $request->query('before_id');
        $beforeId = is_numeric($beforeId) ? (int) $beforeId : null;

        $query = Gallery::query()
            ->where('status', 1)
            ->orderBy('id', 'desc');

        if ($beforeId) {
            $query->where('id', '<', $beforeId);
        }

        $items = $query->limit($perPage + 1)->get(['id', 'name', 'upload_id', 'created_at']);
        $hasMore = $items->count() > $perPage;
        $items = $items->take($perPage)->values();

        $payloadItems = $items->map(function ($photo) {
            return [
                'id' => $photo->id,
                'name' => $photo->name ?: ('Photo #' . $photo->id),
                'image_url' => dynamic_asset($photo->upload_id ?? 0),
                'created_at' => optional($photo->created_at)->toISOString(),
            ];
        });

        return response()->json([
            'items' => $payloadItems,
            'has_more' => $hasMore,
            'next_before_id' => optional($items->last())->id,
        ]);
    }

    public function photoGalleryDetails($id)
    {
        $photo = Gallery::query()
            ->where('status', 1)
            ->where('id', $id)
            ->first();

        if (!$photo) {
            return Inertia::render('404', ['message' => 'Photo not found']);
        }

        $relatedPhotos = Gallery::query()
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->limit(12)
            ->get(['id', 'name', 'upload_id', 'created_at'])
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name ?: ('Photo #' . $item->id),
                    'image_url' => dynamic_asset($item->upload_id ?? 0),
                    'created_at' => optional($item->created_at)->toISOString(),
                ];
            })
            ->values();

        $nextPhoto = Gallery::query()
            ->where('status', 1)
            ->where('id', '<', $photo->id)
            ->orderBy('id', 'desc')
            ->first(['id']);

        $prevPhoto = Gallery::query()
            ->where('status', 1)
            ->where('id', '>', $photo->id)
            ->orderBy('id', 'asc')
            ->first(['id']);

        return Inertia::render('PhotoGalleryDetails', [
            'photo' => [
                'id' => $photo->id,
                'name' => $photo->name ?: ('Photo #' . $photo->id),
                'image_url' => dynamic_asset($photo->upload_id ?? 0),
                'created_at' => optional($photo->created_at)->toISOString(),
            ],
            'related_photos' => $relatedPhotos,
            'next_photo_id' => $nextPhoto?->id,
            'prev_photo_id' => $prevPhoto?->id,
        ]);
    }

    public function ajaxSliders(Request $request)
    {
        $perPage = (int) $request->query('per_page', 4);
        $perPage = max(1, min($perPage, 20));

        $items = Slider::query()
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->limit($perPage)
            ->get(['id', 'title', 'sub_title', 'upload_id', 'link_text', 'created_at']);

        $payloadItems = $items->map(function ($slider) {
            return [
                'id' => $slider->id,
                'title' => $slider->title,
                'sub_title' => $slider->sub_title,
                'link_text' => $slider->link_text,
                'image_url' => dynamic_asset($slider->upload_id ?? 0),
                'created_at' => optional($slider->created_at)->toISOString(),
            ];
        });

        return response()->json([
            'items' => $payloadItems,
        ]);
    }

    public function ajaxClients(Request $request)
    {
        $perPage = (int) $request->query('per_page', 7);
        $perPage = max(1, min($perPage, 50));

        $items = Client::query()
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->limit($perPage)
            ->get(['id', 'name', 'company_name', 'description', 'upload_id', 'created_at']);

        $payloadItems = $items->map(function ($client) {
            return [
                'id' => $client->id,
                'name' => $client->name,
                'company_name' => $client->company_name,
                'description' => $client->description,
                'image_url' => dynamic_asset($client->upload_id ?? 0),
                'created_at' => optional($client->created_at)->toISOString(),
            ];
        });

        return response()->json([
            'items' => $payloadItems,
        ]);
    }

    public function aboutproject()
    {
        return Inertia::render('AboutProject', [
            'about_project1' => settings('about_project_image_1', 171),
            'about_project2' => settings('about_project_image_2', 171),

        ]);
    }



}
