<?php

namespace App\Http\Controllers;

use App\Http\Requests\WordStoreRequest;
use App\Word;
use Carbon\Carbon;
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
   * @param WordStoreRequest $request
   * @return \Illuminate\Http\Response
   */
    public function store(WordStoreRequest $request)
    {

      /* TODO
      A. Validation rules
      1. Check against DB if word or date is not yet taken -- Date checked via validation
      2. Check to see if a word / lexi-stats actually exists before saving anything
      */
      $w = ucfirst($request->word);
      $d = $request->longdate;
      $update_int = $request->update;
      $immutable = Carbon::now();
      $mutable = explode(' ', $immutable->addMonths($update_int))[0];
      $word_m = serialize($this->oxford->callApi($w));
      $lexi_m = serialize($this->oxford->callLexiStats($w));

      Word::create([
        'word' => $w,
        'longdate' => $d,
        'word_meta' => $word_m,
        'update_interval' => $update_int,
        'update_iso' => $mutable,
        'lexi_stat_meta' => $lexi_m
      ]);

      return response()->json(['success' => 'Word was successfully created.']);
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

    public function test() {
//      $word = json_decode($this->oxford->callApi('ace'), true);
//      $lexi = json_decode($this->oxford->callLexiStats('ace'), true);
//      $merged = array_merge_recursive($word, $lexi);


//      $word = Word::where('id', 1)->get();
//      return response()->json($merged);
//      return response()->json($lexi);
    }

}
