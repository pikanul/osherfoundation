<?php

namespace App\Repository\Interfaces;

interface ProcessInterface
{
    public function allLatestProcess();
    public function allProcessList($relation, $column, $condition);
    public function getAnInstance($processId);
    public function createProcess(array $requestData);
    public function updateProcess(array $requestData, $processData);
    public function deleteProcess($processInfo);
}
