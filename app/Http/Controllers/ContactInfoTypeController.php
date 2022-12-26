<?php

namespace App\Http\Controllers;

use App\Models\ContactInfoType;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContactInfoTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
        $this->middleware(['role:Admin']);
    }

    public function index()
    {
        $contactInfoTypes = ContactInfoType::all();
        return view('contactInfoTypes.index', ['contactInfoTypes' => $contactInfoTypes]);
    }

    public function create()
    {
        return view('contactInfoTypes.create');
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => ['required', 'max:255', 'unique:contact_info_types'],
            'url' => ['url', 'max:255'],
            'description' => ['nullable']
        ]);


        ContactInfoType::create($fields);

        return redirect('/contactInfoTypes')->with(['message' => 'ContactInfoType Created Successfully!']);
    }

    public function show($id)
    {
        $contactInfoType = ContactInfoType::find($id);
        if (!$contactInfoType) {
            throw new NotFoundHttpException();
        }

        return view('contactInfoTypes.show', ['contactInfoType', $contactInfoType]);
    }

    public function edit($id)
    {
        $contactInfoType = ContactInfoType::find($id);

        if (!$contactInfoType) {
            throw new NotFoundHttpException();
        }

        return view('contactInfoTypes.edit', ['contactInfoType' => $contactInfoType]);
    }

    public function update(Request $request, $id)
    {
        $contactInfoType = ContactInfoType::find($id);
        if (!$contactInfoType) {
            throw new NotFoundHttpException();
        }

        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'url' => ['url', 'max:255'],
            'description' => ['nullable']
        ]);

        if ($request->hasFile('image')) {
            $fields['image'] = $request->file('image')->store('contactInfoTypes', 'public');
        }

        $contactInfoType->update($fields);

        return redirect('/contactInfoTypes')->with(['message' => 'ContactInfoType Updated Successfully!']);
    }

    public function destroy($id)
    {
        $contactInfoType = ContactInfoType::find($id);

        if (!$contactInfoType) {
            throw new NotFoundHttpException();
        }

        $contactInfoType->delete();
        return redirect('/contactInfoTypes')->with(['message' => 'ContactInfoType Deleted Successfully!']);
    }
}
