<?php

namespace App\Http\Controllers;

use App\Interval;
use Illuminate\Http\Request;

class IntervalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
      $interval = Interval::all()->first();

      if (is_null($interval)) {
        Interval::create([
          'interval'=> 6
        ]);
        $interval = Interval::all()->first();
      }

      return response()->json(['interval' => $interval]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param Interval $interval
   * @return \Illuminate\Http\Response
   */
    public function store(Request $request, Interval $interval)
    {

      $record = Interval::find($request->id);
      $record->update([
        'interval' => $request->new_interval,
      ]);

//      $interval->update([
//        'interval' => $interval->interval
//      ]);

      return response()->json(['success' => 'Updated', 'id' => $request->id, 'new' => $request->new_interval]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Intreval  $intreval
     * @return \Illuminate\Http\Response
     */
    public function show(Intreval $intreval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Intreval  $intreval
     * @return \Illuminate\Http\Response
     */
    public function edit(Intreval $intreval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Intreval  $intreval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Intreval $intreval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Intreval  $intreval
     * @return \Illuminate\Http\Response
     */
    public function destroy(Intreval $intreval)
    {
        //
    }
}
