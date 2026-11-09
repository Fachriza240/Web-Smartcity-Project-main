<?php

namespace App\Services;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class PublicFileService
{
    public function save(Model $model, array $data, array $uploads = [], ?Closure $afterSave = null): Model
    {
        $created = [];

        try {
            return $model->getConnection()->transaction(function () use ($model, $data, $uploads, $afterSave, &$created) {
                if ($model->exists) {
                    $model = $model->newQuery()->lockForUpdate()->findOrFail($model->getKey());
                }

                $obsolete = [];
                $previous = $model->getAttributes();

                foreach ($uploads as $attribute => [$files, $directory]) {
                    if ($files === null || $files === []) {
                        continue;
                    }

                    $paths = [];

                    foreach (is_array($files) ? $files : [$files] as $file) {
                        if (! $file instanceof UploadedFile) {
                            throw new RuntimeException('File unggahan tidak valid.');
                        }

                        $path = $file->store($directory, 'public');

                        if (! is_string($path) || $path === '') {
                            throw new RuntimeException('File gagal disimpan. Silakan coba lagi.');
                        }

                        $created[] = $path;
                        $paths[] = $path;
                    }

                    $obsolete = array_merge($obsolete, (array) $model->getAttribute($attribute));
                    $data[$attribute] = is_array($files) ? $paths : $paths[0];
                }

                $model->fill($data);

                if (! $model->save()) {
                    throw new RuntimeException('Data gagal disimpan.');
                }

                if ($afterSave) {
                    $afterSave($model, $previous);
                }

                $model->getConnection()->afterCommit(fn () => $this->cleanup($obsolete));

                return $model;
            });
        } catch (Throwable $e) {
            $this->cleanup($created);

            throw $e;
        }
    }

    public function delete(Model $model, array $attributes, ?Closure $beforeDelete = null): void
    {
        $model->getConnection()->transaction(function () use ($model, $attributes, $beforeDelete) {
            $model = $model->newQuery()->lockForUpdate()->findOrFail($model->getKey());
            $paths = [];

            foreach ($attributes as $attribute) {
                $paths = array_merge($paths, (array) $model->getAttribute($attribute));
            }

            if ($beforeDelete) {
                $beforeDelete($model);
            }

            if (! $model->delete()) {
                throw new RuntimeException('Data gagal dihapus.');
            }

            $model->getConnection()->afterCommit(fn () => $this->cleanup($paths));
        });
    }

    private function cleanup(array $paths): void
    {
        $paths = array_values(array_unique(array_filter(
            $paths,
            fn ($path) => is_string($path) && $path !== ''
        )));

        if ($paths === []) {
            return;
        }

        try {
            if (! Storage::disk('public')->delete($paths)) {
                report(new RuntimeException('Sebagian file lama belum dapat dibersihkan.'));
            }
        } catch (Throwable $e) {
            report($e);
        }
    }
}