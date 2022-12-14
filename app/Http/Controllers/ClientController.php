<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientContact;
use App\Models\ClientSearchRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
            $clients = Client::with('clientContact')
                ->where([
                    ['is_active', '=', Client::IS_ACTIVATED_YES],
                    ['is_archived', '=', false]
                ])
                ->latest()
                ->paginate(config('setting.pagination_number'));

            $data = [
                'page_title' => 'Dashboard',
                'page_data' => view('client.index', [
                    'clients' => $clients
                ])->render()
            ];

            return response()->json([
                'status' => true,
                'message' => '',
                'data' => $data,
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
            Cache::flush();
            return response()->json([
                'status' => true,
                'message' => 'Data saved successfully',
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
                'uuid' => ''
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
                'data' => '',
                'uuid' => ''
            ]);
        }
        try {

            $client->update([
                'name' => $request->name,
                'is_active' => $request->is_active ?? 2
            ]);
            Cache::flush();
            
            return response()->json([
                'status' => true,
                'message' => 'Data saved successfully',
                'data' => view('client.last-data', [
                    'client' => $client
                ])->render(),
                'uuid' => $client->uuid,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => [$e->getMessage()],
                'data' => '',
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

    public function archived(string $uuid)
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
            $client->update([
                'is_archived' => 1
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data archived successful',
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

                    $record_data = [];
                    $client_data = [];

                    $keywords = ClientSearchRecord::whereHas('client', function (Builder $builder) {
                        return $builder->where('is_active', Client::IS_ACTIVATED_YES);
                    })
                        ->select('keyword')
                        ->where('keyword', 'like', "%{$search_keyword}%")
                        ->distinct()
                        ->take(10)
                        ->orderBy('updated_at', 'desc')
                        ->orderBy('popularity_count', 'desc')
                        ->get();

                    if ($keywords->count()) {
                        foreach ($keywords as $keyword) {
                            $record_data[] = $keyword->keyword;
                        }
                    }

                    $clients = Client::select('name')
                        ->where('is_active', Client::IS_ACTIVATED_YES)
                        ->where(function (Builder $builder) use ($search_keyword) {
                            return $builder->where('name', 'like', "%{$search_keyword}%")
                                ->orWhereHas('clientContacts', function (Builder $builder) use ($search_keyword) {
                                    return $builder->where('email', 'like', "%{$search_keyword}%")->orWhere('mobile', 'like', "%{$search_keyword}%");
                                });
                        })
                        ->orderBy('updated_at', 'desc')
                        ->orderBy('name', 'asc')
                        ->take(10)
                        ->get();

                    if ($clients->count()) {
                        foreach ($clients as $client) {
                            $client_data[] = $client->name;
                        }
                    }

                    $keywords_data = array_unique(array_merge($record_data, $client_data));
                    return response()->json([
                        'status' => true,
                        'message' => '',
                        'data' => view('client.recent_search', [
                            'search_records' => $keywords_data
                        ])->render(),
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

            $clients = Cache::get($search_keyword);

            if (empty($clients)) {
                $clients = Cache::remember($search_keyword, 300, function () use ($search_keyword) {
                    return Client::where([
                        ['is_active', '=', Client::IS_ACTIVATED_YES],
                        ['is_archived', '=', false]
                    ])
                        ->where(function (Builder $builder) use ($search_keyword) {
                            return $builder->where('name', 'like', "%{$search_keyword}%")
                                ->orWhereHas('clientContacts', function (Builder $builder) use ($search_keyword) {
                                    return $builder->where('email', 'like', "%{$search_keyword}%")->orWhere('mobile', 'like', "%{$search_keyword}%");
                                });
                        })
                        ->orderBy('popularity_count', 'desc')
                        ->orderBy('name', 'asc')->paginate(config('setting.pagination_number'));
                });
            }

            if ($clients->count()) {
                foreach ($clients as $client) {
                    DB::transaction(function () use ($search_keyword, $client) {
                        $clientSearchRecord = ClientSearchRecord::where([
                            ['keyword', '=', $search_keyword],
                            ['client_id', '=', $client->id],
                        ])->first();

                        if ($clientSearchRecord) {
                            $clientSearchRecord->increment('popularity_count', 1);
                        } else {

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
                'message' => 'Data saved successfully',
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

    public function searchHistory(Request $request)
    {
        if ($request->ajax()) {

            $search_records = ClientSearchRecord::with('client')
                ->orderBy('popularity_count', 'desc')
                ->orderBy('updated_at', 'desc')
                ->paginate(config('setting.pagination_number'));
            
            $page_file = 'client.search_history';
            if($request->load_more){
                $page_file = 'client.history_load_more_data';
            }

            $data = [
                'page_title' => 'Search History',
                'page_data' => view($page_file, [
                    'search_records' => $search_records
                ])->render()
            ];

            return response()->json([
                'status' => true,
                'message' => '',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Something wrong',
                'data' => '',
            ]);
        }
    }

    public function archivedList(Request $request)
    {
        if ($request->ajax()) {
            $clients = Client::with('clientContact')
                ->where('is_archived', true)
                ->latest()
                ->paginate(config('setting.pagination_number'));
            
            $data = [
                'page_title' => 'Archived list',
                'page_data' => view('client.archived_inactive_list', [
                    'clients' => $clients
                ])->render()
            ];

            return response()->json([
                'status' => true,
                'message' => '',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Something wrong',
                'data' => '',
            ]);
        }
    }

    public function inactiveList(Request $request)
    {
        if ($request->ajax()) {
            $clients = Client::with('clientContact')
                ->where('is_active', Client::IS_ACTIVATED_NO)
                ->latest()
                ->paginate(config('setting.pagination_number'));
            
            $data = [
                'page_title' => 'Inactive list',
                'page_data' => view('client.archived_inactive_list', [
                    'clients' => $clients
                ])->render()
            ];

            return response()->json([
                'status' => true,
                'message' => '',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Something wrong',
                'data' => '',
            ]);
        }
    }

    public function recentSearch(Request $request)
    {
        if ($request->ajax()) {

            $search_records = ClientSearchRecord::select('keyword')->whereHas('client', function(Builder $builder){
                return $builder->where([
                    ['is_active', '=', Client::IS_ACTIVATED_YES],
                    ['is_archived', '=', false]
                ]);
            })
            ->orderBy('updated_at', 'desc')
            ->distinct()
            ->limit(5)
            ->pluck('keyword')
            ->toArray();
            
            return response()->json([
                'status' => true,
                'message' => '',
                'data' => view('client.recent_search', [
                    'search_records' => $search_records
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
