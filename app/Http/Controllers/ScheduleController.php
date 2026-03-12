<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\Announcements;
use App\Models\Links;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $schedules = Schedule::with([
            'announcement:id,title,content',
            'link:id,title,url'
        ])->select('id', 'link_id', 'announcement_id', 'schedule', 'type', 'is_active')->orderBy('is_active', 'desc')
            ->get();

        return view('form_page', compact('schedules'));
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $scheduleData = [
            'schedule' => $request->schedule,
            'type'     => $request->type,
            'is_active' => $request->is_active
        ];

        $request->validate([
            'schedule' => 'required|date',
            'type'     => 'required|string',
        ]);

        if ($request->type === 'announcement') {
            $validated = $request->validate([
                'title'   => 'required|string',
                'content' => 'required|string',
            ]);

            $announcement = Announcements::create($validated);
            $scheduleData['announcement_id'] = $announcement->id;
        }
        if ($request->type === 'link') {
            $validated = $request->validate([
                'title' => 'required|string',
                'url'   => 'required|url',
            ]);

            $link = Links::create($validated);
            $scheduleData['link_id'] = $link->id;
        }

        $schedule = Schedule::create($scheduleData);

        if (request()->expectsJson()) {
            return response()->json($schedule, 201);
        }

        return redirect()->back()->with('success', 'Schedule created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $schedule->update([
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->back()->with('success', 'Schedule updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        //
    }

    // function apiData()
    // {
    //     $schedules = Schedule::with([
    //         'announcement:id,title,content',
    //         'link:id,title,url'
    //     ])->select('id', 'link_id', 'announcement_id', 'schedule', 'type')->where('is_active', 1)->get();
    //     $query = Schedule::where('is_active', 1);
    //     $data = now();
    //     $link = (clone $query)->select('links.title', 'links.url')
    //         ->where('schedule', $data->format('Y-m-d'))
    //         ->join('links', 'links.id', 'schedules.link_id')
    //         ->where('type', 'link')->get();
    //     $announcement = (clone $query)->select('announcements.title', 'announcements.content')
    //         ->join('announcements', 'announcements.id', 'schedules.announcement_id')
    //         ->where('type', 'announcement')->get();
    //     // return compact('link', 'announcement');
    //     return response(compact('link', 'announcement'), 200);

    //     // 

    // }

    // public function index()
    // {
    //     $schedules = Schedule::with([
    //         'announcement:id,title,content',
    //         'link:id,title,url'
    //     ])->select('id', 'link_id', 'announcement_id', 'schedule', 'type')->where('is_active', 1)->get();
    //     $query = new Schedule();

    //     $links = (clone $query)->select('schedules.id', 'schedules.schedule', 'links.title', 'links.url')
    //         ->join('links', 'links.id', 'schedules.link_id')
    //         ->where('type', 'link')->get();
    //     $announcements = (clone $query)->select('announcements.title', 'announcements.content')
    //         ->join('announcements', 'announcements.id', 'schedules.announcement_id')
    //         ->where('type', 'announcement')->get();
    //     //return compact('link', 'announcement');
    //     /*  if (request()->expectsJson()) {
    //         return response()->json($schedules);
    //     } */

    //     return view('form_page', compact('schedules', 'links', 'announcements'));
    // }
    /**
     * Show the form for creating a new resource.
     */

    function showFormattedData()
    {
        $today = now()->format('Y-m-d');

        return response()->json([
            'announcements' => Schedule::with('announcement:id,title,content')
                ->where('type', 'announcement')
                ->where('is_active', 1)->orderBy('is_active', 'asc')
                ->get()
                ->pluck('announcement'),

            'links' => Schedule::with('link:id,title,url')
                ->where('type', 'link')
                ->where('schedule', $today)->where('is_active', 1)->orderBy('is_active', 'asc')
                ->get()
                ->pluck('link'),
        ]);
    }
}
