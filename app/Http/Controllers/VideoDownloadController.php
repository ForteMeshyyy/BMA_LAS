<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoDownloadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'video' => 'required|mimetypes:video/mp4,video/avi,video/mov,video/wmv,video/mkv|max:102400',
        ]);

        $file         = $request->file('video');
        $originalName = $file->getClientOriginalName();
        $safeName     = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $extension    = $file->getClientOriginalExtension();
        $filename     = time() . '_' . $safeName . '.' . $extension;

        $file->move(storage_path('app/public/videos'), $filename);

        return redirect()->route('video.index')->with('success', 'Video uploaded successfully.');
    }

    public function index()
    {
        $folder = storage_path('app/public/videos');

        // Create folder if it doesn't exist
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        // Read all video files from the folder
        $files  = array_diff(scandir($folder), ['.', '..']);
        $videos = collect($files)->map(function ($filename) {
            return [
                'filename' => $filename,
                'url'      => asset('storage/videos/' . $filename),
                'uploaded' => date('Y-m-d H:i', filemtime(storage_path('app/public/videos/' . $filename))),
            ];
        })->sortByDesc('uploaded')->values();

        return view('videos.index', compact('videos'));
    }

    public function destroy(Request $request)
    {
        $filename = $request->input('filename');
        $filePath = storage_path('app/public/videos/' . $filename);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        return back()->with('success', 'Video deleted.');
    }
}