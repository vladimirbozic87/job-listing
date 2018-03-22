<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GoogleClientApi\GoogleClient;

class CompanyController extends Controller
{
    public function getAddCompany()
    {
        if (Auth::user()->company) {
            return redirect()->route('jobs');
        }

        return view('company.add');
    }

    public function postAddCompany(Request $request)
    {
        $this->validate($request, [
            'company_name' => 'required|unique:company|regex:/^[A-Za-z0-9\-\+\s]+$/',
            'company_info' => 'required',
            'client_secret' => 'required|mimes:txt',
        ]);

        $file = $request->file('client_secret');

        $name = 'client_secret_' . Auth::user()->email . '.json';

        $file->move(base_path() . '/google/client_secret/',$name);

        $client_secret = base_path() . '/google/client_secret/'.$name;

        $client = new GoogleClient($client_secret, '');

        $token_url = $client->getTokenFromWeb();

        $company = new Company();

        $company->company_name       = $request->input('company_name');
        $company->company_info       = $request->input('company_info');
        $company->client_secret_path = $client_secret;
        $company->user_id            = Auth::user()->id;
        $company->token_path         = '';
        $company->token_web_page     = $token_url;

        $company->save();

        return view('company.complit')
            ->with('company_id', $company->id)
            ->with('token_url', $token_url);
    }

    public function completeCompany(Request $request, string $company_id)
    {
        $this->validate($request, [
            'auth_token' => 'required',
        ]);

        $company = Company::find($company_id);

        $token = 'token_' . Auth::user()->email . '.json';

        $client = new GoogleClient($company->client_secret_path, $token);

        $auth_token = $request->input('auth_token');

        $client->setTokenFromWeb($auth_token);

        $company->token_path = $client->expandHomeDirectory($token);
        $company->complete   = 1;

        $company->save();

        return view('company.show')
            ->with('company', $company)
            ->with('status', 'success')
            ->with('msg', 'Company was successfully created')
            ;
    }

    public function showCompany(string $company_id)
    {
        $company = Company::find($company_id);

        if ( ! $company) {
            abort(404);
        }

        return view('company.show')->with('company', $company);
    }

    public function editCompany(Request $request, string $company_id)
    {
        $this->validate($request, [
            'company_info' => 'required',
        ]);

        $company = Company::find($company_id);

        $company->company_info = $request->input('company_info');

        $company->save();

        return view('company.show')
            ->with('company', $company)
            ->with('status', 'success')
            ->with('msg', 'Company was successfully updated')
            ;
    }

    public function getCompleteCompany(string $company_id)
    {
        if (
            ! Auth::user()->company ||
            (Auth::user()->company && Auth::user()->company->complete == 1) ||
            Auth::user()->company->id != $company_id
        ) {
            return redirect()->route('jobs');
        }

        $company = Company::find($company_id);

        if ( ! $company) {
            abort(404);
        }

        return view('company.complit')
            ->with('company_id', $company->id)
            ->with('token_url', $company->token_web_page);
    }

}
