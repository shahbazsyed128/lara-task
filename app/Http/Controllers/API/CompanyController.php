<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return Company::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request)
    {
        $name ='placeholder.png';
        if($request->logo){
            $name = time().'.'.explode('/',explode(':',substr($request->logo,0,strpos($request->logo,';')))[1])[1];
            \Image::make($request->logo)->save(storage_path('app/public/').$name);
        }
        Company::create([
            'name'      => $request['name'],
            'email'     => $request['email'],
            'logo'      => $name,
            'website'   => $request['website'],
        ]);

        $details = [
            'title' =>  "Your Company '".$request['name']."' has been Rgistered",
            'body' =>   'Your Company has Been Registred Successfully'
        ];
        \Mail::to($request['email'])->send(new \App\Mail\CompanyMail($details));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyRequest $request, $id)
    {
        $company =  Company::findOrFail($id); 
        $currentLogo = $company->logo;
        if($request->logo != $currentLogo ){ 
            $name = time().'.'.explode('/',explode(':',substr($request->logo,0,strpos($request->logo,';')))[1])[1];
            \Image::make($request->logo)->save(storage_path('app/public/').$name);

            $request->merge([
                'logo' => $name,
            ]);

            $companyLogo = storage_path('app/public/').$currentLogo;
            if(file_exists($companyLogo)){
                @unlink($companyLogo);
            }
        }
        $company->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company =  Company::findOrFail($id);
        $company->delete();
        return ['message'=>'Company Deleted'];
    }
}
