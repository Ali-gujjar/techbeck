<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSignalRequest;
use App\Http\Requests\UpdateSignalRequest;
use App\Services\SignalService;
use Illuminate\Http\JsonResponse;

class SignalController extends Controller
{
    protected $signalService;

    public function __construct(SignalService $signalService)
    {
        $this->signalService = $signalService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->signalService->getAllSignals();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSignalRequest $request): JsonResponse
    {
        return $this->signalService->storeSignal($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        return $this->signalService->getSignalById($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSignalRequest $request, int $id): JsonResponse
    {
        return $this->signalService->updateSignal($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->signalService->deleteSignal($id);
    }
}
