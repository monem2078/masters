<?php

namespace Services;

use Models\Master;
use Repositories\CategoryRepository;
use Repositories\CountryRepository;
use Repositories\MasterRepository;
use Illuminate\Database\DatabaseManager;
use \Illuminate\Database\Eloquent\Model;
use Repositories\MasterImageRepository;
use Repositories\ImageRepository;
use Repositories\MasterCategoryRepository;
use Repositories\PackageRepository;
use Repositories\UserRepository;

class MasterService extends BaseService
{
    private $packageRepository;
    private $categoryRepository;
    private $imageRepository;
    private $masterImagesRepository;
    private $masterCategoryRepository;
    private $userRepository;
    private $countryRepository;

    public function __construct(DatabaseManager $database,
                                ImageRepository $imageRepository,
                                MasterRepository $repository,
                                MasterImageRepository $masterImagesRepository,
                                MasterCategoryRepository $masterCategoryRepository,
                                PackageRepository $packageRepository,
                                CategoryRepository $categoryRepository,
                                UserRepository $userRepository ,
                                CountryRepository $countryRepository)
    {
        $this->setDatabase($database);
        $this->setRepository($repository);
        $this->imageRepository = $imageRepository;
        $this->masterImagesRepository = $masterImagesRepository;
        $this->masterCategoryRepository = $masterCategoryRepository;
        $this->packageRepository = $packageRepository;
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
        $this->countryRepository = $countryRepository;
    }

    public function prepareCreate(array $data)
    {

        $master = null;

        if (isset($data['master'])) {
            $master = $this->repository->create($data['master']);
        }

        if (isset($data['user']) && $master)
            $this->userRepository->update($data['user'], $master->user_id);

        if (isset($data['images']) && $master) {
            $createdImages = [];
            for ($i = 0; $i < count($data["images"]); $i++) {
                $image['original_image_url'] = $data['images'][$i]['original_image_url'];
                $image['image_url'] = $data['images'][$i]['image_url'];
                $createdImages[$i] = $this->imageRepository->create($image)->toArray();
                $createdImages[$i]['image_id'] = $createdImages[$i]['id'];
            }
            $master->images()->createMany($createdImages);
        }

        if (isset($data['categories']) && $master) {
            for ($i = 0; $i < count($data["categories"]); $i++) {
                $masterCategory["master_id"] = $master->id;
                $masterCategory["category_id"] = $data["categories"][$i];
                $this->masterCategoryRepository->create($masterCategory);
            }
        }


        return $master;
    }

    public function prepareUpdate(Model $model, array $data)
    {

        $masterId = $model->id;
        $master = $this->repository->find($masterId);

        if (isset($data['images'])) {
            $this->masterImagesRepository->deleteAllImagesForMaster($masterId);
            $createdImages = [];
            for ($i = 0; $i < count($data["images"]); $i++) {
                $image['original_image_url'] = $data['images'][$i]['original_image_url'];
                $image['image_url'] = $data['images'][$i]['image_url'];
                $createdImages[$i] = $this->imageRepository->create($image)->toArray();
                $createdImages[$i]['image_id'] = $createdImages[$i]['id'];
            }
            $master->images()->createMany($createdImages);
        }


        if (isset($data['categories'])) {
            $this->masterCategoryRepository->deleteAllCategoriesForMaster($masterId);
            for ($i = 0; $i < count($data["categories"]); $i++) {
                $masterCategory["master_id"] = $masterId;
                $masterCategory["category_id"] = $data["categories"][$i];
                $this->masterCategoryRepository->create($masterCategory);
            }

            $currentMasterPackages = $this->packageRepository->getMasterPackages($masterId)->toArray();
            $currentCategoryIds = $data['categories'];
            $packageCategoryIds = array_column($currentMasterPackages , 'category_id');
            $toBeDeletedCategoryIds = array_diff($packageCategoryIds , $currentCategoryIds);
            $this->packageRepository->deletePackagesByMasterIdAndCategoryIds($masterId , $toBeDeletedCategoryIds);
        }

        if (isset($data['sub_category_ids'])) {

            $this->masterCategoryRepository->deleteAllCategoriesForMaster($masterId);
//            $category_ids = [];
            for ($i = 0; $i < count($data["sub_category_ids"]); $i++) {
//                $category_ids['category_id'] = $data["sub_category_ids"][$i];
                $masterCategory["master_id"] = $masterId;
                $masterCategory["category_id"] = $data["sub_category_ids"][$i];
                $this->masterCategoryRepository->create($masterCategory);
            }
//            $master = $this->getById($masterId);
//            dd($category_ids);
//            $master->categories()->createMany($data['sub_category']);
        }

        if (isset($data['packages'])) {
            $master = $this->getById($masterId);
            $this->packageRepository->deletePackageByMasterId($masterId);
//            dd($data['packages']);
//            for ($i = 0; $i < count($data["packages"]); $i++) {
//                $packageCategory["master_id"] = $masterId;
//                $packageCategory["category_id"] = $data["packages"][$i]['category_id'];
//                $packageCategory["title"] = $data["packages"][$i]['title'];
//                $packageCategory["description"] = $data["packages"][$i]['description'];
//                $packageCategory["price"] = $data["packages"][$i]['price'];
//                $packageCategory["currency_id"] = $data["packages"][$i]['currency_id'];
//                $this->packageRepository->create($packageCategory);
//            }
            $master->packages()->createMany($data["packages"]);
        }


        if (isset($data['master'])) {
            $this->repository->update($data['master'], $masterId);
        } else {
            unset($data['sub_category_ids']);
            unset($data['packages']);
            $this->repository->update($data, $masterId);
        }

        if (isset($data['user']))
            $this->userRepository->update($data['user'], $master->user_id);

    }

    public function prepareDelete(int $id)
    {
        return $this->repository->delete($id);
    }

    public function getMasterByUserId(int $userId)
    {
        return $this->repository->getMasterByUserId($userId);
    }

    public function getPagedList($filter)
    {
        if (isset($filter->SearchObject)) {
            $params = (object)$filter->SearchObject;
        } else {
            $params = new \stdClass();
        }

        if (property_exists($filter, "SortBy") && $filter->SortBy == 'country_id') {
            $filter->SortBy = "users.country_id";
        }

        if (!property_exists($filter, "SortBy")) {
            $filter->SortBy = "masters.id";
        }
        if (!property_exists($filter, "SortDirection")) {
            $filter->SortDirection = "DESC ";
        }




        $masters = $this->repository->filterMasters($filter->PageNumber, $filter->PageSize, $params, $filter->SortBy, $filter->SortDirection);

        return $masters;
    }

    public function getMastersFilterData()
    {
        $filterData = [];
        $parentCategories = $this->packageRepository->getUsedParentCategories();
        for ($i = 0; $i < sizeof($parentCategories); $i++) {
            $category = $this->categoryRepository->find($parentCategories[$i]['id']);
            $category->subCategories;
            $parentCategories[$i] = $category;
        }

        $countries = $this->packageRepository->getUsedCountries();

       for($i = 0 ; $i< sizeof($countries) ; $i++){
           $country = $this->countryRepository->find($countries[$i]['id']);
           $country->cities;
           $countries[$i] = $country;
       }

        $filterData['countries'] = $countries;
        $filterData['categories'] = $parentCategories;
        $filterData['min_price'] = $this->packageRepository->getMinPrice()->minPrice;
        $filterData['max_price'] = $this->packageRepository->getMaxPrice()->maxPrice;
        $filterData['min_rating'] = $this->repository->getMinRating()->minRating;
        $filterData['max_rating'] = $this->repository->getMaxRating()->maxRating;

        return $filterData;
    }

    public function getMasterDetails($id, $userId, $seeContactInfo)
    {
        $master = $this->repository->getMasterDetails($id, $userId, $seeContactInfo);
        for($i = 0 ; $i<sizeof($master->categories) ; $i++){
            $subCategories = $this->categoryRepository
                ->getUsedSubCategories($master['categories'][$i]['id'] , $master['categories'][$i]['master_id']);

            $master['categories'][$i] = $subCategories;
        }
        return $master;
    }

    public function getMastersByName($name) {
        return $this->repository->getMastersByName($name);
    }

}
