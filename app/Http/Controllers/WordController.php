<?php

namespace App\Http\Controllers;

use App\Http\Requests\WordStoreRequest;
use App\Http\Requests\WordUpdateRequest;
use App\Interval;
use App\Word;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Helpers\OxfordApi;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Panther as Panth;


class WordController extends Controller
{
  private $oxford = null;
  private $data_errors = '{
                        "entry_error": "No entry data exists for this word",
                        "lexi_error": "No lexical stat data exists for this word"
                      }';

  public function __construct() {
    $this->oxford = new OxfordApi();
    $this->middleware('auth')->except(['returnStoredWord']);
  }

  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $interval = Interval::find(1);

      return view('word.index')->with('word', Word::all()->sortBy('longdate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $dates_taken = Word::all()->sortBy('longdate')->pluck('word', 'longdate');
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
      $word_m = $this->oxford->callApi($w);
      $lexi_m = null;

      if (!is_null($word_m)) {
        $word_m = serialize($word_m);
        $lexi_m = serialize($this->oxford->callLexiStats($request->word));
      } else {
        $word_m = serialize($this->data_errors);
        $lexi_m = serialize($this->data_errors);
      }

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
      $dates = Word::all()->sortBy('longdate')->pluck('word', 'longdate');
      return view('word.show')->with('word', $word)->with('dates', $dates);
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
      return view('word._edit')->with('word', $word)->with('dates', Word::all()->sortBy('longdate')->pluck('word', 'longdate'));
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
        $word_m = $this->oxford->callApi($request->word);

        if (!is_null($word_m)) {
          $word->word_meta = serialize($word_m);
          $word->lexi_stat_meta = serialize($this->oxford->callLexiStats($request->word));
        } else {
          $word->word_meta = serialize($this->data_errors);
          $word->lexi_stat_meta = serialize($this->data_errors);
        }

        $word->update([
          'word' => ucfirst($request->word),
          'word_meta' => $word->word_meta,
          'lexi_stat_meta' => $word->lexi_stat_meta
        ]);

        return response()->json(['success' => 'Word was succesfully updated', 'word' => $word]);
      }

    }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Word $word
   * @return \Illuminate\Http\Response
   * @throws \Exception
   */
    public function destroy(Word $word)
    {
        //
      $word->delete();
      return response()->json(['success' => 'Word has been deleted']);
    }

    public function test() {
//      $record = Word::where('longdate', '=', '2019-01-02')->first();
//      dd($record);

//      $word_m = serialize($this->oxford->callApi('yachty'));
//      $word_m = $this->oxford->callApi('yachty');
//
//      if (is_object($word_m))
//        dd(json_encode('{id: error}'));
//      else
//        dd($word_m);

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

    public function checkIfDateExist(string $date) {
      $exits = Word::where('longdate', $date)->get();

      if (count($exits) > 0)
        return response()->json(['success'=> 'A word with the date '. $date .' already exists. Please confirm to overwrite the data.']);
      else
        return response()->json(['error'=> 'No word data exists for this date '. $date .'.']);
    }

  /**
   * @param Request $request
   * @param Word $word
   * @return \Illuminate\Http\JsonResponse
   */
    public function moveWord(Request $request, Word $word) {

      if ($request->newDate === $word->longdate)
        return response()->json(['success'=> 'Current date and new date match. No words were affected.']);

      $record = Word::where('longdate', '=', $request->newDate)->first();

      if (!is_null($record)) {
        $record->update([
          'longdate' => $request->newDate,
          'update_interval' => 6,
          'update_iso' => $this->returnUpdateIso($request->newDate),
          'word_meta' => $word->word_meta,
          'lexi_stat_meta' => $word->lexi_stat_meta
        ]);
      } else {
        Word::create([
          'word' => $word->word,
          'longdate' => $request->newDate,
          'update_interval' => 6,
          'update_iso' => $this->returnUpdateIso($request->newDate),
          'word_meta' => $word->word_meta,
          'lexi_stat_meta' => $word->lexi_stat_meta
        ]);
        $word->delete();
      }

      return response()->json(['resp' => $request->newDate, 'word' => $word, 'record' => $record, 'success' => 'Word was succesfully moved.' ]);
    }

    public function returnStoredWord(string $id_date) {

      if ($id_date > '2019-01-01') {
        $record = Word::where('longdate', '=', filter_var($id_date, FILTER_SANITIZE_STRING))->first();

        /* Check for update word date
          – If time, get updated word and re–update the words entry and lexi meta in DB.
        */
        if (!is_null($record) && $id_date >= $record->update_iso) {

          $word_m = serialize($this->oxford->callApi($record->word));
          $lexi_m = serialize($this->oxford->callLexiStats($record->word));

          $record->update([
            'word' => ucfirst($record->word),
            'word_meta' => $word_m,
            'lexi_stat_meta' => $lexi_m,
            'update_interval' => 6,
            'update_iso' => $this->returnUpdateIso($id_date)
          ]);

        }

        if ($record)
          return response()->json(['date' => $record->longdate, 'word' => $record->word, 'entry' => json_decode(unserialize($record->word_meta), true), 'lexical' => json_decode(unserialize($record->lexi_stat_meta), true) ]);
        else
          return response('No record found.', 404);

      } else {
        return response('Invalid date.', 404);
      }

    }

    public function suggestNewWord() {
      $siteUrl = "https://randomword.com";
      $client = Panth\Client::createChromeClient();
      $crawler = $client->request('GET', $siteUrl);
      $word = $crawler->filter('#random_word')->getText();
      return response()->json(['suggested' => $word ]);
//      return response()->json(['suggested' => 'yachty' ]);
    }

}
