<?php

namespace App\Http\Controllers;

use App\Http\Requests\WordStoreRequest;
use App\Http\Requests\WordUpdateRequest;
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
      return view('word.index')->with('word', Word::all()->sortBy('longdate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $dates_taken = Word::all()->sortBy('longdate')->pluck('longdate', 'word');
      return view('word._create')->with('taken', $dates_taken);
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
      $updated_iso = $this->returnUpdateIso($d);
      $word_m = serialize($this->oxford->callApi($w));
      $lexi_m = serialize($this->oxford->callLexiStats($w));

      Word::create([
        'word' => $w,
        'longdate' => $d,
        'word_meta' => $word_m,
        'update_interval' => 6,
        'update_iso' => $updated_iso,
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
      return view('word._edit')->with('word', $word)->with('dates', Word::all()->sortBy('longdate')->pluck('longdate', 'word'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function update(WordUpdateRequest $request, Word $word)
    {

      /* Words match */
      if ($request->word === $word->word)
        return response()->json(['success' => 'Nothing was updated.']);
      else {
        /* TODO
          1. Check if words match
          2. If not, pull new word details and update [ interval date, interval_iso, entries/lexical meta ]
        */
        $word_m = serialize($this->oxford->callApi($request->word));
        $lexi_m = serialize($this->oxford->callLexiStats($request->word));

        $word->update([
          'word' => ucfirst($request->word),
          'word_meta' => $word_m,
          'lexi_stat_meta' => $lexi_m
        ]);

        return response()->json(['success' => 'Word was succesfully updated', 'word' => $word]);
      }

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

    private function returnUpdateIso($input_date) {
      $immutable = explode('-', $input_date);
      $y = $immutable[0];
      $m = $immutable[1];
      $day = $immutable[2];

      return explode(' ', Carbon::createFromDate($y, $m, $day)->addMonths(6))[0];
    }

}
