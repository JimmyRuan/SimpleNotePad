<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNote;
use App\Http\Requests\UpdateNote;
use App\Http\Resources\NoteResource;
use App\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(Request $request) : View
    {
        return view('note.index');
    }

    public function list(Request $request) : JsonResponse
    {
        $notes = Note::orderBy('id', 'DESC')->get();

        return NoteResource::collection($notes)->toResponse(app('request'));
    }

    public function store(StoreNote $request): JsonResponse
    {
        $validated = $request->validated();
        $note = Note::create($validated);

        return NoteResource::make($note)->response($request);
    }

    public function updateColor(Request $request, $id): JsonResponse
    {
        $validated = $request->validate(['color' => 'required|max:255']);
        $note = Note::findOrFail($id);
        $note->color = $validated['color'];
        $note->save();

        return response()->json('', Response::HTTP_OK);
    }

    public function delete(Request $request, $id): JsonResponse
    {
        Note::findOrFail($id)->delete();

        return response()->json('', Response::HTTP_OK);
    }

    public function update(UpdateNote $request, $id): JsonResponse
    {
        $validated = $request->validated();
        $note = Note::findOrFail($id);
        $note->update($validated);

        return response()->json('', Response::HTTP_OK);
    }


}
