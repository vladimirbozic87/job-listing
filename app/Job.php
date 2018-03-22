<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'deadline', 'user_id', 'company_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public function candidates()
    {
      return $this->belongsToMany(
          'App\Candidate',
          'job_candidate',
          'job_id',
          'candidate_id'
      );
    }

    public function company()
    {
        return $this->hasOne('App\Company', 'id', 'company_id');
    }

}
