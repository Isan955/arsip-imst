<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AttachmentController extends Controller
{
    public function stream($id)
    {
        $attachment = Attachment::with('version.information.category')->findOrFail($id);

        // Run Gate check
        if (!Gate::allows('stream-attachment', $attachment)) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat lampiran ini.');
        }

        $path = $attachment->file_path;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        $fullPath = Storage::disk('local')->path($path);

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $attachment->file_name . '"'
        ]);
    }
}
