<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $timestamps = false;

    /**
     * One user to many event created relation.
     *
     * @return $this
     */
    public function event()
    {
        return $this->hasMany('App\Event', 'creator');
    }

    /**
     * One user to many event join requeest relation.
     *
     * @return $this
     */
    public function eventRequest()
    {
        return $this->belongsToMany('App\Event', 'req', 'userid', 'eventid')
            ->withPivot('status');
    }

    /**
     * Many response (submission) to one user relation.
     *
     * @return $this
     */
    public function response()
    {
        return $this->hasMany('App\Response', 'userid');
    }

    /**
     * One user to many response (submission) relation.
     *
     * @return $this
     */
    public function queResponse()
    {
        return $this->belongsToMany('App\Queans', 'response', 'userid', 'queid')
            ->withPivot('ans');
    }
}
