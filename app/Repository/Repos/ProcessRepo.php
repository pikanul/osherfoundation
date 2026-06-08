<?php

namespace App\Repository\Repos;

use App\Models\Process;
use App\Repository\Interfaces\ProcessInterface;

class ProcessRepo implements ProcessInterface
{
    public function allLatestProcess()
    {
        return Process::latest('id');
    }
    public function allProcessList($relation, $column, $condition)
    {
        return Process::with($relation)->select($column)->where($condition)->get();
    }
    public function getAnInstance($processId)
    {
        return Process::findOrFail($processId);
    }

    public function createProcess($requestData)
    {
        return Process::create($requestData);
    }

    public function updateProcess($requestData, $processData)
    {
        return $processData->update($requestData);
    }

    public function deleteProcess($processInfo)
    {
        return $processInfo->delete();
    }
}
