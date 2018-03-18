<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Job;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobsController extends Controller
{
    /**
     * @param Request $request
     * @return $this
     */
    public function getJobs(Request $request)
    {
        $search = $request->input('search');

        $jobs = Job::where('name', 'like', "%$search%")->paginate(4);

        return view('jobs.jobs')
            ->with('jobs', $jobs)
            ->with('search', $search)
            ;
    }

    /**
     * @param string $job_name
     * @return $this
     */
    public function getJob(string $job_name)
    {
        $job = Job::where('name', $job_name)->get()->first();

        if ( ! $job) {
            abort(404);
        }

        return view('jobs.job')->with('job', $job);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAddJob()
    {
        return view('jobs.add');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postAddJob(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:jobs|regex:/^[A-Za-z0-9\-\+\s]+$/',
            'description' => 'required',
            'deadline' => 'required|date',
        ]);

        $job = new Job();

        $job->name        = $request->input('name');
        $job->description = $request->input('description');
        $job->deadline    = $request->input('deadline');

        $job->save();

        return view('jobs.job')
            ->with('job', $job)
            ->with('status', 'info')
            ->with('msg', 'The new job was successfully set up')
            ;
    }

    /**
     * @param Request $request
     * @param string $job_name
     * @return mixed
     */
    public function postEditJob(Request $request, string $job_name)
    {
        $this->validate($request, [
            'name' => [
                'required',
                'regex:/^[A-Za-z0-9\-\+\s]+$/',
                Rule::unique('jobs')->ignore($job_name, 'name'),
            ],
            'description' => 'required',
            'deadline' => 'required|date',
        ]);

        $job = Job::where('name', $job_name)->get()->first();

        $job->name        = $request->input('name');
        $job->description = $request->input('description');
        $job->deadline    = date("Y-m-d", strtotime($request->input('deadline')));

        $job->save();

        return view('jobs.job')
            ->with('job', $job)
            ->with('status', 'info')
            ->with('msg', 'This job has been changed')
            ;
    }

    /**
     * @param string $job_name
     * @return $this
     */
    public function getEditJob(string $job_name)
    {
        $job = Job::where('name', $job_name)->get()->first();

        if ( ! $job) {
            abort(404);
        }

        return view('jobs.edit')->with('job', $job);
    }

    /**
     * @param Request $request
     * @param string $job_name
     * @return mixed
     */
    public function postApplyJob(Request $request, string $job_name)
    {
        $this->validate($request, [
            'fullname'          => 'required',
            'email'             => 'required|email',
            'phone'             => '',
            'cv_file'           => 'required|mimes:pdf,txt,docx,doc',
            'letter_file'       => 'mimes:pdf,txt,docx,doc',
        ]);

        $file = $request->file('cv_file');

        $name = $file->getClientOriginalName();

        $file->move(base_path() . '/public/uploads/',$name);

        //dd($file);

        $job = Job::where('name', $job_name)->get()->first();

        $email = $request->input('email');

        $candidate = Candidate::where('email', $email)->get()->first();

        if ( ! $candidate) {
            $candidate = new Candidate();

            $candidate->fullname          = $request->input('fullname');
            $candidate->email             = $request->input('email');
            $candidate->phone             = $request->input('phone') ? $request->input('phone') : '';
            $candidate->cover_letter_path = "";
            $candidate->cv_path           = "";

            $candidate->save();
        }

        $check = $job->candidates->where('id', $candidate->id)->first();

        if ( ! $check) {
            $job->candidates()->attach($candidate);

            return view('jobs.apply')
                ->with('job_name', $job_name)
                ->with('status', 'success')
                ->with('msg', 'The application was successful')
                ;
        }

        return view('jobs.apply')
            ->with('job_name', $job_name)
            ->with('status', 'danger')
            ->with('msg', 'You have already applied for this position')
            ;
    }

    /**
     * @param string $job_name
     * @return $this
     */
    public function getApplyJob(string $job_name)
    {
        $job = Job::where('name', $job_name)->get()->first();

        if ( ! $job) {
            abort(404);
        }

        return view('jobs.apply')->with('job_name', $job_name);
    }

}
