<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{

    public function index()
    {
        //get customers
        $customers = Customer::when(request()->q, function($customers) {
                    $customers = $customers->where('name', 'like', '%'. request()->q . '%');
                })
                ->latest()
                ->paginate(5);

        //return inertia
        return Inertia::render('Apps/Customers/Index', [
            'customers' => $customers,
        ]);
    }

    public function create()
    {
        return Inertia::render('Apps/Customers/Create');
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'name'      => 'required',
            'phone_number'   => 'required|unique:customers',
            'address'   => 'required',
        ]);

        //create customer
        Customer::create([
            'name'      => $request->name,
            'phone_number'   => $request->phone_number,
            'address'   => $request->address,
        ]);

        //redirect
        return redirect()->route('apps.customers.index')->with([
            'success' => 'Customer created successfully',
        ]);
    }

    public function edit(Customer $customer)
    {
        return Inertia::render('Apps/Customers/Edit', [
            'customer' => $customer,
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'name'      => 'required',
            'phone_number'   => 'required|unique:customers,phone_number,'.$customer->id,
            'address'   => 'required',
        ]);

        //update customer
        $customer->update([
            'name'      => $request->name,
            'phone_number'   => $request->phone_number,
            'address'   => $request->address,
        ]);

        //redirect
        return redirect()->route('apps.customers.index')->with([
            'success' => 'Customer updated successfully',
        ]);
    }

    public function destroy($id)
    {
        //find customer by ID
        $customer = Customer::findOrFail($id);

        //delete customer
        $customer->delete();

        //redirect
        return redirect()->route('apps.customers.index');
    }
}
