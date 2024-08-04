<?php

use App\Models\Parameter;
use App\Models\ParameterImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

function getAllParametersWithImages(?string $searchText = ''): array
{
    $parametersWithImages = [];
    try {
        $parametersQuery = Parameter::query();
        $parametersQuery->where('type', '=', Parameter::PARAMETER_TYPE_WITH_IMAGES);
        if ($searchText) {
            $parametersQuery->where(function ($query) use ($searchText) {
                return $query->
                where('id', '=', $searchText)
                    ->orWhere('title', 'LIKE', '%'.$searchText.'%');
            });
        }
        $availableParameters = $parametersQuery->get();

        foreach ($availableParameters as $index => $parameter) {
            $parameterImages = getParameterImagesByTypes($parameter);
            $parameterFullData = array_merge($parameter->getAttributes(), $parameterImages);
            $parametersWithImages[$index] = $parameterFullData;
        }
    } catch (Throwable $exception) {
        Log::error($exception->getMessage());
    }

    return $parametersWithImages;
}

function getParameterImagesByTypes(Parameter $parameter): array
{
    $imagesByTypes = [];
    try {
        $images = $parameter->images->toArray();
        if ($images) {
            foreach ($images as $parameterImage) {
                $imagesByTypes[$parameterImage['image_type']] = $parameterImage;
            }
        }
    } catch (Throwable $exception) {
        Log::error($exception->getMessage());
    }

    return $imagesByTypes;
}

function uploadingParameterImg(UploadedFile $imageFile): array
{
    $uploadingParameters = [];
    try {
        $originalImageName = string_translit(pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME));
        $imageName = time() . '_' . $originalImageName . '.' . $imageFile->getClientOriginalExtension();
        $imagePath = $imageFile->storeAs(ParameterImage::PARAMETER_IMAGE_STORAGE_FOLDER, $imageName);
        $imageUrl = Storage::url($imagePath);
        $uploadingParameters = [$imageName, $imageUrl];
    } catch (Throwable $exception) {
        Log::error($exception->getMessage());
    }

    return $uploadingParameters;
}

function deletingParameterImg(string $imageName): void
{
    try {
        $currentImagePath = ParameterImage::PARAMETER_IMAGE_STORAGE_FOLDER . '/' . $imageName;
        if(Storage::exists($currentImagePath)){
            Storage::delete($currentImagePath);
        }
    } catch (Throwable $exception) {
        Log::error($exception->getMessage());
    }
}

function string_translit(string $text): string
{
    try {
        $tr = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
            "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
            "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
            "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
            "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
            "Ё"=>"E","Є"=>"E","Ї"=>"YI","ё"=>"e","є"=>"e","ї"=>"yi",
            " "=> "_", "/"=> "_"
        );
        if (preg_match('/[^A-Za-z0-9_\-]/', $text)) {
            $text = strtr($text,$tr);
            $text = preg_replace('/[^A-Za-z0-9_\-.]/', '', $text);
        }
    } catch (Throwable $exception) {
        Log::error($exception->getMessage());
    }

    return $text;
}
