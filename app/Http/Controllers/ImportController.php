<?php

namespace App\Http\Controllers;

use App\Imports\ItemsImport;
use App\Imports\TotalItemsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\HeadingRowImport;
use App\Exports\ImportErrorsExport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function viewImportPage()
    {
        return view('import');
    }

    public function importItemCSV()
    {

        //check if file is appropriate size and is in fact a csv
        $validator = Validator::make(request()->all(), [
            'file' => ['required', 'max:1024', 'mimes:csv'],
            'option' => ['required', 'numeric', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_error' => $validator->errors()]);
        }

        $file = $validator->validated()['file'];
        $option = $validator->validated()['option'];

        //check for the correct headers being present in the csv
        $expectedHeaders = ["upc", "category", "description", "quantity"];
        $headerRow = (new HeadingRowImport)->toArray($file);

        $missingHeaders = array_diff($expectedHeaders, $headerRow[0][0]);

        $headerValues = "";
        foreach ($missingHeaders as $h) {$headerValues .= "\"" . $h . "\" ";}

        /* if something is missing return back and tell user what headers are missing */
        if (!empty($missingHeaders)) {
            return response()->json(['header_error' => 'The following headers are missing from file: ' . $headerValues]);
        }

        //case for allowing good values to go through, but still return errors
        if ($option == 2) {

            $import = new ItemsImport(); 
            $import->import($file);

            if(empty($import->errors) && empty($import->incrementedUPCS)){
                return response()->json(['success' => 'Import successful!']);
            }
            $errorsToExport = [];
            $errors = $incUPCs = [];
            if (!empty($import->errors)) {

                
                foreach ($import->errors as $failure) {
                    $temp = [];
                    $temp["row"] = $failure->row();
                    $temp["error"] = $failure->errors();
                    $temp["attr"] = $failure->attribute();
                    array_push($errors, $temp);

                    array_push($errorsToExport, $failure->values());
                }
                
                //dd($errorsToExport);

                //export error rows for partial fail
                Excel::download(new ImportErrorsExport($errorsToExport), 'import_errors.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
            }

            //if present get the UPCs that were incremented if they existed
            if(!empty($import->incrementedUPCS)){
                $incUPCs = $import->incrementedUPCS;
            }

            return response()->json(['partial_fail_import_errors' => ['errors'=>$errors, 'incUPCS'=> $incUPCs]]);

        } 
        
        //case for stopping entire import if any values are bad
        else {

            //TotalItemsImport type will do batch inserts for whole set, will rollback import in any errors are present
            $import = new TotalItemsImport();
        
            try {

                $import->import($file);
                return response()->json(['success' => 'Import successful!']); //if attempt does not throw errors return a success
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures(); //$e->failures() has all of the failures for the batch insert 
               
                $errors = []; 
                foreach($failures as $failure){
                    $temp = [];
                    $temp["row"] = $failure->row();
                    $temp["error"] = $failure->errors();
                    $temp["attr"] = $failure->attribute();
                    array_push($errors, $temp);
                }

                return response()->json(['full_fail_import_errors' => $errors]);
            } 

        }

    

    }

    
}
