<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientContact;
use App\Models\ClientSearchRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClientController extends Controller
{

    public $client;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $clients = Client::with('clientContact')->latest()->paginate(config('setting.pagination_number'));
            return response()->json([
                'status' => true,
                'message' => 'Data save successful',
                'data' => view('client.index', [
                    'clients' => $clients
                ])->render(),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Something wrong',
                'data' => '',
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data = [
            'form' => view('client.create')->render(),
            'form_title' => 'Create Client details',
        ];

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:client_contacts|max:50|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'mobile' => 'required|unique:client_contacts|max:15|regex:/^\+(?:[0-9] ?){6,14}[0-9]$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
                'data' => '',
                'is_replace' => false,
            ]);
        }

        try {

            DB::transaction(function () use ($request) {

                $this->client = Client::create([
                    'uuid' => Str::uuid(),
                    'name' => $request->name,
                    'is_active' => $request->is_active ?? 2
                ]);

                $this->client->clientContact()->create([
                    'email' => $request->email,
                    'mobile' => $request->mobile
                ]);
            });

            return response()->json([
                'status' => true,
                'message' => 'Data save successful',
                'data' => view('client.last-data', [
                    'client' => $this->client
                ])->render(),
                'is_replace' => false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => [$e->getMessage()],
                'data' => '',
                'is_replace' => false,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function edit(string $uuid)
    {
        $client = Client::with('clientContact')->where('uuid', $uuid)->first();

        $data = [
            'form' => view('client.edit', [
                'client' => $client
            ])->render(),
            'form_title' => 'Edit Client details',
        ];

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $client = Client::with('clientContact')->where('uuid', $uuid)->first();

        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => ['Data is not valid'],
                'data' => '',
                'is_replace' => true,
                'uuid' => ''
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:client_contacts,email,' . $client->clientContact->id . '|max:50|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'mobile' => 'required|unique:client_contacts,mobile,' . $client->clientContact->id . '|max:15|regex:/^\+(?:[0-9] ?){6,14}[0-9]$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
                'data' => '',
                'is_replace' => true,
                'uuid' => ''
            ]);
        }
        try {

            DB::transaction(function () use ($request, $client) {
                $client->update([
                    'name' => $request->name,
                    'is_active' => $request->is_active ?? 2
                ]);

                $client->clientContact()->update([
                    'email' => $request->email,
                    'mobile' => $request->mobile
                ]);

                $this->client = $client;
            });

            return response()->json([
                'status' => true,
                'message' => 'Data save successful',
                'data' => view('client.last-data', [
                    'client' => $this->client
                ])->render(),
                'is_replace' => true,
                'uuid' => $this->client->uuid,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => [$e->getMessage()],
                'data' => '',
                'is_replace' => true,
                'uuid' => ''
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $uuid)
    {
        $client = Client::where('uuid', $uuid)->first();

        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'Data is not valid',
                'data' => '',
            ]);
        }

        try {
            $client->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data delete successful',
                'data' => '',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => '',
            ]);
        }
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {

            try {
                if ($request->has('search_keyword')) {

                    $search_keyword = $request->search_keyword;

                    $keywords_data = [];

                    $keywords = ClientSearchRecord::select('keyword')
                        ->where('keyword', 'like', "%{$search_keyword}%")
                        ->take(10)
                        ->orderBy('keyword', 'asc')
                        ->orderBy('popularity_count', 'desc')
                        ->get();

                    if ($keywords->count()) {
                        foreach ($keywords as $keyword) {
                            $keywords_data[] = $keyword->keyword;
                        }
                    }else{
                        $clients = Client::select('name')
                        ->where(function(Builder $builder) use ($search_keyword){
                            return $builder->where('name', 'like', "%{$search_keyword}%")
                            ->orWhereHas('clientContacts', function(Builder $builder) use ($search_keyword){
                                return $builder->where('email', 'like', "%{$search_keyword}%")->orWhere('mobile', 'like', "%{$search_keyword}%");
                            });
                        })
                        ->orderBy('popularity_count', 'desc')
                        ->orderBy('name', 'asc')
                        ->take(10)
                        ->get();

                        if ($clients->count()) {
                            foreach ($clients as $client) {
                                $keywords_data[] = $client->name;
                            }
                        }
                    }

                    return response()->json([
                        'status' => true,
                        'message' => '',
                        'data' => $keywords_data,
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Search keyword missing',
                        'data' => '',
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                    'data' => '',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Something wrong',
                'data' => '',
            ]);
        }
    }

    public function searchData(Request $request)
    {
        if ($request->ajax()) {

            $search_keyword = $request->search_data;

            $clients = Client::where(function(Builder $builder) use ($search_keyword){
                return $builder->where('name', 'like', "%{$search_keyword}%")
                ->orWhereHas('clientContacts', function(Builder $builder) use ($search_keyword){
                    return $builder->where('email', 'like', "%{$search_keyword}%")->orWhere('mobile', 'like', "%{$search_keyword}%");
                });
            })
            ->orderBy('popularity_count', 'desc')
            ->orderBy('name', 'asc')->paginate(config('setting.pagination_number'));

            if($clients->count()){
                foreach($clients as $client){
                    DB::transaction(function() use ($search_keyword, $client){
                        $clientSearchRecord = ClientSearchRecord::where([
                            ['keyword', '=', $search_keyword],
                            ['client_id', '=', $client->id],
                        ])->first();

                        if($clientSearchRecord){
                            $clientSearchRecord->increment('popularity_count', 1);
                        }else{
                            ClientSearchRecord::create([
                                'keyword' => $search_keyword,
                                'client_id' => $client->id,
                                'popularity_count' => 1
                            ]);
                        }
                        $client->increment('popularity_count', 1);
                    });
                }
            }            

            return response()->json([
                'status' => true,
                'message' => 'Data save successful',
                'data' => view('client.index', [
                    'clients' => $clients
                ])->render(),
            ]);

        } else {
            return response()->json([
                'status' => false,
                'message' => 'Something wrong',
                'data' => '',
            ]);
        }
    }
}
