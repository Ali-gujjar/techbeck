<?php

namespace App\Services;

use App\Models\Signal;
use App\Http\Resources\SignalResource;
use App\Traits\FileUploadTrait;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SignalService
{
    use ResponseTrait, FileUploadTrait;

    public function getAllSignals()
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

    public function storeSignal($request)
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

    public function getSignalById($id)
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

    public function updateSignal($request, $id)
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

    public function deleteSignal($id)
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
