<?php

use App\Event;
use App\Req;
use App\Queans;
use App\Subject;
use App\Option;

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        return view('pages.home');
    })->middleware('RedirectIfAuthenticated')->name('login');

    Route::get('/about', function () {
        return view('pages.about');
    });

    Route::post('login/{eventid?}', 'InfoConnectApiController@login')->middleware(['RedirectIfAuthenticated'])->name('LoginUrl');

    Route::get('auto/{userid}', 'InfoConnectApiController@auto')->middleware(['RedirectIfAuthenticated']);

    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/event/{id}', function ($id) {
        $event = Event::select('isactive', 'name', 'description', 'img', 'duration', 'correctmark', 'wrongmark', 'quedisplay')->where('id', $id)->first();
        if ($event->count() == 0) {
            return back()->with(['msg' => 'The event you are trying to visit does not exist', 'class' => 'alert-danger']);
        }
        if ($event->isactive == 1) {
            return view('student.event', ['event' => $event, 'id' => $id]);
        } else {
            return back()->with(['msg' => 'The event you are trying to access is invalid', 'class' => 'alert-danger']);
        }
    })->middleware(['RedirectIfAuthenticated']);

    Route::group(['middleware' => ['auth']], function () {

        Route::group(['prefix' => '/student', 'middleware' => 'UserType:student'], function () {
            Route::get('profile/{val?}', function ($val = null) {
                return view('student.profile', ['val' => $val]);
            });
            Route::post('profile', 'ProfileController@edit');
            Route::group(['middleware' => ['EventPlay','ProfileUpdate']], function () {
                Route::get('/', function () {
                    $events = Event::select('id', 'name', 'start', 'end', 'duration', 'quedisplay')->where('isactive', '1')->orderBy('id', 'desc')->paginate(9);
                    return view('student.home', ['events' => $events]);
                });
                Route::get('/event/{id}', function ($id) {
                    $event = Event::select('isactive', 'start', 'end', 'name', 'description', 'img', 'duration', 'correctmark', 'wrongmark', 'quedisplay')->where('id', $id)->first();
                    if ($event->count() == 0) {
                        return back()->with(['msg' => 'The event you are trying to visit does not exist', 'class' => 'alert-danger']);
                    }
                    $req = Req::select('status','start')->where('userid', Auth::id())->where('eventid', $id)->first();
                    if ($event->isactive == 1) {
                        return view('student.event', ['event' => $event, 'id' => $id, 'req' => $req]);
                    } else {
                        return back()->with(['msg' => 'The event you are trying to access is invalid', 'class' => 'alert-danger']);
                    }
                });
                Route::group(['middleware' => ['EventAccess']], function () {
                    Route::post('event/{id}/req', 'EventPlayController@req');
                    Route::post('event/{id}/join', 'EventPlayController@join');
                });
            });
            Route::post('/ajax/event/req', 'AjaxController@eventReq');
            Route::get('event/play/{queid}', 'EventPlayController@play');
            Route::post('/event/play/{queid}', 'EventPlayController@response');
            Route::post('/event/submit/{val?}', 'EventPlayController@submit');
        });

        Route::group(['prefix' => '/teacher', 'middleware' => 'UserType:teacher'], function () {
            Route::post('event/ques', 'EventController@create');
            Route::get('/', function () {
                $events = Event::select('id', 'name', 'start', 'end', 'duration', 'quedisplay', 'isactive')->where('creator', Auth::id())->orderBy('id', 'desc');
                $eventarrey = $events->get()->toArray();
                $events = $events->paginate(9);
                $quecount = Queans::select('eventid', DB::raw('count(id) as total'))->whereIn('eventid', array_column($eventarrey, 'id'))->groupBy('eventid')->get()->toArray();
                return view('teacher.home', ['events' => $events, 'quecount' => $quecount]);
            });
            Route::get('/allques', function () {
                $ques = DB::table('event')->join('queans', 'event.id', '=', 'queans.eventid')->select('queans.que')->where('creator',Auth::id())->get()->toArray();
                return view('teacher.allques',['ques' => $ques]);
            })->name('teacherAllQues');

            Route::get('/event/create', function () {
                $subject = Subject::select('*')->orderBy('name', 'asc')->get()->toArray();
                return view('teacher.create-event')->with('subject', $subject);
            })->name('teacherCreateEvent');
            
            Route::post('/ajax/event/req', 'AjaxController@eventReqs');
            Route::post('event/allowaccess/{id}', 'EventController@accessEvent');

            Route::group(['middleware' => ['EventOwner']], function ()
            {
                Route::get('event/view/{id}', function ($id) {
                    $quecount = Queans::where('eventid', $id)->get()->count();
                    $event = Event::select('name', 'description', 'subid', 'img', 'start', 'end', 'duration', 'correctmark', 'wrongmark', 'quedisplay', 'isactive')->where('id', $id)->first();
                    $subject = Subject::select('name')->where('id', $event->subid)->first();
                    return view('teacher.view-event', ['event' =>$event, 'id'=>$id, 'subject'=>$subject->name, 'quecount'=>$quecount]);
                });
                Route::get('event/edit/{id}', function ($id) {
                    $event = Event::findOrFail($id);
                    $subject = Subject::select('*')->get()->toArray();
                    return view('teacher.create-event', ['event' =>$event, 'id'=>$id, 'subject'=>$subject]);
                });
                Route::post('event/edit/{id}', 'EventController@editEvent');
                Route::get('event/launch/{id}', function ($id) {
                    $quecount = Queans::where('eventid', $id)->get()->count();
                    $event = Event::select('quedisplay', 'isactive', 'id', 'name')->findOrFail($id);
                    $req = Req::join('user', 'req.userid', '=', 'user.id')->select('userid', 'name', 'admno', 'rollno', 'status') ->where('eventid', $id)->get()->toArray();
                    if ($quecount >= $event->quedisplay) {
                        $event->isactive = 1;
                        $event->save();
                        return view('teacher.launched-event', ['req' => $req, 'event' => $event]);
                    } else {
                        return back();
                    }
                });
                Route::post('event/launch/{id}', 'EventController@launch');

                Route::get('event/{id}', function ($id) {
                    $queans = Queans::select('id', 'que')->where('eventid', $id)->get()->toArray();
                    return view('teacher.add-ques', ['id'=>$id, 'queans'=>$queans]);
                });
                Route::get('event/{id}/que/{qid}', function ($id, $qid) {
                    $que = Queans::findOrFail($qid);
                    $options = Option::select('id', 'ans', 'iscorrect')->where('queid', $qid)->get()->toArray();
                    return view('teacher.edit-ques', ['id'=>$id ,'options' => $options, 'qid' => $qid, 'que' => $que]);
                });
                Route::post('event/delete/{id}', 'EventController@deleteEvent');
                Route::post('event/{id}/edit/que/{qid}', 'EventController@editQue');
                Route::post('event/{id}/delete/que/{qid}', 'EventController@deleteQue');
                Route::post('event/{id}', 'EventController@add');
                Route::get('event/{id}/result', 'ResultController@view');
                Route::get('event/{id}/score', 'ResultController@score');
            });
        });

        Route::group(['prefix' => '/society', 'middleware' => 'UserType:society'], function () {
            Route::get('/', function () {
                return view('society.home');
            });
        });
    });
});
