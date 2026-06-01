<?php

namespace App\Http\Controllers;

use App\Enums\FileType;
use App\Http\Requests\File\StoreFileRequest;
use App\Models\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function index(Request $request): View
    {
        try {
            $fileType = $request->query('file_type');
            $courseId = $request->query('course_id');

            $query = File::where('user_id', Auth::id())
                ->with('course')
                ->orderByDesc('created_at');

            if ($fileType && in_array($fileType, FileType::values())) {
                $query->where('file_type', $fileType);
            }

            if ($courseId && is_numeric($courseId)) {
                $query->where('course_id', (int) $courseId);
            }

            $files    = $query->paginate(20)->withQueryString();
            $courses  = Auth::user()->courses()->orderBy('name')->get();
            $fileTypes = FileType::cases();

            return view('files.index', compact('files', 'courses', 'fileTypes', 'fileType', 'courseId'));
        } catch (\Exception $e) {
            Log::error('FileController@index failed', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function store(StoreFileRequest $request): RedirectResponse
    {
        try {
            $uploaded  = $request->file('file');
            $mimeType  = $uploaded->getMimeType() ?? 'application/octet-stream';
            $fileType  = FileType::fromMime($mimeType);
            $filename  = Str::uuid() . '.' . $uploaded->getClientOriginalExtension();
            $path      = $uploaded->storeAs(
                'files/' . Auth::id(),
                $filename,
                'private'
            );

            File::create([
                'user_id'       => Auth::id(),
                'course_id'     => $request->validated('course_id'),
                'task_id'       => $request->validated('task_id'),
                'original_name' => $uploaded->getClientOriginalName(),
                'filename'      => $filename,
                'path'          => $path,
                'mime_type'     => $mimeType,
                'file_type'     => $fileType->value,
                'size'          => $uploaded->getSize(),
            ]);

            return back()->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('FileController@store failed', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function download(File $file): StreamedResponse
    {
        abort_unless((int) $file->user_id === (int) Auth::id(), 403);

        return Storage::disk('private')->download($file->path, $file->original_name);
    }

    public function destroy(File $file): RedirectResponse
    {
        abort_unless((int) $file->user_id === (int) Auth::id(), 403);

        try {
            Storage::disk('private')->delete($file->path);
            $file->delete();

            return back()->with('success', 'File deleted.');
        } catch (\Exception $e) {
            Log::error('FileController@destroy failed', [
                'user_id' => Auth::id(),
                'file_id' => $file->id,
                'error'   => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
