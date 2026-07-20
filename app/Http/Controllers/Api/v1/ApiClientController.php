<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Resources\ClientResource;
use Illuminate\Support\Str;

class ApiClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('cin', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('client_type')) {
            $query->where('client_type', $request->query('client_type'));
        }

        if ($request->has('city')) {
            $query->where('city', $request->query('city'));
        }

        $clients = $query->latest()->paginate($request->query('per_page', 15));

        return ClientResource::collection($clients);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'client_type' => 'required|in:individual,company',
            'cin' => 'nullable|string|max:50',
            'passport' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:50',
            'whatsapp_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'date_of_birth' => 'nullable|date',
            'profession' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'succursale_id' => 'nullable|integer',
        ]);

        $validated['uuid'] = (string) Str::uuid();
        $validated['created_by'] = auth()->id();

        $client = Client::create($validated);

        return new ClientResource($client);
    }
}
