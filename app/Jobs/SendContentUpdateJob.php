<?php

namespace App\Jobs;

use App\Models\Blog;
use App\Models\News;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SendContentUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;
    private const CHUNK_SIZE = 150;
    private const CHUNK_DELAY_SECONDS = 30;
    private const PER_EMAIL_PAUSE_MS = 200;

    public function __construct(
        public string $type,
        public int $contentId
    ) {}

    public function handle(): void
    {
        $payload = $this->buildPayload();
        if (!$payload) {
            return;
        }

        $emails = Subscriber::query()
            ->where('status', 1)
            ->whereNotNull('email')
            ->pluck('email')
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->values();

        if ($emails->isEmpty()) {
            return;
        }

        $chunks = $emails->chunk(self::CHUNK_SIZE)->values();

        foreach ($chunks as $index => $chunk) {
            SendContentUpdateChunkJob::dispatch(
                $payload,
                $chunk->values()->all(),
                self::PER_EMAIL_PAUSE_MS
            )->delay(now()->addSeconds($index * self::CHUNK_DELAY_SECONDS));
        }

        Log::info('Queued content update email chunks', [
            'type' => $this->type,
            'content_id' => $this->contentId,
            'total_recipients' => $emails->count(),
            'chunk_size' => self::CHUNK_SIZE,
            'chunks' => $chunks->count(),
            'chunk_delay_seconds' => self::CHUNK_DELAY_SECONDS,
        ]);

        $this->sendPushNotificationHook($payload);
    }

    private function buildPayload(): ?array
    {
        if ($this->type === 'news') {
            $news = News::with('category')->find($this->contentId);
            if (!$news) {
                return null;
            }

            $previousNewsByCategory = News::with('category')
                ->where('id', '!=', $news->id)
                ->orderByDesc('publish_date')
                ->orderByDesc('id')
                ->take(20)
                ->get()
                ->groupBy(fn ($item) => $item->category?->name ?? 'Other')
                ->map(function ($items) {
                    return $items->map(function ($item) {
                        $url = $this->buildNewsUrl($item);

                        return [
                            'id' => $item->id,
                            'title' => $item->title,
                            'summary' => Str::limit((string) ($item->short_descripiton ?? ''), 140),
                            'image_url' => function_exists('dynamic_asset') ? dynamic_asset($item->news_image ?? 0) : null,
                            'publish_date' => $item->publish_date ? \Carbon\Carbon::parse($item->publish_date)->format('d M Y') : null,
                            'url' => $url,
                        ];
                    })->values()->all();
                })
                ->toArray();

            $url = $this->buildNewsUrl($news);

            return [
                'type' => 'news',
                'title' => $news->title,
                'category' => $news->category?->name ?? 'News',
                'summary' => $news->short_descripiton ?: 'A new news update has been published.',
                'image_url' => function_exists('dynamic_asset') ? dynamic_asset($news->news_image ?? 0) : null,
                'publish_date' => $news->publish_date ? \Carbon\Carbon::parse($news->publish_date)->format('d M Y') : null,
                'newsletter_banner' => function_exists('settings') ? settings('newslater_banner_image', 27) : null,
                'publish_label' => now()->format('F Y'),
                'url' => $url,
                'previous_news_by_category' => $previousNewsByCategory,
            ];
        }

        if ($this->type === 'blog') {
            $blog = Blog::find($this->contentId);
            if (!$blog) {
                return null;
            }

            return [
                'type' => 'blog',
                'title' => $blog->title,
                'summary' => $blog->short_description ?: 'A new blog update has been published.',
                'url' => route('blog'),
            ];
        }

        return null;
    }

    private function buildNewsUrl(News $news): string
    {
        if (Route::has('news.show')) {
            return route('news.show', ['id' => $news->id]);
        }

        if ($news->category && Route::has('news.legacy')) {
            return route('news.legacy', [
                'newsCategory' => $news->category->slug,
                'newsId' => $news->id,
            ]);
        }

        return url('/news/' . $news->id);
    }

    private function sendPushNotificationHook(array $payload): void
    {
        // Push integration hook for queue worker.
        // Wire your FCM/WebPush provider here when tokens/provider are available.
        Log::info('Push notification hook triggered for content update', [
            'type' => $payload['type'] ?? null,
            'title' => $payload['title'] ?? null,
            'url' => $payload['url'] ?? null,
        ]);
    }
}
