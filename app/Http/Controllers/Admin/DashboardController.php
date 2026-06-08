<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'queueWorkerStatus' => $this->getQueueWorkerStatus(),
        ]);
    }

    public function queueWorkerStart(Request $request)
    {
        $status = $this->getQueueWorkerStatus();
        if ($status['running']) {
            return redirect()->route('admin.dashboard')->with('status', 'Queue worker is already running.');
        }

        $phpBinary = PHP_BINARY ?: 'php';
        $artisan = base_path('artisan');
        $started = false;

        try {
            if (stripos(PHP_OS_FAMILY, 'Windows') !== false) {
                $command = 'start /B "" "' . $phpBinary . '" "' . $artisan . '" queue:work --sleep=3 --tries=3 > NUL 2>&1';
                @pclose(@popen($command, 'r'));
                $started = true;
            } else {
                $command = 'nohup ' . escapeshellarg($phpBinary) . ' ' . escapeshellarg($artisan) . ' queue:work --sleep=3 --tries=3 > /dev/null 2>&1 &';
                @exec($command);
                $started = true;
            }
        } catch (\Throwable $e) {
            $started = false;
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('status', $started ? 'Queue worker start command sent.' : 'Unable to start queue worker from web process.');
    }

    public function queueWorkerStop(Request $request)
    {
        try {
            Artisan::call('queue:restart');
            return redirect()->route('admin.dashboard')->with('status', 'Queue worker restart signal sent (workers will stop gracefully).');
        } catch (\Throwable $e) {
            return redirect()->route('admin.dashboard')->with('status', 'Unable to stop queue worker.');
        }
    }

    public function queueWorkerStatus(Request $request)
    {
        return response()->json($this->getQueueWorkerStatus());
    }

    private function getQueueWorkerStatus(): array
    {
        $processCount = 0;

        try {
            if (stripos(PHP_OS_FAMILY, 'Windows') !== false) {
                $output = @shell_exec('wmic process where "name=\'php.exe\'" get CommandLine 2>NUL');
                if (is_string($output) && $output !== '') {
                    preg_match_all('/artisan\s+queue:work/i', $output, $matches);
                    $processCount = count($matches[0] ?? []);
                }
            } else {
                $output = @shell_exec("ps -ef | grep 'artisan queue:work' | grep -v grep");
                $lines = array_values(array_filter(explode("\n", (string) $output)));
                $processCount = count($lines);
            }
        } catch (\Throwable $e) {
            $processCount = 0;
        }

        $pendingJobs = Schema::hasTable('jobs') ? (int) DB::table('jobs')->count() : null;
        $failedJobs = Schema::hasTable('failed_jobs') ? (int) DB::table('failed_jobs')->count() : null;

        return [
            'running' => $processCount > 0,
            'process_count' => $processCount,
            'pending_jobs' => $pendingJobs,
            'failed_jobs' => $failedJobs,
            'checked_at' => now()->toDateTimeString(),
        ];
    }
}

