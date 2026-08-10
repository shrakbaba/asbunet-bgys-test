<?php

namespace app\components;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class SecureFileStorage
{
    public static function storePdf(UploadedFile $file, $category)
    {
        return self::store($file, $category, 'pdf');
    }

    public static function store(UploadedFile $file, $category, $extension)
    {
        $extension = strtolower((string)$extension);
        if (!in_array($extension, ['pdf', 'mp4'], true) || strtolower($file->extension) !== $extension) {
            throw new \InvalidArgumentException('Geçersiz dosya uzantısı.');
        }

        $directory = self::categoryDirectory($category);
        self::ensureDirectory($directory);

        $fileName = Yii::$app->security->generateRandomString(32) . '.' . $extension;
        $path = $directory . DIRECTORY_SEPARATOR . $fileName;
        if (!$file->saveAs($path, false)) {
            throw new \RuntimeException('Dosya güvenli depoya kaydedilemedi.');
        }

        return $fileName;
    }

    public static function find($fileName, $category, array $legacyDirectories = [])
    {
        $safeName = self::safeFileName($fileName);
        $directories = array_merge([self::categoryDirectory($category)], $legacyDirectories);

        foreach ($directories as $directory) {
            $candidate = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . $safeName;
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        throw new NotFoundHttpException('Dosya bulunamadı.');
    }

    public static function exists($fileName, $category, array $legacyDirectories = [])
    {
        try {
            $safeName = self::safeFileName($fileName);
        } catch (NotFoundHttpException $exception) {
            return false;
        }

        $directories = array_merge([self::categoryDirectory($category)], $legacyDirectories);
        foreach ($directories as $directory) {
            if (is_file(rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . $safeName)) {
                return true;
            }
        }

        return false;
    }

    public static function delete($fileName, $category, array $legacyDirectories = [])
    {
        if (!$fileName) {
            return;
        }

        $safeName = self::safeFileName($fileName);
        $directories = array_merge([self::categoryDirectory($category)], $legacyDirectories);
        foreach ($directories as $directory) {
            $candidate = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . $safeName;
            if (is_file($candidate) && !unlink($candidate)) {
                throw new \RuntimeException('Dosya güvenli biçimde silinemedi.');
            }
        }
    }

    private static function categoryDirectory($category)
    {
        if (!preg_match('/^[a-z0-9_-]+$/', $category)) {
            throw new \InvalidArgumentException('Geçersiz dosya kategorisi.');
        }

        return Yii::getAlias('@app/storage/uploads/' . $category);
    }

    private static function safeFileName($fileName)
    {
        $fileName = (string)$fileName;
        if ($fileName === '' || basename($fileName) !== $fileName || !preg_match('/^[A-Za-z0-9._-]+$/', $fileName)) {
            throw new NotFoundHttpException('Geçersiz dosya adı.');
        }

        return $fileName;
    }

    private static function ensureDirectory($directory)
    {
        if (!is_dir($directory) && !mkdir($directory, 0770, true) && !is_dir($directory)) {
            throw new \RuntimeException('Güvenli dosya dizini oluşturulamadı.');
        }
    }
}
