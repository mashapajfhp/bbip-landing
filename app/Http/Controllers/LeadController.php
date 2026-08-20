<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Services\GoogleSheetsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    public function __construct(private GoogleSheetsService $sheetsService)
    {
    }

    public function store(StoreLeadRequest $request): JsonResponse
    {
        try {
            $this->sheetsService->appendLead($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Your enquiry has been submitted successfully. The BBIP team will contact you shortly.',
            ]);
        } catch (\Exception $e) {
            Log::error('Lead submission failed', [
                'error' => $e->getMessage(),
                'email' => $request->email,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'We couldn\'t submit your request right now. Please try again.',
            ], 500);
        }
    }
}
