<?php

namespace App\Services;

use App\Models\Information;
use App\Models\InformationVersion;
use App\Models\Attachment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class InformationService
{
    public function listAll($filters = [])
    {
        $query = Information::with(['category', 'author']);

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function createInformation(array $data, int $authorId)
    {
        return DB::transaction(function () use ($data, $authorId) {
            $slug = Str::slug($data['title']);
            // Ensure unique slug
            $originalSlug = $slug;
            $count = 1;
            while (Information::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-" . $count++;
            }

            $thumbnailPath = null;
            if (isset($data['thumbnail']) && $data['thumbnail']->isValid()) {
                $thumbnailPath = $data['thumbnail']->store('thumbnails', 'public');
            }

            $information = Information::create([
                'title' => $data['title'],
                'slug' => $slug,
                'category_id' => $data['category_id'],
                'thumbnail' => $thumbnailPath,
                'summary' => $data['summary'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'author_id' => $authorId,
                'published_at' => (isset($data['status']) && $data['status'] === 'published') ? now() : null,
            ]);

            // If initial version content is supplied
            if (!empty($data['rich_content']) && !empty($data['month']) && !empty($data['year'])) {
                $this->createOrUpdateVersion($information->id, [
                    'month' => $data['month'],
                    'year' => $data['year'],
                    'rich_content' => $data['rich_content'],
                ], $authorId, $data['attachment_file'] ?? null);
            }

            return $information;
        });
    }

    public function updateInformation(Information $information, array $data, int $userId)
    {
        return DB::transaction(function () use ($information, $data, $userId) {
            if ($information->title !== $data['title']) {
                $slug = Str::slug($data['title']);
                $originalSlug = $slug;
                $count = 1;
                while (Information::where('slug', $slug)->where('id', '!=', $information->id)->exists()) {
                    $slug = "{$originalSlug}-" . $count++;
                }
                $information->slug = $slug;
            }

            if (isset($data['thumbnail']) && $data['thumbnail']->isValid()) {
                // Delete old thumbnail if exists
                if ($information->thumbnail) {
                    Storage::disk('public')->delete($information->thumbnail);
                }
                $information->thumbnail = $data['thumbnail']->store('thumbnails', 'public');
            }

            $oldStatus = $information->status;
            $newStatus = $data['status'] ?? $information->status;

            $information->title = $data['title'];
            $information->category_id = $data['category_id'];
            $information->summary = $data['summary'] ?? $information->summary;
            $information->status = $newStatus;

            if ($oldStatus !== 'published' && $newStatus === 'published') {
                $information->published_at = now();
            }

            $information->save();

            return $information;
        });
    }

    public function createOrUpdateVersion(int $informationId, array $data, int $userId, $attachmentFile = null)
    {
        return DB::transaction(function () use ($informationId, $data, $userId, $attachmentFile) {
            $version = InformationVersion::updateOrCreate([
                'information_id' => $informationId,
                'month' => $data['month'],
                'year' => $data['year'],
            ], [
                'rich_content' => $data['rich_content'],
                'created_by' => $userId,
            ]);

            if ($attachmentFile && $attachmentFile->isValid()) {
                // Delete old attachment if exists for this version
                $oldAttachment = Attachment::where('information_version_id', $version->id)->first();
                if ($oldAttachment) {
                    Storage::disk('local')->delete($oldAttachment->file_path);
                    $oldAttachment->delete();
                }

                $filePath = $attachmentFile->store('attachments', 'local');
                Attachment::create([
                    'information_version_id' => $version->id,
                    'file_path' => $filePath,
                    'file_name' => $attachmentFile->getClientOriginalName(),
                    'file_size' => $attachmentFile->getSize(),
                    'mime_type' => $attachmentFile->getMimeType(),
                ]);
            }

            return $version;
        });
    }

    public function deleteInformation(Information $information)
    {
        return DB::transaction(function () use ($information) {
            // Delete thumbnail
            if ($information->thumbnail) {
                Storage::disk('public')->delete($information->thumbnail);
            }

            // Delete version attachments
            foreach ($information->versions as $version) {
                foreach ($version->attachments as $attachment) {
                    Storage::disk('local')->delete($attachment->file_path);
                }
            }

            $information->delete();
            return true;
        });
    }
}
