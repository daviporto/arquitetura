<?php

namespace App\Domain\Resume;

use App\Models\Resume;

class ResumeRepository implements ResumeRepositoryInterface
{
    public function save(array $data): array
    {
        return Resume::create($data)->toArray();
    }

    public function exists(int $resumeId): bool
    {
        return Resume::where('id', $resumeId)->exists();
    }

    public function get(int $resumeId): array
    {
        return Resume::find($resumeId)->toArray();
    }

    public function updateAlias(int $id, string $alias): void
    {
        Resume::where('id', $id)->update(['alias' => $alias]);
    }

    public function updateFile(int $id, string $path): void
    {
        Resume::where('id', $id)->update(['file_path' => $path]);
    }

    public function getResumes(int $ownerId): array
    {
        return Resume::where('owner_id', $ownerId)->get()->toArray();
    }

    public function delete(int $id): void
    {
        Resume::where('id', $id)->delete();
    }
}
