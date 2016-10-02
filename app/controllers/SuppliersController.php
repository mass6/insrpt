<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Insight\Companies\Company;
use Insight\Companies\CopySuppliersFromMasterListCommand;
use Insight\Companies\CopySuppliersFromMasterListCommandHandler;
use Insight\Companies\Supplier;

/**
 * Class SuppliersController
 */
class SuppliersController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id)
    {
        $suppliers = Company::find($id)->associatedSuppliers->toArray();

        return Response::json(['data' => $suppliers]);
    }

    /**
     * @return mixed
     */
    public function ajax()
    {
        $data = Input::get('data');
        $action = Input::get('action');

        if ($action == 'edit') {
            $supplier = Supplier::find(key($data));
            $supplier->update($data[key($data)]);
        }

        if ($action == 'create') {
            $company = Company::find(Input::get('company_id'));
            $supplier = new Supplier($data[key($data)]);
            $company->associatedSuppliers()->save($supplier);
        }

        return Response::json([
            'data' => [
                [
                    'id'              => $supplier->id,
                    'name'            => $supplier->name,
                    'email'           => $supplier->email,
                    'address'         => $supplier->address,
                    'primary_contact' => $supplier->primary_contact,
                    'telephone1'      => $supplier->telephone1,
                    'telephone2'      => $supplier->telephone2,
                    'fax'             => $supplier->fax,
                    'website'         => $supplier->website,
                    'description'     => $supplier->description,
                ]
            ]
        ]);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($company_id)
    {
        $data = Input::get('data');
        $payload = $data[key($data)];
        $company = Company::find($company_id);

        $validator = Validator::make(
            $payload,
            [
                'name'            => 'required|min:2:max:120|unique:suppliers,name,null,id,company_id,' . $company_id,
                'primary_contact' => 'max:50',
                'email'           => 'required|email',
            ]
        );
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->messages()->toArray() as $field => $messages) {
                $errors[] = ['name' => $field, 'status' => implode('<br/>', $messages)];
            }

            return Response::json(['fieldErrors' => $errors]);
        }

        $supplier = new Supplier($payload);
        $company->associatedSuppliers()->save($supplier);

        return Response::json([
            'data' => [
                [
                    'id'              => $supplier->id,
                    'name'            => $supplier->name,
                    'email'           => $supplier->email,
                    'address'         => $supplier->address,
                    'primary_contact' => $supplier->primary_contact,
                    'telephone1'      => $supplier->telephone1,
                    'telephone2'      => $supplier->telephone2,
                    'fax'             => $supplier->fax,
                    'website'         => $supplier->website,
                    'description'     => $supplier->description,
                ]
            ]
        ]);
    }


    /**
     * Copies all Suppliers from Master List
     *
     * @return mixed
     */
    public function copyFromMasterList()
    {
        $company = Company::findOrFail(Input::get('companyId'));
        $handler = new CopySuppliersFromMasterListCommandHandler;

        $numberOfSuppliersAdded = $handler->handle(new CopySuppliersFromMasterListCommand($company));

        if (! $numberOfSuppliersAdded && $numberOfSuppliersAdded !== 0){
            return Response::json([], 400);
        }

        return Response::json(['numberOfSuppliersAdded' => $numberOfSuppliersAdded]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param $company_id
     * @param $supplier_id
     * @return Response
     */
    public function update($company_id, $supplier_id)
    {
        $data = Input::get('data');

        foreach ($data as $id => $payload) {
            $nameValidator = 'min:2';
            if (array_key_exists('name', $payload)) {
                $nameValidator = 'required|min:2:max:120|unique:suppliers,name,' . $supplier_id . ',id,company_id,' . $company_id;
            }
            $validator = Validator::make(
                $payload,
                [
                    'name'            => $nameValidator,
                    'primary_contact' => 'max:50',
                    'email'           => 'email',
                ]
            );

            // if validation fails, return error messages
            if ($validator->fails()) {
                $errors = [];
                foreach ($validator->messages()->toArray() as $field => $messages) {
                    $errors[] = ['name' => $field, 'status' => implode('<br/>', $messages)];
                }

                return Response::json(['fieldErrors' => $errors]);
            }

            // validation passes, update the supplier
            $supplier = Supplier::find($id);
            $supplier->update($payload);

            $response[] = [
                'id'              => $supplier->id,
                'name'            => $supplier->name,
                'email'           => $supplier->email,
                'address'         => $supplier->address,
                'primary_contact' => $supplier->primary_contact,
                'telephone1'      => $supplier->telephone1,
                'telephone2'      => $supplier->telephone2,
                'fax'             => $supplier->fax,
                'website'         => $supplier->website,
                'description'     => $supplier->description,
            ];

        }

        return Response::json(['data' => $response]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $company_id
     * @param $supplier_id
     * @return Response
     */
    public function destroy($company_id, $supplier_id)
    {
        $data = Input::get('data');
        $supplier = Supplier::find($supplier_id);
        $supplier->delete();

        return Response::json([
            'data' => [
                [
                    'id'              => $supplier->id,
                    'name'            => $supplier->name,
                    'primary_contact' => $supplier->primary_contact,
                    'email'           => $supplier->email,
                    'telephone1'      => $supplier->telephone1,
                    'fax'             => $supplier->fax,
                    'website'         => $supplier->website,
                ]
            ]
        ]);
    }


}
