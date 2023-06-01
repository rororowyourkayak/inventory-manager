<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\ItemsImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class ImportController extends Controller
{
    public function viewImportPage(){
        return view('import');
    }
    public function importItemCSV(){
 
        $validator = Validator::make(request()->all(), [
            'file' => ['required', 'max:1024','mimes:csv'],
        ]);

        if($validator->fails()){
            return back()->with(['error'=>$validator->errors()]);
        } 

        $file = $validator->validated()['file']; 

        $expectedHeaders = ["upc","category","description", "quantity"]; 
        $headerRow = (new HeadingRowImport)->toArray($file);
     
        $missingHeaders = array_diff($expectedHeaders, $headerRow[0][0]); 

        $headerValues = "";
        foreach($missingHeaders as $h){ $headerValues .= "\"".$h . "\" ";}
        if(!empty($missingHeaders)){
            return back()->with(['error'=>'The following headers are missing from file: '. $headerValues]);
        }
    
   /*  try{
        Excel::import(new ItemsImport, $file);
    }
    catch(\Maatwebsite\Excel\Validators\ValidationException $e){
        $failures = $e->failures();
    
        return back()->with(['error'=> $failures]);
    } */
    $import = new ItemsImport(); 
    $import -> import($file);
    //$import = Excel::import(new ItemsImport, $file);

    if(!empty($import->failures())){
        $error = [];
        foreach($import->failures() as $failure){
            $temp = [];
            $temp["row"] = $failure->row();
            $temp["error"] = $failure->errors();
            array_push($error, $temp);
        }
        return back()->with(["error"=>$error]);
    }

    
    return redirect("/");



    }
}
