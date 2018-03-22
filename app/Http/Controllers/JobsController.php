<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Job;
use GoogleClientApi\GoogleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        $jobs = Job::where('name', 'ilike', "%$search%")->paginate(4);
        //$jobs = Job::where('name', 'like', "%$search%")->paginate(4);

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

        $company_id = Auth::user()->company->id;

        $job->name        = $request->input('name');
        $job->description = $request->input('description');
        $job->deadline    = date("Y-m-d", strtotime($request->input('deadline')));
        $job->user_id     = Auth::user()->id;
        $job->company_id  = $company_id;

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

        $company_id = Auth::user()->company->id;

        $job->name        = $request->input('name');
        $job->description = $request->input('description');
        $job->deadline    = date("Y-m-d", strtotime($request->input('deadline')));
        $job->user_id     = Auth::user()->id;
        $job->company_id  = $company_id;

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

        $file  = $request->file('cv_file');
        $file2 = $request->file('letter_file');

        $ext       = $file->extension();
        $name      = $request->input('email') . '-' . $job_name .'-cv.' . $ext;
        $file_path = base_path() . '/public/uploads/' . $name;

        $file_path2 = "";

        if ($file2) {
            $ext2       = $file2->extension();
            $name2      = $request->input('email') . '-' . $job_name . '-cover-letter.' . $ext2;
            $file_path2 = base_path() . '/public/uploads/' . $name2;
        }

        $job = Job::where('name', $job_name)->get()->first();

        $email = $request->input('email');

        $candidate = Candidate::where('email', $email)->get()->first();

        if ( ! $candidate) {
            $candidate = new Candidate();

            $candidate->fullname          = $request->input('fullname');
            $candidate->email             = $request->input('email');
            $candidate->phone             = $request->input('phone') ? $request->input('phone') : '';
            $candidate->cover_letter_path = $file_path2;
            $candidate->cv_path           = $file_path;

            $candidate->save();
        }

        $check = $job->candidates->where('id', $candidate->id)->first();

        if ( ! $check) {
            $job->candidates()->attach($candidate);

            $file->move(base_path() . '/public/uploads/',$name);

            $token_path = explode('/', $job->company->token_path);
            $token      = end($token_path);

            $client = new GoogleClient($job->company->client_secret_path, $token);

            $client->googleUploadFile($name, $file_path);

            if ($file2) {
                $file2->move(base_path() . '/public/uploads/',$name2);
                $client->googleUploadFile($name2, $file_path2);
            }

            $app_name = 'app-' . $candidate->email . '-' . $job->name . '.txt';

            $app_content  = "Job Application\n";
            $app_content .= "Job Name: "        . $job->name . "\n";
            $app_content .= "Job Description: " . $job->description . "\n";
            $app_content .= "Job Deadline: "    . $job->deadline . "\n";
            $app_content .= "Applicant Name: "  . $candidate->fullname . "\n";
            $app_content .= "Applicant Email: " . $candidate->email . "\n";
            $app_content .= "Applicant Phone: " . $candidate->phone . "\n";

            Storage::put($app_name, $app_content);

            $app_path = base_path() . '/storage/app/' . $app_name;

            $client->googleUploadFile($app_name, $app_path);

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
