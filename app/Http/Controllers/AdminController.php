<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;

class AdminController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('admin.index', compact('clients'));
    }

    public function saveClient(Request $request)
    {
        $request->validate([
            'client_name' => 'required',
            'client_address' => 'required',
            'client_statement' => 'required',
            'manager_name' => 'required',
        ]);

        Client::create([
            'name' => $request->client_name,
            'address' => $request->client_address,
            'statement' => $request->client_statement,
            'manager_name' => $request->manager_name,
        ]);

        return redirect()->route('admin.index')->with('success', 'Client data saved successfully');
    }
    public function editClient($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.edit-client', compact('client'));
    }

    public function updateClient(Request $request, $id)
    {
        $request->validate([
            'client_name' => 'required',
            'client_address' => 'required',
            'client_statement' => 'required',
            'manager_name' => 'required',
        ]);

        $client = Client::findOrFail($id);
        $client->name = $request->client_name;
        $client->address = $request->client_address;
        $client->statement = $request->client_statement;
        $client->manager_name = $request->manager_name;
        $client->save();

        return redirect()->route('admin.index')->with('success', 'Client updated successfully');
    }

    public function deleteClient($id)
    {
        Client::destroy($id);
        return redirect()->route('admin.index')->with('success', 'Client deleted successfully');
    }
}


