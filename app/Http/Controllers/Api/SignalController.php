<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSignalRequest;
use App\Http\Requests\UpdateSignalRequest;
use App\Http\Resources\SignalResource;
use App\Models\Signal;
use App\Services\SignalService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\ResponseTrait;
use App\Traits\FileUploadTrait;

class SignalController extends Controller
{
    use ResponseTrait, FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $signals = Signal::where('user_name', auth()->user()->name)->get();

            return $this->successResponse('Signals fetched successfully.', [
                'signals' => SignalResource::collection($signals)
            ]);
        }
        catch (\Exception $e) {
            return $this->handleException($e);
        }
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
        try {
            DB::beginTransaction();

            $signal = new Signal();
            $signal->user_name = auth()->user()->name;
            $signal->instrument = $request->instrument;
            $signal->order_type_id = $request->order_type_id;
            $signal->price = $request->price;
            $signal->sl = $request->sl;
            $signal->tp1 = $request->tp1;
            $signal->tp2 = $request->tp2;
            $signal->tp3 = $request->tp3;
            $signal->date_time = $request->date_time;
            $signal->status = $request->status;
            $signal->created_by = auth()->id();


            if ($request->hasFile('image')) {
                if ($signal->image) {
                    $this->deleteFile($signal->image);
                }

                $signal->image = $this->uploadFile($request->file('image'), 'signals');
            }

            $signal->trading_chart_link = $request->trading_chart_link ?? null;
            $signal->save();
            $signal->load('orderType');

            DB::commit();

            return $this->successResponse('Signal created successfully.', [
                'signal' => new SignalResource($signal)
            ]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $signal = Signal::findOrFail($id);

            $signal->load('orderType');
            return $this->successResponse('Signal fetched successfully.', [
                'signal' => new SignalResource($signal)
            ]);
        }
        catch (ModelNotFoundException $e) {
            return $this->errorResponse('Signal not found.', $e->getMessage(), 404);
        }
        catch (\Exception $e) {
            return $this->handleException($e);
        }
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
        try {
            $signal = Signal::findOrFail($id);

            DB::beginTransaction();

            $signal->result_type = $request->result_type;
            $signal->signal_amount = $request->signal_amount;

            if ($request->hasFile('screenshot')) {
                if ($signal->screenshot) {
                    $this->deleteFile($signal->screenshot);
                }

                $signal->screenshot = $this->uploadFile($request->file('screenshot'), 'screenshots');
            }

            $signal->save();
            $signal->load('orderType');

            DB::commit();

            return $this->successResponse('Signal updated successfully.', [
                'signal' => new SignalResource($signal)
            ]);
        }
        catch (ModelNotFoundException $e) {
            return $this->errorResponse('Signal not found.', $e->getMessage(), 404);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $signal = Signal::findOrFail($id);

            if ($signal->image) {
                Storage::disk('public')->delete($signal->image);
            }

            if ($signal->screenshot) {
                Storage::disk('public')->delete($signal->screenshot);
            }

            $signal->delete();

            return $this->successResponse('Signal deleted successfully.', []);
        }
        catch (ModelNotFoundException $e) {
            return $this->errorResponse('Signal not found.', $e->getMessage(), 404);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }
}
