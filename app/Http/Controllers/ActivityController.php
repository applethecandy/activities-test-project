<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Display a paginated list of activities.
     *
     * @return Response
     */
    public function index()
    {
        $activities = Activity::paginate(10);
        return response()->json($activities);
    }

    /**
     * Store a new activity or update an existing one.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Activity|null $activity
     * @return Response
     */
    public function store(Request $request, Activity $activity = null)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        if (!isset($activity)) {
            $activity = new Activity;
        } else {
            if ($user->id != $activity->user_id) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }
        }

        $activity->user_id = $user->id;
        $activity->meta = json_encode($request->input('meta', []));
        $activity->save();

        return response()->json($activity);
    }

    /**
     * Display the activity.
     *
     * @param \App\Models\Activity $activity
     * @return Response
     */
    public function show(Activity $activity)
    {
        return response()->json($activity);
    }

    /**
     * Delete the activity.
     *
     * @param \App\Models\Activity $activity
     * @return Response
     */
    public function delete(Activity $activity)
    {
        if (Auth::id() != $activity->user_id) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        $activity->delete();

        return response()->json(['message' => 'Activity deleted']);
    }
}