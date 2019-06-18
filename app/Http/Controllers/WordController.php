<?php

namespace App\Http\Controllers;

use App\Word;
use Illuminate\Http\Request;
use App\Http\Helpers\OxfordApi;

class WordController extends Controller
{
  private $oxford = null;

  public function __construct() {
    $this->oxford = new OxfordApi();
  }

  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

//      $word = $this->oxford->callApi('afreet');
//      $lexi = $this->oxford->callLexiStats('afreet');

      /* TODO
      1. Check against DB if word or date is not yet taken
      2. Check to see if a word / lexi-stats actually exists before saving anything
      */

//      Word::create([
//        'word' => 'afreet',
//        'longdate' => '2019-01-02',
//        'word_meta' => serialize($word),
//        'lexi_stat_meta' => serialize($lexi)
//      ]);

      return view('word.index')->with('word', Word::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
//      $word = Word::all()->first();
//      return $word->unserialize();
      return view('word._create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
      return response()->json([D => $request->all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function show(Word $word)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function edit(Word $word)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Word $word)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function destroy(Word $word)
    {
        //
    }
}
